strBingoShoeId﻿<?php
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
  $reg_id=$_REQUEST['reg_id'];
  $prj_id=$_REQUEST['prj_id'];
  $sec_code=$_REQUEST['sec_code'];
 
  if ($reg_id==null){
    printf("...no reg_id");
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
    printf("連線失敗: %s\n", $mysqli->connect_error);
    exit();
  }


  //SELECT * FROM `bin001_id` WHERE `reg_id`="XXX" and `prj_id`="yyy"
  $SQL=" SELECT * FROM `bin001_id` WHERE `reg_id`='$reg_id' AND `prj_id`='$prj_id' ";
  //echo $SQL;

  $result = $mysqli->query($SQL);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      echo "之前已注冊, ID= " . $row["_id"];

      //close result set 
      $result->close();
      // close connection
      $mysqli->close();
      exit();
    }  
  }

    echo "going to register to APP server";

    /* close result set */
    $result->close();
    




//  $SQL1=" INSERT INTO `gcm_register` (`_id` ,`reg_id` ,`prj_id` ,`time_stamp`) VALUES ";
  $SQL1=" INSERT INTO `bin001_id` (`_id` ,`reg_id` ,`prj_id` ,`time_stamp`) VALUES ";

  $SQL=$SQL1."(NULL, '$reg_id', '$prj_id',CURRENT_TIMESTAMP)";
  //  echo $SQL."<BR>";

  // 這是PHP獨特的寫法，拆開其實不好
  // 如果SQL執行有成功，回傳受影響的筆數，在這裡應該是1
  // 如果不成功，報錯
  if ($cnt=$mysqli->query($SQL)) {
    printf("影響筆數%d\n", $cnt);    

    $SQL=" SELECT * FROM `bin001_id` WHERE `reg_id`='$reg_id' AND `prj_id`='$prj_id' ";
    //echo $SQL;


    $result = $mysqli->query($SQL);

    if ($result->num_rows > 0) {
    // output data of each row
      while($row = $result->fetch_assoc()) {
        echo "注冊成功, ID= " . $row["_id"];
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



