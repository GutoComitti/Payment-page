<?php
$asaasBaseUrl = config("app.env") === 'production' ? "https://www.asaas.com/" : "https://sandbox.asaas.com/";
return [
    'asaas' => [
        "api-base-url" => $asaasBaseUrl . 'api/v3/',
        "api-key" => env("ASAAS_API_KEY"),
        "wallet-id" => env("ASAAS_WALLET_ID"),
    ]
];
