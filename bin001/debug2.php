<?php

class GameUtil {

    function getInvlovedGames($bin_id) {
        require_once ('class/db.php');
        $db = new Database();
        $db->connect();
        $where_clause = "(p1_id=$bin_id OR p2_id=$bin_id) AND state_id<99";


        $db->select('game_header', 'game_id,p1_id,p1_set,p2_id,p2_set,state_id', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        foreach ($res as $a) {

            //      $sample_msg = array('gamexxx' => json_encode($a));
        }
        print_r($res);
        if (count($res) == 0) {
            $result = array('game_id' => 0);
        } else {
            $result = $res[0];
        }
        echo "<h1>---</h1>";
        print_r($result);
        return $result;
    }

}

$game = new GameUtil();
$bin_id = 29;
$feedback = $game->getInvlovedGames($bin_id);
echo "<h1>---</h1>";
echo json_encode($feedback);
