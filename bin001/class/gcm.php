<?php

class GcmUtil {

    function sendMsg($arr,$msg) {

        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $arr,
            'data' => array("message" => $msg),
        );
// ### NEED TO UPDATE API KEY HERE ###
        $headers = array(
            'Authorization: key=AIzaSyCbWEy5YGvdATCaQoPBCijd_fnSa0XF_K4',
            'Content-Type: application/json'
        );

// Open connection
        $ch = curl_init();

// Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
// Execute post
        $result = curl_exec($ch);
// Close connection
        curl_close($ch);
        return $result;
    }

}

