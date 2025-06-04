<?php

namespace CorexTech\CredoServices\Controllers;

use Illuminate\Http\Request;
use CorexTech\CredoServices\CredoGateways;
use Illuminate\Support\Facades\Log;

class CredoController
{

    /**
     * initiate payment.
     * payment callback.
     * payment webhook.
     * corexTech packages
     * @return void
     */


    public function initiate(Request $request,CredoGateways $credo_gateways) 
    {
        $data=[
            "amount" => ($request->amoumt),
            "currency" => $request->currency, // current accepted currency'NGN",
            "customerFirstName" => $request->firstname,
            "customerLastName" => $request->surname,
            "customerPhoneNumber" => $request->phone,
            "email" => $request->email,
            'reference' => $request->reference,  //Str::random(8) generate random number for transaction reference,
            'serviceCode' => $service_code,
            'narration' => $request->narration,
            'metadata' => array($request->array) // pass in an array data,

        ];

        $response = $credo_gateways->initiate_payment($data);

        dd($response);

        // return $response;

    }

    public function callback(Request $request,CredoGateways $credo_gateways) 
    {
        $transaction_data = $credo_gateways->getTransactionData($request->transRef); 
         
         dd($transaction_data);

        // code here

        
    }

    public function transaction_webhook(Request $request,CredoGateways $credo_gateways) 
    {
        $header = $request->header('x-credo-signature') == hash('sha512', 'w3GiEJb8ncpIt6dwT4qzFE8LbGPnO9nzPkFBfejlsJE'.$request['data']['businessCode']); 
        if($header)
        {
            $data = $request['data'];
            $meta_data = $credo_gateways->getTransaction($data['transRef']);

            // test using ngrok...

            dd($meta_data);

            // save data

        }
        else
        {
            Log::info($data);
        }
         
    }
}