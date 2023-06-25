<?php

namespace App\Services;

use App\DataTransferObjects\BoletoPaymentDto;
use App\DataTransferObjects\CreditCardPaymentDto;
use App\DataTransferObjects\PaymentDto;
use App\DataTransferObjects\PixPaymentDto;
use App\Exceptions\PaymentApiException;
use App\Exceptions\PaymentException;
use App\Models\BillingType;
use App\Models\Payment;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Http;

class AsaasPaymentService
{

    private string $apiKey;
    private string $walletId;
    private string $apiBaseUrl;
    private string $customersBaseUrl;
    private array $defaultHeaders;
    public function __construct(string $apiKey, string $walletId, string $apiBaseUrl){
        $this->apiKey = $apiKey;
        $this->walletId = $walletId;
        $this->apiBaseUrl = $apiBaseUrl;
        $this->customersBaseUrl = $this->apiBaseUrl . 'customers/';
        $this->defaultHeaders = [
            "Content-Type" => "application/json",
            "access_token" => $this->apiKey
        ];
    }

    public function pay(PaymentDto $paymentDto): Payment{
        $payment = match ($paymentDto->billingType){
            BillingType::CARTAO_CREDITO => $this->payCreditCard($paymentDto),
            BillingType::BOLETO => $this->payBoleto($paymentDto),
            BillingType::PIX => $this->payPix($paymentDto),
            default => throw new PaymentException("Invalid billing type.")
        };
        return $payment;
    }

    private function getBaseBillingPayload(PaymentDto $paymentDto): array{
        return [
            "customer" => $this->ensureCustomer($paymentDto),
            "billingType" => $paymentDto->billingType,
            "value" => $paymentDto->amount,
            "dueDate" => (new DateTime())->format('Y-m-d')
        ];
    }

    private function payCreditCard(CreditCardPaymentDto $paymentDto): Payment{
        $payload = $this->getCreditCardPaymentPayload($paymentDto);
        $url = $this->apiBaseUrl . 'payments/';
        $response = Http::withHeaders($this->defaultHeaders)->post($url, $payload);
        if($response->failed()){
            // ! log information regarding the payment information and the return from the API?
            throw new PaymentApiException("An error occurred while trying to process the payment.");
        }
        $result = json_decode($response->body());
        $externalBillingId = $result->id;
        $billingTypeId = BillingType::where("type", $paymentDto->billingType)->first()->id;

        /**
         * *To store credit card information the system must be built differently.
         * For now we should just pass it to Asaas and not store it. Source:
         * https://www.pcidssguide.com/how-to-store-credit-card-information/
         */
        $payment = new Payment([
            "value" => $payload["value"],
            "billing_type_id" => $billingTypeId,
            "external_billing_id" => $externalBillingId,
            "customer_id" => $payload["customer"],
        ]);
        $payment->save();
        return $payment;
    }

    private function getCreditCardPaymentPayload(CreditCardPaymentDto $paymentDto){
        $payload = $this->getBaseBillingPayload($paymentDto);
        if($paymentDto->ccInstallmentCount > 1 && $paymentDto->ccInstallmentValue > 0){
            $payload["ccInstallmentCount"] = $paymentDto->ccInstallmentCount;
            $payload["ccInstallmentValue"] = $paymentDto->ccInstallmentValue;
            $payload["value"] = $paymentDto->ccInstallmentCount * $paymentDto->ccInstallmentValue;
        }
        $payload["remoteIp"] = $paymentDto->remoteIp;
        $payload["creditCard"] = [
            "holderName" => $paymentDto->ccName,
            "number" => $paymentDto->ccNumber,
            "expiryMonth" => $paymentDto->ccExpiration->format("m"),
            "expiryYear" => $paymentDto->ccExpiration->format("y"),
            "ccv" => $paymentDto->ccCcv,
        ];
        $payload["creditCardHolderInfo"] = [
            "name" => $paymentDto->ccName,
            "email" => $paymentDto->ccEmail,
            "cpfCnpj" => $paymentDto->ccCpf,
            "postalCode" => $paymentDto->ccPostalCode,
            "addressNumber" => $paymentDto->ccAddressNumber,
            "phone" => $paymentDto->ccPhone,
        ];
        return $payload;
    }

    private function payBoleto(BoletoPaymentDto $paymentDto): Payment{
        $daysInterval = DateInterval::createFromDateString("30 days");
        $endDate = (new DateTime())->add($daysInterval)->format("Y-m-d");
        $payload = [
            "name" => "Pagamento pelo link do sistema Asaas", //Business rule: define which would be the name for the link
            "billingType" => BillingType::BOLETO,
            "chargeType" => "DETACHED",
            "value" => $paymentDto->amount,
            "dueDateLimitDays" => 30,
            "endDate" => $endDate
        ];
        $url = $this->apiBaseUrl . 'paymentLinks';
        $response = Http::withHeaders($this->defaultHeaders)->post($url, $payload);
        if($response->failed()){
            // ! log information regarding the payment information and the return from the API?
            throw new PaymentApiException("An error occurred while trying to process the payment.");
        }
        $result = json_decode($response->body());
        $externalBillingId = $result->id;
        $paymentLink = $result->url;
        $billingTypeId = BillingType::where("type", $paymentDto->billingType)->first()->id;
        $payment = new Payment([
            "value" => $payload["value"],
            "billing_type_id" => $billingTypeId,
            "external_billing_id" => $externalBillingId,
            "payment_link" => $paymentLink
        ]);
        $payment->save();
        return $payment;
    }

    private function payPix(PixPaymentDto $paymentDto): Payment{
        $payload = $this->getBaseBillingPayload($paymentDto);
        $url = $this->apiBaseUrl . 'payments/';
        $response = Http::withHeaders($this->defaultHeaders)->post($url, $payload);
        if($response->failed()){
            // ! log information regarding the payment information and the return from the API?
            throw new PaymentApiException("An error occurred while trying to process the payment.");
        }
        $result = json_decode($response->body());
        $externalBillingId = $result->id;
        $qrCodeRequestUrl = $this->apiBaseUrl . 'payments/' . $externalBillingId . '/pixQrCode';
        $response = Http::withHeaders($this->defaultHeaders)->post($qrCodeRequestUrl);
        if($response->failed()){
            // ! log information regarding the payment information and the return from the API?
            throw new PaymentApiException("An error occurred while trying to process the payment.");
        }
        $result = json_decode($response->body());
        $encodedImage = $result->encodedImage;
        $qrCodePayload = $result->payload;
        $expirationDate = $result->expirationDate;
        $billingTypeId = BillingType::where("type", $paymentDto->billingType)->first()->id;
        $payment = new Payment([
            "value" => $payload["value"],
            "billing_type_id" => $billingTypeId,
            "external_billing_id" => $externalBillingId,
            "pix_encoded_image" => $encodedImage,
            "pix_qr_code_payload" => $qrCodePayload,
            "pix_expiration_date" => $expirationDate,
        ]);
        $payment->save();
        return $payment;
    }

    /**
     * Creates a customer if it doesn't exist, and return it's ID
     */
    public function ensureCustomer(PaymentDto $customerInfo): string{
        $headers = [
            "access_token" => $this->apiKey
        ];
        $payload = [
            "cpfCnpj" => $customerInfo->cpf
        ];
        $getResponse = Http::withHeaders($headers)->get($this->customersBaseUrl, $payload);
        if($getResponse->failed()){
            // ! log information regarding the payment information and the return from the API?
            throw new PaymentApiException("An error occurred while trying to process the payment.");
        }
        $customers = json_decode($getResponse->body())->data;
        if(count($customers) >= 1){
            return $customers[0]->id;
        }
        $payload["name"] = $customerInfo->name;
        $headers["Content-Type"] = "application/json";
        $postResponse = Http::withHeaders($headers)->post($this->customersBaseUrl, $payload);
        if($postResponse->failed()){
            // ! log information regarding the payment information and the return from the API?
            throw new PaymentApiException("An error occurred while trying to process the payment.");
        }
        $newCustomer = json_decode($postResponse->body());
        return $newCustomer->id;
    }

}
