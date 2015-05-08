<?php
// filename: task_get_game_id.php
require ('task_get_game_id_pre.php');



include('class/db.php');
$db = new Database();
$db->connect();
$db->select('game_header', 'game_id,p1_id,state_id', NULL, 'state_id<99', NULL);
//$res = $db->getResult();
//print_r($res);
//echo $db->getSql();

if ($db->numRows() > 0) {
    $arr = array('todo' => 'join an open game');
} else {
    $arr = array('todo' => 'open a new  game');
    $db->insert('game_header', array('game_id' => NULL, 'p1_id' => $bin_id)); // Table name, column names and respective values
    $res = $db->getResult();
//echo json_encode($res);
//print_r($res);
    $arr = array('game_id' => $res[0], 'p1_id' => $bin_id, 'state_id' => 1);
}
echo json_encode($arr);


?>