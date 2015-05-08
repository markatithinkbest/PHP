

<?php
// filename: task_gcm_all_devices.php
require ('task_gcm_all_devices_pre.php');
$db = new Database();
$db->connect();
$db->select('bin001_id', 'reg_id', NULL, ' 1', ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
$res = $db->getResult();
//print_r($res);
//
//$arr = array();
foreach ($res as $a) {
  //  print_r($a);
    $arr[] = $a['reg_id'];
}
//echo "<br>&&&&&&&&&&&&&&&&&&<br>";
print_r($arr);

//exit();
//$res
//while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
//while ($row = mysql_fetch_array($res, MYSQL_NUM)) {
////    printf("ID: %s  Name: %s", $row[0], $row[1]);  
////    echo "<br>".$row[0]; 
//    $arr[$myindex] = $row[0];
//    $myindex++;
////}
//while ($row = mysqli_fetch_array($res)) {
//    print_r($row);
//
//    $arr[$myindex++] = $row[0];
//}
//print_r($arr);

//mysql_free_result($result);



// Message to be sent
//$message = $_POST['message'];
$message = "ZZ馬克 testing GCM ";



// Set POST variables
$url = 'https://android.googleapis.com/gcm/send';

$fields = array(
    'registration_ids' => $arr,
    'data' => array("message" => $message),
);
//                      AIzaSyATvnKoFAoOxP6lkiNmq2xhhH0hoZChg54   browser
//                      AIzaSyCbWEy5YGvdATCaQoPBCijd_fnSa0XF_K4
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

//echo $result;
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
