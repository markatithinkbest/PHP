<?php

// filename: get_game_id.php

require_once ('class/game.php');

$known_sec_code = "Asdfg12345";

//---取得變量值
$player = $_REQUEST['player'];
$set = $_REQUEST['num_set'];
//$prj_id = $_REQUEST['prj_id'];
$sec_code = $_REQUEST['sec_code'];

if ($sec_code == null || $player == null || $set == null) {
    //printf("...no sec_code");
    $arr = array('game_id' => 0, 'desc' => 'no sec code, player, or set');
    echo json_encode($arr);
    exit();
}

if (strcmp($sec_code, $known_sec_code) != 0) {
    //printf("...wrong sec_code");
    $arr = array('game_id' => 0, 'desc' => 'wrong sec code');
    echo json_encode($arr);
    exit();
}

class B253GCM {

    function sendMsgToGamePlayers($game_id) {
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
        $db->select('v_p2_reg_id', 'reg_id', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        foreach ($res as $a) {
            $arr[] = $a['reg_id'];
        }

        $db->select('game_header', '*', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        foreach ($res as $a) {

      //      $sample_msg = array('gamexxx' => json_encode($a));
        }
        print_r($res);
        echo "<h1>---</h1>";
        print_r($res[0]);
        echo "<h1>---</h1>";
        $sample_msg = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
        //echo json_encode($arr);
        print_r($arr);
echo "<h1>---</h1>";
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $arr,
            'data' => $res[0]
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
        // echo $result;
        //  Close connection
        curl_close($ch);
        return $result;
    }

}

$g001 = new GameB001();
//echo "open game id is ";
$open_game_id = $g001->getOpenGameId();
//$res_join=$g001->joinOpenGame($player, $set);
if ($open_game_id == 0) {
    // No Open Game
    // Open New Game
    $res = $g001->openNewGame($player, $set);
} else {
    // There is an Open Game
    // Join 
    $res = $g001->joinOpenGame($player, $set, $open_game_id);

    //
    //
    
    $gcm = new B253GCM();
    $feedback = $gcm->sendMsgToGamePlayers($open_game_id);
    // need to keep this as log
    //echo $feedback;
}
echo json_encode($res);
