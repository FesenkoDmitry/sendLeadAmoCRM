<?php

require_once "./src/config.php";

class AccessManager
{

    public static function getToken()
    {
    
        if (!file_exists(TOKEN_FILE)) {
             $tokenData = self::requestNewToken();
             $token = $tokenData['access_token'];
        } else {
            $tokenData = file_get_contents(TOKEN_FILE);
            $tokenData = json_decode($tokenData, true);
                if (!isset($tokenData["endTokenTime"])) {
                    $tokenData = self::requestNewToken();
                    $token = $tokenData['access_token'];
                }
                elseif ($tokenData["endTokenTime"] - 60 < time()) {
                    $tokenData = self::requestRefreshToken($tokenData['refresh_token']);
                    $token = $tokenData['access_token'];
                }  else {
                    $token = $tokenData['access_token'];
                }
        }

        return $token;

    }

    private static function requestRefreshToken($refreshToken)
    {
        $response = self::request(REFRESH_GRANT_TYPE, $refreshToken);
        $tokenData = self::saveToken($response);
        
        return $tokenData;
    }

    private static function requestNewToken()
    {
        $response = self::request(AUTH_GRANT_TYPE);
        $tokenData = self::saveToken($response);
                
        return $tokenData;
    }

    private static function saveToken(array $tokenData){
        
        $newTokenData = [
            "access_token"  => $tokenData['access_token'],
            "refresh_token" => $tokenData['refresh_token'],
            "token_type"    => $tokenData['token_type'],
            "expires_in"    => $tokenData['expires_in'],
            "endTokenTime"  => $tokenData['expires_in'] + time(),
        ];

        $f = fopen(TOKEN_FILE, 'w');
        fwrite($f, json_encode($newTokenData));
        fclose($f);
        
        return $newTokenData;
    }

    private static function request(string $grantType, string $refreshToken = ''){

        if ($grantType === AUTH_GRANT_TYPE){
            $data = [
                'client_id'     => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'grant_type'    => $grantType,
                'code' => AUTH_CODE,
                'redirect_uri'  => REDIRECT_URI,
            ];
        } else {
            $data = [
                'client_id'     => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'grant_type'    => $grantType,
                'refresh_token' => $refreshToken,
                'redirect_uri'  => REDIRECT_URI,
            ];
        }
        
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, CURL_OAUTH_TOKEN_USERAGENT);
        curl_setopt($curl, CURLOPT_URL, CURL_OAUTH_TOKEN_URL);
        curl_setopt($curl, CURLOPT_HTTPHEADER, CURL_OAUTH_HTTP_HEADER);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, CURL_REQUEST_METHOD);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($out, true);
        $output = 'Запрос токена:' . PHP_EOL;

        foreach ($response as $row){
            $output .= $row . PHP_EOL;
        }
        
         
        $f = fopen(LOG_FILE, 'a');
        fwrite($f, $output);
        fclose($f);
        return $response;
    }
}
