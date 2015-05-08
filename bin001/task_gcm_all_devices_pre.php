<?php
// filename: task_task_gcm_all_devices_pre.php

$known_sec_code = "Asdfg12345";

//---取得變量值
//$bin_id = $_REQUEST['bin_id'];
//$prj_id = $_REQUEST['prj_id'];
$sec_code = $_REQUEST['sec_code'];


//$arr = array('ans' => 'ok', 'game_id' => 12345, 'game_state' => 1, 'game_state_desc' =>'newly created');
//echo json_encode($arr);
//$arr = array('ans' => 'no', 'err_desc' => '');
//
//if ($bin_id == null) {
//    $arr = array('ans' => 'no', 'err_desc' => 'no bin id');
//    echo json_encode($arr);
//    exit();
//}

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

// $arr = array('ans' => 'yes', 'desc' => 'sec code checked!');
// echo json_encode($arr);

include('class/db.php');
