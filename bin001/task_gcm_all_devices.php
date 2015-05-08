<?php
// filename: task_gcm_all_devices.php
require ('task_gcm_all_devices_pre.php');
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

$url = 'https://android.googleapis.com/gcm/send';

$fields = array(
    'registration_ids' => $arr,
    'data' => array("message" => $message),
);
// ### NEED TO UPDATE API KEY HERE ###
$headers = array(
    'Authorization: key=AIzaSyCbWEy5YGvdATCaQoPBCijd_fnSa0XF_K4',
    'Content-Type: application/json'
);

// Open connection
$ch = curl_init();

// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
// Execute post
$result = curl_exec($ch);
// Close connection
curl_close($ch);
?>



<html>
    <head>
        <title>BIN001</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1>BIN001 APP server to GCM server result</h1>
        <h2> <?php echo $result; ?>   </h2>

    </body>
</html>
