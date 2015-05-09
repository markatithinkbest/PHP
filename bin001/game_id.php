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
//        foreach ($res as $a) {
//
//            //      $sample_msg = array('gamexxx' => json_encode($a));
//        }
//        print_r($res);
//        echo "<h1>---</h1>";
//        print_r($res[0]);
//        $sample_msg = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
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

//sample usage
//$gcm = new GcmUtilV3();
//$game_id=113;
//$feedback = $gcm->sendMsgToGamePlayers($game_id);
//echo $feedback;
}

class GameUtil {

    function getUnfinishedGamesJson($bin_id) {
        require_once ('class/db.php');
        $db = new Database();
        $db->connect();
        $where_clause = "(p1_id=$bin_id OR p2_id=$bin_id) AND state_id<99";
        $db->select('game_header', 'game_id,p1_id,p1_set,p2_id,p2_set,state_id', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        if (count($res) == 0) {
            $result = array('game_id' => 0);
        } else {
            $result = $res[0];
        }
        
        return $result;

//SAMPLE USAGE
//$game = new GameUtil();
//$bin_id = 30;
//$feedback = $game->getUnfinishedGamesJson($bin_id);
//echo json_encode($feedback);
    }

    function joinOrOpenGameJson($bin_id) {
        require_once ('class/db.php');
        $db = new Database();
        $db->connect();
        $where_clause = "state_id=-3";
        $db->select('game_header', 'game_id,p1_id,p1_set,p2_id,p2_set,state_id', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        if (count($res) == 0) {// to open
            $result = array('game_id' => 0);
        } else { // to join
            $game_id = $res[0]['game_id']; // open game
            echo "game id ? ".$game_id;
            $timestamp = date('Y-m-d G:i:s');
//            $set_clause = array('p2_id' => $bin_id, 'state_id' => $state_join_game_2_more_number_set);
            $set_clause = array('p2_id' => "$bin_id", 'p2_dt' => "$timestamp", 'state_id' => "-2");
            $where_clause = "game_id=$game_id";
            $res2 = $db->update('game_header', $set_clause, $set_clause);
            echo '######'.$res2."<br>";
            // Table name, column names and values, WHERE conditions
            if ($res2 > 0) {
                $res2 = $db->insert('game_header', array('p1_id' => $bin_id));
                echo "what is res2 now? ";
                print_r($res2);
                
            } else {
                $result = array('game_id' => -1, 'desc' => 'err when join, sql=> ' . $db->getSql());
            }
        }
        return $result;

//SAMPLE USAGE
//$game = new GameUtil();
//$bin_id = 30;
//$feedback = $game->getUnfinishedGamesJson($bin_id);
//echo json_encode($feedback);
    }

}

require ('task_get_game_id_pre.php');

$game = new GameUtil();
$feedback = $game->getUnfinishedGamesJson($bin_id);
if ($feedback['game_id'] > 0) {
    echo json_encode($feedback);
    exit();
}

echo "...to open or join, join first <br>";

//SAMPLE USAGE
//$game = new GameUtil();
//$bin_id = 30;
$feedback2 = $game->joinOrOpenGameJson($bin_id);
echo json_encode($feedback2);

exit();




$state_newly_open_wait_for_join = -3;
$state_join_game_2_more_number_set = -2;
$state_1_more_number_set = -1;
$state_ready_to_start_game = 0;


$db = new Database();
$db->connect();

// list all open games
$db->select('game_header', 'game_id,p1_id,state_id', NULL, 'state_id<99', NULL);
if ($db->numRows() > 0) {
    // ### Some open games ###
    $where_clause = "(p1_id=$bin_id OR p2_id=$bin_id) AND state_id<99";
    $db->select('game_header', 'game_id,p1_id,p2_id,p1_set,p2_set,state_id', NULL, $where_clause, NULL);
    $sql = $db->getSql();
    //echo $sql; // to debug $where_clause is necessary

    $res = $db->getResult();
    //print_r($res); // to have a look on the result
    if ($db->numRows() > 0) {
        // === 1-1 existing open game, with player invloved, to continue
        $arr = array('ans' => 'yes',
            'game_id' => $res[0]['game_id'],
            'p1_id' => $res[0]['p1_id'],
            'p2_id' => $res[0]['p2_id'],
            'p1_set' => $res[0]['p1_set'],
            'p2_set' => $res[0]['p2_set'],
            'state_id' => $res[0]['state_id'],
            'desc' => 'to continue unfinished game');
    } else {
        $game_id = $res[0]['game_id'];
        $p1_id = $res[0]['p1_id'];
        $set_clause = array('p2_id' => $bin_id, 'state_id' => $state_join_game_2_more_number_set);
        $where_clause = "game_id=$game_id";
        $db->update('game_header', $set_clause, $where_clause); // Table name, column names and respective values
        if ($db->numRows() > 0) {
            // === 1-1-1 existing open, without player invloved, open a new one      
            //===GCM=== BIG GAME HERE

            $gcm = new GcmUtilV3();
            $feedback = $gcm->sendMsgToGamePlayers($game_id);
//echo $feedback;

            $arr = array('ans' => 'yes',
                'game_id' => $game_id,
                'p1_id' => $p1_id,
                'p2_id' => $bin_id,
                'p1_set' => "---",
                'p2_set' => "---",
                'state_id' => $state_join_game_2_more_number_set,
                'desc' => 'join an open game, ###GCM to inform p1 ' . $feedback);
        } else {
            // === 1-1-2 unexcepted situation
            $arr = array('ans' => 'no', 'desc' => '1-1-2 unexcepted situation, SOMETHING IS WRONG HERE, TO DEBUG!');
        }
    }
} else {
    // ### No open game ###
    // === 1-2 no open game, create a new one
    $db->insert('game_header', array('game_id' => NULL, 'p1_id' => $bin_id));
    if ($db->numRows() > 0) {
        $res = $db->getResult();
        $arr = array('ans' => 'yes',
            'game_id' => $res[0],
            'p1_id' => $bin_id,
            'p2_id' => 0,
            'state_id' => $state_newly_open_wait_for_join);
    } else {
        // === 1-2-2 unexcepted situation
        $arr = array('ans' => 'no', 'desc' => '1-2-2 unexcepted situation SOMETHING IS WRONG HERE, TO DEBUG!');
    }
}
echo json_encode($arr);
