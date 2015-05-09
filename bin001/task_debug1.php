<?php

class GcmUtilV2 {

    function sendMsgToGamePlayers($game_id, $msg) {
        require_once ('class/db.php');
        $db = new Database();
        $db->connect();
        $where_clause = "game_id=$game_id";
        
        // add p1 regId
        $db->select('v_p1_reg_id', 'reg_id', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        foreach ($res as $a) {
            $arr[] = $a['reg_id'];
        }
        
        // add p2 regId
        $db->select('v_p1_reg_id', 'reg_id', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        foreach ($res as $a) {
            $arr[] = $a['reg_id'];
        }
        //print_r($arr);

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
        echo $result;
        //  Close connection
        curl_close($ch);
        return $result;
    }
}

echo "yyyy";
//print_r($arr);
// Message to be sent
//$message = $_POST['message'];
$game_id = 97;
$msg = "task_debug1.php ZZ馬克 testing GCM ";
$gcm2 = new GcmUtilV2();
$feedbackFromGCM = $gcm2->sendMsgToGamePlayers(97, $msg);
?>



<html>
    <head>
        <title>BIN001</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1>BIN001 APP server to GCM server result</h1>
        <h2> <?php echo $feedbackFromGCM; ?>   </h2>

    </body>
</html>
