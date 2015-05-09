<?php
// filename: task_gcm_all_devices.php
require ('task_gcm_all_devices_pre.php');
require ('class/gcm.php');

$db = new Database();
$db->connect();
$db->select('bin001_id', 'reg_id', NULL, ' 1', ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
$res = $db->getResult();

foreach ($res as $a) {
    $arr[] = $a['reg_id'];
}
print_r($arr);

// Message to be sent
//$message = $_POST['message'];
$message = "ZZ馬克 testing GCM ";

// Set POST variables
//===GCM=== BIG GAME HERE
require ('class/gcm.php');

//$arr=[];
$p1_id=29;
$bin_id=30;
$game_id=97;
$gcm_arr[] = $p1_id;
$gcm_arr[] = $bin_id;

$gcm_msg = "$game_id IS SET FOR YOU TO SUBMIT YOUR NUMBER SET ";

print_r($gcm_arr);
print_r($gcm_msg);

$gcm = new GcmUtil;
$feedback = $util->sendMsg($gcm_arr, $gcm_msg);


?>



<html>
    <head>
        <title>BIN001</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1>DEBUG ...BIN001 APP server to GCM server result</h1>
        <h2> <?php echo $feedbackFromGCM; ?>   </h2>

    </body>
</html>
