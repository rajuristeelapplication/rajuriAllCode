<?php
namespace App\Helpers;


/**
 * Firebase helper class
 */
class CurlCallHelper {

    public static function commonCurlCall($requestMethod,$url,$bodyData,$queryData)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request($requestMethod, $url, [
            // 'body' => json_encode($bodyData),
            // 'query' => $queryData,
            // 'headers' => [
            //     'apiKey' => env('COMETCHAT_API_KEY'),
            //     'Accept' => 'application/json',
            //     'Content-Type' => 'application/json',
            //     'accept-encoding'=> 'gzip, deflate',
            // ],
          ]);

          $result = json_decode((string) $response->getBody(), true);

          return $result;
    }
}
?>
