<?php

namespace App\Http\Controllers;

use App\Actions\PaymentAction;
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
    }

    /**
     * Display information regarding the processed payment
     */
    public function success()
    {
    }
}
