<?php

// filename: task_get_game_id.php
//http://www.ithinkbest.com/b253-stateid-definition/
//B253 stateId definition

/**
 * === preparation ===
 * -3 (default) waiting for another player to join this game
 * -2 two more to submit their number set
 * -1 one more to submit their number set
 * 0 ready to start game
 *
 * 
 */
//define("STATE_WAIT_FOR_JOIN",     -3);
//define("STATE_2_MORE_NUMBER_SET",     -2);
//define("STATE_1_MORE_NUMBER_SET",     -1);
//define("STATE_0_READY_TO_START",     0);

class GcmUtilV3 {

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
        $db->select('v_p1_reg_id', 'reg_id', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        foreach ($res as $a) {
            $arr[] = $a['reg_id'];
        }

        $db->select('game_header', 'game_id,p1_id,p1_set,p2_id,p2_set,state_id', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        foreach ($res as $a) {

      //      $sample_msg = array('gamexxx' => json_encode($a));
        }
        print_r($res);
        echo "<h1>---</h1>";
        print_r($res[0]);
          $sample_msg = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
        //echo json_encode($arr);
        //print_r($arr);

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



$gcm = new GcmUtilV3();
$game_id=113;
$feedback = $gcm->sendMsgToGamePlayers($game_id);
echo $feedback;