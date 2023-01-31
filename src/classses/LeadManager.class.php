<?php

require_once "./src/config.php";
require_once "./src/classes/AccessManager.class.php";

class LeadManager
{
    public static function send(array $data)
    {
        $data = self::formatData($data);
        $accessToken = AccessManager::getToken();
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
        ];
        self::requestSendLead($data, $headers);
    }

    private static function formatData(array $data)
    {

        $name = $data['name'] ?? 'Неизвестный контакт';
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? '';
        $price = (int) $data['price'] ?? '0';

        $data = [
            [
                "name" => $name,
                "price" => $price,
                "_embedded" => [
                    "contacts" => [
                        [
                            "first_name" => $name,
                            "custom_fields_values" => [
                                [
                                    "field_code" => "EMAIL",
                                    "values" => [
                                        [
                                            "value" => $email
                                        ]
                                    ]
                                ],
                                [
                                    "field_code" => "PHONE",
                                    "values" => [
                                        [
                                            "value" => $phone
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                        "companies" => [
                                [
                                    "name" => $name
                                ]
                        ]
                    ]
                ]
            
        ];

        return $data;
    }

    private static function requestSendLead(array $data, array $headers)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, CURL_SEND_LEAD_USERAGENT);
        curl_setopt($curl, CURLOPT_URL, CURL_SEND_LEAD_URL);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, CURL_REQUEST_METHOD);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $out = curl_exec($curl);

        $f = fopen(LOG_FILE, 'a');
        fwrite($f, $out);
        fclose($f);

        $response = json_decode($out, true);
    }
}
