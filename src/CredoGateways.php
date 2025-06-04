<?php 

  namespace CorexTech\CredoServices;

  class CredoGateways
  {
       public static function initiate_payment(array $request)
       {

            $headers = array(
                'Content-Type:application/json',
                'Authorization:'.config('services.credo.publishable_key')
            );

             // initiate curl with the url to send request
            $service_code = config('services.credo.acc_serv_key');
            $curl = curl_init(config('services.credo.url').'initialize');
            $data = json_encode([
                        "amount" => ($request['amount']) * 100,
                        "bearer" => 0,
                        "callbackUrl" => asset(config('services.credo.redirect')),
                        "channels" => ['card', 'bank'],
                        "currency" => $request['currency'],
                        "customerFirstName" => $request['firstname'],
                        "customerLastName" => $request['lastname'],
                        "customerPhoneNumber" => $request['phone'],
                        "email" => $request['email'],
                        'reference' => $request['reference'], //auth()->user()->application_id.Str::random(4),
                        'serviceCode' => $service_code,
                        'narration' => $request['narration'] !== null ? $request['narration'] : null,
                        'metadata' => ['customFields'=>[$request['metadata'] !== null ? $request['metadata'] : null ]],

                    ]);

            $data = Str::of($data)->replace('}}}', '}]}}');
            $data = Str::of($data)->replace('"customFields":{', '"customFields":[{');

             //dd($data);
             // return CURL response
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            // Send request data using POST method
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

            // Data conent-type is sent as JSON
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POST, true);

            // Curl POST the JSON data to send the request
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data->value);

            // execute the curl POST request and send data
            $result = curl_exec($curl);
            // dd($result);
            curl_close($curl);
            $data_result = json_decode($result);
            if($data_result == null){
                Log::warning($data_result);
                    Log::warning($data);
            }
            else{
                 return redirect()->away($data_result->data->authorizationUrl);
            }

        

        }

        public function getTransaction(string $transactionReference)
        {
            $ch=curl_init();
            curl_setopt($ch, CURLOPT_URL, config('services.credo.url').$transactionReference.'/verify');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization:'.config('services.credo.secret_key')));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $result = curl_exec($ch);
            curl_close($ch);
            return json_decode($result)->data->metadata;
        }
    
        public function getTransactionData(string $transactionReference)
        {
            $ch=curl_init();
            curl_setopt($ch, CURLOPT_URL, config('services.credo.url').$transactionReference.'/verify');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization:'.config('services.credo.secret_key')));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $result = curl_exec($ch);
            curl_close($ch);
            return json_decode($result)->data;
        }

      
  }