<?php

// filename: get_new_game_or_join.php

require_once ('class/game.php');
$g001 = new GameB001();

//---取得變量值
$game_id = $_REQUEST['game_id'];
$num_set = $_REQUEST['num_set'];
$state_id = $_REQUEST['state_id'];
$sec_code = $_REQUEST['sec_code'];

if ($sec_code == null || $game_id == null || $num_set == null || $state_id == null) {
    $arr = array('game_id' => 0, 'desc' => 'no game id, num set, state id or sec code');
    echo json_encode($arr);
    exit();
}

if (strcmp($sec_code, $g001->sec_code) != 0) {
    $arr = array('game_id' => 0, 'desc' => 'wrong sec code');
    echo json_encode($arr);
    exit();
}

$g001->playP1Move($game_id, $num_set, $state_id);
$gcm = new B253GCM();
$feedback = $gcm->sendMsgToGamePlayers($game_id);

echo json_encode(array('game_id'=>$game_id,'gcm'=>$feedback));
//exit();

/*

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
*/