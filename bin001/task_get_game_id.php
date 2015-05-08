<?php

// filename: task_get_game_id.php
require ('task_get_game_id_pre.php');



//include('class/db.php');
$db = new Database();
$db->connect();

// open games
$db->select('game_header', 'game_id,p1_id,state_id', NULL, 'state_id<99', NULL);


if ($db->numRows() > 0) {
    // open games with player invloved
    $where_clause = "(p1_id=$bin_id OR p2_id=$bin_id) AND state_id<99";
    $db->select('game_header', 'game_id,p1_id,p2_id,state_id', NULL, $where_clause, NULL);
    $sql = $db->getSql();
    echo $sql; // to debug $where_clause is necessary

    $res = $db->getResult();
    print_r($res); // to have a look on the result
    if ($db->numRows() > 0) {
        $arr = array('ans' => 'yes',
            'game_id' => $res[0]['game_id'],
            'p1_id' => $res[0]['p1_id'],
            'p2_id' => $res[0]['p2_id'],
            'desc' => 'to continue unfinished game');
    } else {
        $game_id = $res[0]['game_id'];
        $p1_id = $res[0]['p1_id'];

        $set_clause = array('p2_id' => $bin_id, 'state_id' => 2);
        $where_clause = "game_id=$game_id";
        $db->update('game_header', $set_clause, $where_clause); // Table name, column names and respective values
        if ($db->numRows() > 0) {
            $arr = array('ans' => 'yes', 'todo' => 'to join an open game');
            $arr = array('ans' => 'yes',
                'game_id' => $game_id,
                'p1_id' => $p1_id,
                'p2_id' => $bin_id,
                'state_id' => 2,
                'desc' => 'join an open game');
        } else {
            $arr = array('ans' => 'no', 'todo' => 'SOMETHING IS WRONG HERE!');
        }
    }
} else {
    //  $arr = array('todo' => 'open a new  game');
    $db->insert('game_header', array('game_id' => NULL, 'p1_id' => $bin_id)); // Table name, column names and respective values
    $res = $db->getResult();
//echo json_encode($res);
//print_r($res);
    $arr = array('ans' => 'yes', 'game_id' => $res[0], 'p1_id' => $bin_id, 'p1_id' => 0, 'state_id' => 1);
}
echo json_encode($arr);
