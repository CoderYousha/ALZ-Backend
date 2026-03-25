<?php


namespace App\Http\Services\Notification;

use App\Models\FcmToken;
use App\Models\Notification;
use Google\Auth\CredentialsLoader;


class BaseNotificationService
{

    protected $notificationType;
    protected $toUserIds = [];

    protected $notificationTitle = '',
              $notificationTitle_ar = '',
              $notificationBody = '',
              $notificationBody_ar = '',
              $data = [];


    protected function init(Notification $notification, array $data = []){
        $this->notificationType = $notification->type;
        $this->notificationTitle = $notification->title_en;
        $this->notificationTitle_ar = $notification->title_ar;
        $this->notificationBody = $notification->description_en;
        $this->notificationBody_ar = $notification->description_ar;
        $this->data = $data;

    }


    protected function getFcmNotificationBody(array $data){
        return [
            'click_action'=>'FLUTTER_NOTIFICATION_CLICK',
            // 'notificationType' => $this->notificationType,
            // 'notificationData' => $data,
        ];
    }


    /**
     *
     * $userIds its array of user ids we want to send notification for them
     *
     *
     */
    public function toFireBase( array $userIds, array $data){
        if(empty($userIds))
            return 0;

        $data = $this->getFcmNotificationBody($data);

        $this->toFireBaseEn($userIds, $data);
        $this->toFireBaseAr($userIds, $data);
        return true;

    }


    public function toFireBaseTopic(array $data, string $topic, string $lang = 'en'){

        $data = $this->getFcmNotificationBody($data);

        if($lang == 'ar'){
            $fcmData = $this->prepareFcmData(
                null,
                $this->notificationTitle_ar,
                $this->notificationBody_ar,
                $data
            );
            $this->callTopicApi($fcmData, $topic);
        }else{
            $fcmData = $this->prepareFcmData(
                null,
                $this->notificationTitle,
                $this->notificationBody,
                $data
            );
            $this->callTopicApi($fcmData, $topic);
        }

        return true;

    }

    /**
     *
     * $userIds its array of user ids we want to send notification for them
     *
     *
     */
    public function toFireBaseEn( array $userIds, array $data) :void
    {

        if(empty($userIds))
            return;

        $tokensEn = FcmToken::whereIn('user_id',$userIds)
            ->where('lang','en')
            ->pluck('token');


        $fcmData = $this->prepareFcmData(
            $tokensEn,
            $this->notificationTitle,
            $this->notificationBody,
            $data
        );

        $this->callApi($fcmData);
    }


    /**
     *
     * $userIds its array of user ids we want to send notification for them
     *
     *
     */
    public function toFireBaseAr( array $userIds, array $data) : void
    {

        if(empty($userIds))
            return;

        $tokensAr = FcmToken::whereIn('user_id',$userIds)
            ->where('lang','ar')
            ->pluck('token');


        $fcmData = $this->prepareFcmData(
            $tokensAr,
            $this->notificationTitle_ar,
            $this->notificationBody_ar,
            $data
        );

        $this->callApi($fcmData);

    }

    /**
     * @param array $fcmTokens
     * @param string $title
     * @param string $body
     * @param array $data
     *
     * @return array
     */
    private function prepareFcmData($fcmTokens, $title, $body, $data)
    {
        $notification = [
            "title" => $title,
            "body" => $body,
            'notificationType' => $this->notificationType,
            "description" => [
                'Alarm'=> $body,
            ],
            'sound' => 1,
        ];

        if(isset($this->data['id'])){
            $notification['id'] = $this->data['id'];
        }

        return [
            "registration_ids" => $fcmTokens,
            "notification" => $notification,

            "data" => $data,
        ];
    }



    private function callApi(array $data)
    {


        // Path to your service account key file
        $storagePath = storage_path();
        $serviceAccountFile = $storagePath . '/app' . '/firebase-adminsdk.json';
        $projectId = "firebase";
        // Define the required scopes
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        // Create a credentials object
        $credentials = CredentialsLoader::makeCredentials($scopes, json_decode(file_get_contents($serviceAccountFile), true));

        // Create a callable HTTP handler
        $authHttpHandler = \Google\Auth\HttpHandler\HttpHandlerFactory::build(new \GuzzleHttp\Client());

        // Fetch the OAuth2 token
        $token = $credentials->fetchAuthToken($authHttpHandler);
        $accessToken = $token['access_token'];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        foreach ($data['registration_ids'] as $token) {
            $final_data = [
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $data['notification']['title'],
                        "body" => $data['notification']['body'],
                    ],
                    "data" => [
                        "notificationData" => json_encode($data['data']),
                        "notificationTitle" => (string)$data['notification']['title'],
                        "notificationBody" => (string)$data['notification']['body'],
                        'notificationType' => (string)$this->notificationType,
                    ],
                    "android" => [
                        "notification" => [
                            "sound" => 'default'
                        ],
                    ],
                    "apns" => [
                        "payload" => [
                            "aps" => [
                                "sound" => 'default'
                            ],
                        ],
                    ],
                ]
            ];
            
            if(isset($this->data['id'])){
                $final_data['message']['data']['id'] = (string)$this->data['id'];
            }

            $dataString = json_encode($final_data);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/$projectId/messages:send");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
            if ($response === false) {
                throw new \Exception('Curl error: ' . curl_error($ch));
            }
            curl_close($ch);


        }
    }

    private function callTopicApi(array $data, string $topic)
    {
        // Path to your service account key file
        $storagePath = storage_path();
        $serviceAccountFile = $storagePath . '/app' . '/firebase-adminsdk.json';
        $projectId = "firebase";
        // Define the required scopes
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        // Create a credentials object
        $credentials = CredentialsLoader::makeCredentials($scopes, json_decode(file_get_contents($serviceAccountFile), true));

        // Create a callable HTTP handler
        $authHttpHandler = \Google\Auth\HttpHandler\HttpHandlerFactory::build(new \GuzzleHttp\Client());

        // Fetch the OAuth2 token
        $token = $credentials->fetchAuthToken($authHttpHandler);
        $accessToken = $token['access_token'];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        $final_data = [
            "message" => [
                'topic' => $topic,
                "notification" => [
                    "title" => $data['notification']['title'],
                    "body" => $data['notification']['body'],
                ],
                "data" => [
                    "notificationData" => json_encode($data['data']),
                    "notificationTitle" => (string)$data['notification']['title'],
                    "notificationBody" => (string)$data['notification']['body'],
                    'notificationType' => (string)$this->notificationType,
                ],
                "android" => [
                    "notification" => [
                        "sound" => 'default'
                    ],
                ],
                "apns" => [
                    "payload" => [
                        "aps" => [
                            "sound" => 'default'
                        ],
                    ],
                ],
            ]
        ];
        
        if(isset($this->data['id'])){
            $final_data['message']['data']['id'] = (string)$this->data['id'];
        }

        $dataString = json_encode($final_data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/$projectId/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);

    }

}
