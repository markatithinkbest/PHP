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


$util = new GcmUtil;
$feedbackFromGCM=$util->sendMsg($arr,"I guess so!");

?>



<html>
    <head>
        <title>BIN001</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1>BIN001 APP server to GCM server result</h1>
        <h2> <?php echo $feedbackFromGCM; ?>   </h2>

    </body>
</html>
