<?php

sendGCM("Test Message","fBuQivJnRwqyBFY5vYN_Lt:APA91bF9Rmvh6PslZSSU_2ykp0xt31nZL2oDYFM-RwOFO13dz8iTAWc0zNZoEEToPLq7t-h0bScIcNNdiJ9UH_sGc4Q21A_l_9uF7G53WFYZIN5zK8XSo5ETOrRyJHnFeJDJ-Ibciu_M");

function sendGCM($message, $id) {


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'registration_ids' => array (
                    $id
            ),
            'data' => array (
                    "message" => $message
            )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AIzaSyDPR_wLACMQll-IdPGx_JIK1VukQxlCa2s",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    echo $result;
    curl_close ( $ch );
}

?>
