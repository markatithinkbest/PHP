<?php

// filename: get_game_id.php

require_once ('class/game.php');

$known_sec_code = "Asdfg12345";

//---取得變量值
$player = $_REQUEST['player'];
//$prj_id = $_REQUEST['prj_id'];
$sec_code = $_REQUEST['sec_code'];

if ($sec_code == null || $player == null) {
    //printf("...no sec_code");
    $arr = array('game_id' => 0, 'desc' => 'no sec code, or no player');
    echo json_encode($arr);
    exit();
}

if (strcmp($sec_code, $known_sec_code) != 0) {
    //printf("...wrong sec_code");
    $arr = array('game_id' => 0, 'desc' => 'wrong sec code');
    echo json_encode($arr);
    exit();
}







$g001 = new GameB001();
$res = $g001->getUnfinishedGame($player);
echo json_encode($res);
