<?php

namespace App\Http\Controllers;

use App\Actions\PaymentAction;
use App\DataTransferObjects\PaymentDto;
use App\Http\Requests\PaymentRequest;
use App\Models\BillingType;

class PaymentController extends Controller
{

    public function __construct()
    {

    }
    /**
     * Show the payment page
     */
    public function index()
    {
        $billingTypes = BillingType::all();
        return view('index', ["billingTypes" => $billingTypes]);
    }

    /**
     * Process the payment
     */
    public function checkout(PaymentRequest $request, PaymentAction $action)
    {
        $payment = $action->execute(PaymentDto::fromSuccessRequest($request));
        // Since different payment types have very different data, it makes sense to have a resource for each of them
        return $payment;
    }

    /**
     * Display information regarding the processed payment
     */
    public function success()
    {
    }
}
