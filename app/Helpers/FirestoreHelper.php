<?php

namespace App\Helpers;

class FirestoreHelper
{

    public static function firestoreUserCreate($userData)
    {
        $userId = $userData->id;
        $apiKEY = config('constant.APIKEY');
        $projectId = config('constant.PROJECTID');


            $url = "https://firestore.googleapis.com/v1beta1/projects/$projectId/databases/(default)/documents/users/$userData->id";

            $curl1 = curl_init();
            curl_setopt_array($curl1, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'cURL',
            ));

            $response = json_decode(curl_exec($curl1));

           if(isset($response->error->code) == 404)
           {
                $emailMobileNumber =   $userData->email;

                    $firestore_data = [
                        "mobile_email" => ["stringValue" => $emailMobileNumber],
                        "name" => ["stringValue" => $userData->fullName ?? ""],
                        "profilePicture" => ["stringValue" => $userData->profileImage],
                        "serverUserId" => ["stringValue" => "111"],
                        "userId" => ["stringValue" => $userData->id],
                        "groups" => [
                            "arrayValue" => [
                                "values" => [],
                            ],
                        ],
                    ];

                    $url = "https://firestore.googleapis.com/v1beta1/projects/$projectId/databases/(default)/documents/users/" . $userId;

                    $data = ["fields" => (object) $firestore_data];

                    $json = json_encode($data);

                    try {
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => 'PATCH',
                            CURLOPT_HTTPHEADER => array('Content-Type: application/json',
                                'Content-Length: ' . strlen($json),
                                'X-HTTP-Method-Override: PATCH'),
                            CURLOPT_URL => $url . '?key=' . $apiKEY,
                            CURLOPT_USERAGENT => 'cURL',
                            CURLOPT_POSTFIELDS => $json,
                        ));
                        $response = curl_exec($curl);
                        curl_close($curl);

                        $userData->isFirebaseArray = 1;
                        $userData->save();
                        return true;
                    } catch (\Exception $e) {
                        return true;
                    }

           }else{
                     $emailMobileNumber =   $userData->email;

                    $firestore_data = [
                        "mobile_email" => ["stringValue" => $emailMobileNumber],
                        "name" => ["stringValue" => $userData->fullName ?? ""],
                        "profilePicture" => ["stringValue" => $userData->profileImage],
                    ];

                    $data = ["fields" => (object) $firestore_data];

                    $json = json_encode($data);

                    $url = "https://firestore.googleapis.com/v1beta1/projects/$projectId/databases/(default)/documents/users/$userData->id?updateMask.fieldPaths=name&updateMask.fieldPaths=mobile_email&updateMask.fieldPaths=profilePicture";

                    try {
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => 'PATCH',
                            CURLOPT_HTTPHEADER => array('Content-Type: application/json',
                                'Content-Length: ' . strlen($json),
                                'X-HTTP-Method-Override: PATCH'),
                            CURLOPT_URL => $url,
                            CURLOPT_USERAGENT => 'cURL',
                            CURLOPT_POSTFIELDS => $json,
                        ));

                        $response = curl_exec($curl);
                        curl_close($curl);

                    } catch (\Exception $e) {
                        return true;
                    }
           }
    }

}
