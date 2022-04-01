<?php

namespace App;

use App\Models\Flood;
use App\Models\Flood_Request;

class Notification
{
    function sendNotification($token, $title, $body, $action, $flood_id = 0, $request_id = 0)
    {
        $lat = 0;
        $lng = 0;
        $req = [];
        if ($flood_id != 0) {
            $flood = Flood::find($flood_id);
            $lat = $flood->lat;
            $lng = $flood->lng;
        }
        if ($request_id != 0) {
            $req = Flood_Request::find($request_id);
        }
        $curl = curl_init();

        $post = [
            'to' => "$token",
            'data' => [
                "lat" => "$lat",
                "lng" => "$lng",
                "request" => "$req",
                "action" => "$action"
            ],
            'notification' => [
                "title" => "$title",
                "body" => "$body"
            ]
        ];
        $encoded_post = json_encode($post);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $encoded_post,
            CURLOPT_HTTPHEADER => array(
                'Authorization: key=AAAAl-TfL5s:APA91bHZ26s2mEsyfiDwHCEwHUQIClSa3b2gwIKAfHeXaJBpXBcfYAIjcLfnGEfPEbjoFyM537tiIMPJSLoP-xHvjULaCxxQGmIkGyodc0u_cNnJbSeXvr5cDcfYmtijv_L6Av3TnIby',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // echo $response;
        return $response;
    }
    public static function notificationCreateFlood($token, $flood_id)
    {
        return self::sendNotification($token, "New flood point created #$flood_id", "Be aware there is new flood near you check Tawoon App", "create_point", $flood_id);
    }
    public static function notificationRemoveFlood($token, $flood_id)
    {
        return self::sendNotification($token, "Flood point removed #$flood_id", "Feel safe flood #$flood_id is removed please check Tawoon App", "remove_point");
    }
    public static function notificationRequestAccepted($token, $request_id)
    {
        return self::sendNotification($token, "Your Request #$request_id has been accepted", "Thank you for your Help Be aware there is new flood near you check Tawoon App", "request_update");
    }
    public static function notificationRequestRejected($token, $request_id)
    {
        return self::sendNotification($token, "Your Request #$request_id has been rejected", "Thank you for your help but unfortunately your request has been rejected", "request_update");
    }
    public static function notificationCreateRequest($token, $request_id)
    {
        return self::sendNotification($token, "New Request has been created #$request_id", "Hey admin please check new request and update it's state", "create_request", 0, $request_id);
    }
}
