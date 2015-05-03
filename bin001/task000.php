<?php
// filename: task000.php
//  project: Bingo001, com.ithinkbest.bingo001
//  purpose: provide $con for this project usage 
//
//   lasted: 2015-5-3, 10:52
//       by: Mark
//  ---------------------------------------------
//     note:
//        1. create new user and db
//  ---------------------------------------------

$bin_user="laobanit_bin001";
$bin_pass="xbg&KovK8F7?";
$bin_db=$bin_user;




  //---for testing only
  //var_dump(function_exists('mysqli_connect'));
  
  // *** FOR OTHER PROJECT, MODIFY HERE ***
  $con=mysqli_connect("localhost","laobanit_bin001","xbg&KovK8F7?","laobanit_bin001");


  //--- check connection
  if (mysqli_connect_errno()){
    die("...failed to connect to MySQL: " . mysqli_connect_error());
  }
  //echo "...con is ready!<br>";


  $MARKER="zzzzzzz";
  //echo "...MARKER IS $MARKER ";
?>