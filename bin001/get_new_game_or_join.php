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
