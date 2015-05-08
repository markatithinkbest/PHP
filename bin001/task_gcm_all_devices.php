<?php
// filename: task_gcm_all_devices.php
require ('task_gcm_all_devices_pre.php');
$db = new Database();
$db->connect();


mysql_connect("localhost", "laobanit_phoenix", "HFx}2U-e2tk,") or
        die("Could not connect: " . mysql_error());
mysql_select_db("laobanit_phoenix");

//$result = mysql_query("SELECT * FROM `v_gcm_list` LIMIT 10");
//select distinct `gcm_register`.`reg_id` AS `reg_id` from `gcm_register` order by `gcm_register`.`time_stamp` desc
$result = mysql_query("SELECT * FROM `v_gcm_list` LIMIT 999");


$myarray = array();
//$myarray[0] = "test data 1";
//$myarray[1] = "test data 2";
//$myarray[3] = "test data 3";
$myindex = 0;

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
//    printf("ID: %s  Name: %s", $row[0], $row[1]);  
//    echo "<br>".$row[0]; 
    $myarray[$myindex] = $row[0];
    $myindex++;
}




mysql_free_result($result);



// Message to be sent
$message = $_POST['message'];




// Set POST variables
$url = 'https://android.googleapis.com/gcm/send';

$fields = array(
    'registration_ids' => $myarray,
    'data' => array("message" => $message),
);

$headers = array(
    'Authorization: key=AIzaSyBzFWkzHIkqwnchyqMo-Si8PKnoJ5tOrXU',
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

