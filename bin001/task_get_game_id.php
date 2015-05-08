<?php
// filename: task_get_game_id.php

require ('task_get_game_id_pre.php');
$db = new Database();
$db->connect();

// list all open games
$db->select('game_header', 'game_id,p1_id,state_id', NULL, 'state_id<99', NULL);
if ($db->numRows() > 0) {
    // ### Some open games ###
    $where_clause = "(p1_id=$bin_id OR p2_id=$bin_id) AND state_id<99";
    $db->select('game_header', 'game_id,p1_id,p2_id,state_id', NULL, $where_clause, NULL);
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
            'state_id' => $res[0]['state_id'],
            'desc' => 'to continue unfinished game');
    } else {
        $game_id = $res[0]['game_id'];
        $p1_id = $res[0]['p1_id'];
        $set_clause = array('p2_id' => $bin_id, 'state_id' => 2);
        $where_clause = "game_id=$game_id";
        $db->update('game_header', $set_clause, $where_clause); // Table name, column names and respective values
        if ($db->numRows() > 0) {
            // === 1-1-1 existing open, without player invloved, open a new one      
            $arr = array('ans' => 'yes',
                'game_id' => $game_id,
                'p1_id' => $p1_id,
                'p2_id' => $bin_id,
                'state_id' => 2,
                'desc' => 'join an open game');
        } else {
            // === 1-1-2 unexcepted situation
            $arr = array('ans' => 'no', 'desc' => 'SOMETHING IS WRONG HERE, TO DEBUG!');
        }
    }
} else {
    // ### No open game ###
    // === 1-2 no open game, create a new one
    $db->insert('game_header', array('game_id' => NULL, 'p1_id' => $bin_id));
    $res = $db->getResult();
    $arr = array('ans' => 'yes', 'game_id' => $res[0], 'p1_id' => $bin_id, 'p1_id' => 0, 'state_id' => 1);
}
echo json_encode($arr);
