<?php
/*
// filename: task1001.php
//  project: Bingo001, com.ithinkbest.bingo001
//
//  purpose: to cancel a game, good for debugging at this moment 
  paramters: game_id

     sample:

//  created: 2015-5-5, 14:18, 草屯StartBucks
//       by: Mark
//  ---------------------------------------------
//     note:
//        1. 如果是新用戶，新建用戶
//        2. insert activity record 
//  ---------------------------------------------
*/
?>
<?php
$known_sec_code="abc12345";

  //---取得變量值
  $game_id=$_REQUEST['game_id'];
  $sec_code=$_REQUEST['sec_code'];

  //---如果變量值不存在，退回 
  if ($bin_id==null){
    printf("...no bin_id");
    exit();
  }

  if ($sec_code==null){
    printf("...no sec_code");
    exit();
  }

  if (strcmp($sec_code,$known_sec_code)!=0){
    printf("...wrong sec_code");
    exit();
  }
 
  //---獲得連到專案數據庫的 $mysqli
  require_once('task000.php'); 

  $mysqli = new mysqli("localhost", $bin_user, $bin_pass, $bin_db);

  //---如果無法連到數據庫，顯示失敗 
  if ($mysqli->connect_errno) {
    printf("no, 連線失敗: %s\n", $mysqli->connect_error);
    exit();
  }

  //---如果要取消的game存在，則取消
  //---               不存在，退回 
  //$SQL=" SELECT * FROM `game_header` WHERE `game_id`='$game_id' ";
  $SQL="UPDATE  `laobanit_bin001`.`game_header` SET  `state_id` =  '1001',
        `state_dt` =  CURRENT_TIMESTAMP WHERE  `game_header`.`game_id` =$game_id;
  //echo $SQL;

  $result = $mysqli->query($SQL);
  if ($result->num_rows ==1) {    
    echo "ok, game_id =$game_id has been cancelled";
  }else{
    echo "no, failed to cancel game_id =$game_id";
  }


  //close result set 
  $result->close();
  // close connection
  $mysqli->close();
  ?>


