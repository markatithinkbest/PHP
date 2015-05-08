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
$db->select('game_header','game_id,p1_id,state_id',NULL,'state_id<99',NULL); 
//$res = $db->getResult();
//print_r($res);
//echo $db->getSql();

if ($db->numRows()>0){
 $arr = array('todo'=>'join an open game');
}else{
 $arr = array('todo'=>'open a new  game');
 $db->insert('game_header',array('game_id'=>NULL,'p1_id'=>$bin_id)); // Table name, column names and respective values
  $res = $db->getResult();
//echo json_encode($res);
//print_r($res);
$arr = array('game_id'=>$res[0],'p1_id'=>$bin_id,'state_id'=>1);

}
echo json_encode($arr);
//echo $db->numRows();
exit();

// if any existing open game created by this user
$SQL = " SELECT * FROM `game_header` WHERE `state_id`=1 AND p1_id='$bin_id'";

$chk1 = $mysqli->query($SQL);
if ($chk1->num_rows > 0) {
    // output data of each row
    while ($row = $chk1->fetch_assoc()) {

        $arr = array('ans' => 'yes',
            'game_id' => $row["game_id"],
            'p1_id' => $row["p1_id"],
            'p2_id' => $row["p2_id"],
            'state_id' => $row["state_id"],
            'detail' => 'opened by this player'
        );
        echo json_encode($arr);

        $chk1->close();
        $mysqli->close();
        exit();
    }
} else {

    $arr = array('ans' => 'no',
        'debug' => 'no any open game of this player');
    //echo json_encode($arr);
    $chk1->close();
}




// if any existing open game 
$SQL = " SELECT * FROM `game_header` WHERE `state_id`=1 ";

$chk1 = $mysqli->query($SQL);
if ($chk1->num_rows > 0) {
    // output data of each row
    while ($row = $chk1->fetch_assoc()) {

        $game_id = $row["game_id"];
        $p1_id = $row["p1_id"];

//      $arr = array('ans' => 'yes', 
//                'game_id' => $row["game_id"], 
//                'p1_id' => $row["p1_id"], 
//                'p2_id' => $row["p2_id"], 
//                'state_id' => $row["state_id"],
//                'detail' => 'to join this game'
//
//                   );
//      echo json_encode($arr); 
        $chk1->close();
    }
} else {

    $arr = array('ans' => 'no',
        'debug' => 'GOING TO OPEN A NEW GAME...');

    echo json_encode($arr);
    $chk1->close();
    $mysqli->close();
    exit();
}

// now , join this game
$SQL1 = "UPDATE  `laobanit_bin001`.`game_header` ";
$SQL2 = " SET  `p2_id` =  '$bin_id', `p2_dt` =  CURRENT_TIMESTAMP, `state_id` =  '2'";
$SQL3 = " WHERE  `game_header`.`game_id` ='$game_id';";
$SQL = $SQL1 . $SQL2 . $SQL3;

$chk1 = $mysqli->query($SQL);
if ($chk1->num_rows > 0) {


    $arr = array('ans' => 'yes',
        'game_id' => $game_id,
        'p1_id' => $p1_id,
        'p2_id' => $bin_id,
        'state_id' => 2,
        'debug' => 'in good shape'
    );
    echo json_encode($arr);
} else {
    $arr = array('ans' => 'no',
        'game_id' => $game_id,
        'p1_id' => $p1_id,
        'p2_id' => $bin_id,
        'state_id' => 2,
        'debug' => 'SOMETHING IS WRONG HERE  ===> ' . $SQL
    );
    echo json_encode($arr);
}




// close result set
$result->close();

// close connection
$mysqli->close();
?>