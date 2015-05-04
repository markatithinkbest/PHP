<?php
// filename: task001.php
//  project: Bingo001, com.ithinkbest.bingo001
//
//  purpose: for Android to submit reg_id and return bin_id 
//
//   lasted: 2015-5-3, 11:01
//       by: Mark, 台中中工三路住處  
//  ---------------------------------------------
//     note:
//        1. 如果是新用戶，新建用戶
//        2. insert activity record 
//  ---------------------------------------------
?>
<?php
$known_sec_code="abc12345";

  //---取得變量值
  $bin_id=$_REQUEST['bin_id'];
 // $prj_id=$_REQUEST['prj_id'];
  $sec_code=$_REQUEST['sec_code'];
 
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
 
 //---獲得連到專案數據庫的 $con
  require_once('task000.php'); 

  $mysqli = new mysqli("localhost", $bin_user, $bin_pass, $bin_db);

  // check connection 
  if ($mysqli->connect_errno) {
    printf("no, 連線失敗: %s\n", $mysqli->connect_error);
    exit();
  }


  //SELECT * FROM `bin001_id` WHERE `bin_id`="XXX" and `prj_id`="yyy"
  $SQL=" SELECT * FROM `bin001_id` WHERE `_id`='$bin_id' ";
  //echo $SQL;

  $result = $mysqli->query($SQL);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $p2_id=$p1_id= $row["_id"];
      //= $p1_id;
      // echo "確認 playe_id=$p1_id, again $p2_id. ";

      //close result set 
      $result->close();      
    }  
  }else{
    echo "no, 並不是核定的ID";
    exit();
  }

// 優先加入已開的GAME to join any Open game first
 $SQL=" SELECT * FROM `game_header` WHERE state_id=1 ";
  //echo $SQL;
  $tmp2 = $mysqli->query($SQL);
  //echo "Open Game cnt = $tmp2->num_rows.";

  if ($tmp2->num_rows > 0){
    while($row = $tmp2->fetch_assoc()) {    
       $game_id= $row["game_id"];
       $waiting_id= $row["p1_id"];
   
       //echo "waiting player is " . $row["p1_id"];
       if ($waiting_id==$p1_id){ //2015－5－4
       //  echo " This game is open by you! YOU CANNOT JOIN YOUR OWN GAME! ";
         echo "no, you cannot join your open game! ";
       } else{
       //   echo " #######GOING TO JOIN ";

          //UPDATE `laobanit_bin001`.`game_header` SET `p2_id` = '9999',
          //`p2_dt` = '2015-05-03 15:40:' WHERE `game_header`.`game_id` =11;
          $SQL1=" UPDATE `laobanit_bin001`.`game_header` ";
          $SQL2=" SET `p2_id` = '$p2_id',`p2_dt` = CURRENT_TIMESTAMP, state_id=2 "; 
          $SQL3=" WHERE `game_header`.`game_id` =$game_id ";
          $SQL=$SQL1.$SQL2.$SQL3;
       //   echo $SQL;
          if ($cnt=$mysqli->query($SQL)) {
//            printf(" 加入成功，影響筆數%d\n", $cnt);   
            printf("ok, game_id =$game_id, p1_id=$waiting_id, p2_id=$p2_id,state_id=2 ");   
 
          }

       }
       
    }// end of while
  
    //close result set 
    $result->close();
    // close connection
    $mysqli->close();
    exit();
  }

//避免重複開 game
//  $SQL=" SELECT * FROM `game_header` WHERE `p1_id`='$p1_id' AND `p2_id`=0 ";
  $SQL=" SELECT * FROM `game_header` WHERE `p1_id`='$p1_id' AND state_id=1 ";

  //echo $SQL;
  $tmp1 = $mysqli->query($SQL);
  //echo "open cnt =".$tmp1->num_rows;

  if ($tmp1->num_rows > 0){
   // echo "This player open game cnt is ".$tmp1->num_rows;
    echo "no, you have open game cnt is ".$tmp1->num_rows;


 

    // ^^^ house keeping ^^^ 
    //close result set 
    $tmp1->close();
    // close connection
    $mysqli->close();
    // ^-^
    exit();
  }










//=== Open a new Game ===
  $SQL1=" INSERT INTO `game_header` (`game_id` ,`p1_id` ,`p1_dt`,state_id ) VALUES ";
  $SQL2=" (NULL, '$p1_id' ,CURRENT_TIMESTAMP,1)";

  $SQL=$SQL1.$SQL2;
  //  echo $SQL."<BR>";

  // 這是PHP獨特的寫法，拆開其實不好

  // 如果SQL執行有成功，回傳受影響的筆數，在這裡應該是1
  // 如果不成功，報錯
  if ($cnt=$mysqli->query($SQL)) {
  //  printf(" 影響筆數%d\n", $cnt);    

   

  //=== to provide game_id
  $SQL=" SELECT * FROM `game_header` WHERE `p1_id`='$p1_id' AND state_id =1 ";
    //echo $SQL;


    $result = $mysqli->query($SQL);

    if ($result->num_rows > 0) {
    // output data of each row
      while($row = $result->fetch_assoc()) {
        $game_id=$row["game_id"];
        $p1_id=$row["p1_id"];
        $p2_id=$row["p2_id"];
        $state_id=$row["state_id"];

    //    echo "ok, game_id= " . $row["game_id"];
    //    echo ", newly created";
          printf("ok, game_id =$game_id, p1_id=$p1_id, p2_id=$p2_id,state_id=$state_id ");   
 
      // echo "reg_id= " . $row["reg_id"];
      // echo "prj_id= " . $row["prj_id"];
       

      //close result set 
      $result->close();
      // close connection
      $mysqli->close();
      exit();
    }  
  }


  }else{    
    printf("Errormessage: %s\n", $mysqli->error); 
  }

  // close connection
  $mysqli->close();
?>



