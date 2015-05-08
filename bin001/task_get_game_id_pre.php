<?php
// filename: task_get_game_id.php

$known_sec_code = "abc12345";

//---取得變量值
$bin_id = $_REQUEST['bin_id'];
$prj_id = $_REQUEST['prj_id'];
$sec_code = $_REQUEST['sec_code'];


//$arr = array('ans' => 'ok', 'game_id' => 12345, 'game_state' => 1, 'game_state_desc' =>'newly created');
//echo json_encode($arr);
$arr = array('ans' => 'no', 'err_desc' => '');

if ($bin_id == null) {
    $arr = array('ans' => 'no', 'err_desc' => 'no bin id');
    echo json_encode($arr);
    exit();
}

if ($sec_code == null) {
    //printf("...no sec_code");
    $arr = array('ans' => 'no', 'err_desc' => 'no sec code');
    echo json_encode($arr);

    exit();
}

if (strcmp($sec_code, $known_sec_code) != 0) {
    //printf("...wrong sec_code");
    $arr = array('ans' => 'no', 'err_desc' => 'wrong sec code');
    echo json_encode($arr);
    exit();
}



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