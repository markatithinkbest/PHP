<?php

// filename: get_game_id.php

require_once ('class/game.php');
$g001=new GameB001();
$game_id=123;
$res=$g001->getGame($game_id);
//print_r($res);

echo json_encode($res);

//echo json_encode(array('game_id'=>-999));
