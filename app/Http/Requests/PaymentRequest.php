<?php

namespace App\Http\Requests;

use App\Models\BillingType;
use App\Rules\CPF;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $input = [];
        // Taking out non-numeric characters from cpf string
        $input['ccCpf'] = preg_replace('/\D/', '', $this->input('ccCpf', ''));
        $input['cpf'] = preg_replace('/\D/', '', $this->input('cpf', ''));
        $this->merge($input);
        // Unsetting parameters that are comming as empty or null
        foreach($this->all() as $key => $param){
            if(is_null($param) || $param === ''){
                $this->offsetUnset($key);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $requiredIfCreditCard = "required_if:billingType," . BillingType::CARTAO_CREDITO;
        return [
            "billingType" => ['required', 'string', 'exists:billing_types,type'],
            "name" => ['required', 'string'],
            "cpf" => ['required', new CPF],
            "amount" => ['required', 'numeric', 'min:0', 'not_in:0'],
            "ccName" => [$requiredIfCreditCard, "string"],
            "ccNumber" => [$requiredIfCreditCard, "string"],
            "ccExpiration" => [$requiredIfCreditCard, "date_format:m-y", 'after_or_equal:today'],
            "ccCcv" => [$requiredIfCreditCard, "string"],
            "ccInstallmentCount" => ['sometimes', 'numeric', 'min:0', 'not_in:0'],
            "ccInstallmentValue" => ['sometimes', 'numeric', 'min:0', 'not_in:0'],
            "ccCpf" => [$requiredIfCreditCard, new CPF],
            "ccPostalCode" => [$requiredIfCreditCard, 'string'],
            "ccEmail" => [$requiredIfCreditCard, 'email'],
            "ccAddressNumber" => [$requiredIfCreditCard, 'string'],
            "ccPhone" => [$requiredIfCreditCard, 'string'],
        ];
    }

    public function messages()
    {
      return [
        'billingType' => "Invalid billing type.",
        'name' => "Invalid name.",
        'amount' => "Invalid amount.",
        'ccName' => "Invalid name for the credit card owner.",
        'ccNumber' => "Invalid credit card number.",
        'ccExpiration' => "Invalid credit card expiration date.",
        'ccCcv' => "Invalid credit card CCV.",
        "ccInstallmentCount" => "Invalid amount of installments.",
        "ccInstallmentValue" => "Invalid installment value.",
        'ccPostalCode' => "Invalid postal code.",
        'ccEmail' => "Invalid email.",
        'ccAddressNumber' => "Invalid address number.",
        'ccPhone' => "Invalid phone.",
      ];
    }
}
