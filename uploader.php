<?php

session_start();
require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);?>
<html>
<body>
<h1>UPLOADER</h1>
</body>

<?php

// Retrieve the POSTED file information (location, name, etc, etc)

$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

#echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid and was successfully uploaded.\n";
    echo  "<br>";
} else {
    echo "Possible file upload attack!\n";
}


// Upload file to S3 bucket
$s3result = $s3->putObject([
    'ACL' => 'public-read',
     'Bucket' => 'raw-vin',
      'Key' =>  basename($_FILES['userfile']['name']),
      'SourceFile' => $uploadfile


// Retrieve URL of uploaded Object
]);
$url=$s3result['ObjectURL'];
echo "\n". "This is your URL: " . $url ."\n";

// INSERT SQL record of job information
$rdsclient = new Aws\Rds\RdsClient([
  'region'            => 'us-west-2',
    'version'           => 'latest'
]);


$rdsresult = $rdsclient->describeDBInstances([
    'DBInstanceIdentifier' => 'vinaydb'
]);


$endpoint = $rdsresult['DBInstances'][0]['Endpoint']['Address'];

$link = mysqli_connect($endpoint,"kbryant","arizzo44","school") or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}



// code to insert new record
/* Prepared statement, stage 1: prepare */
if (!($stmt = $link->prepare("INSERT INTO records(id, email, phone, s3rawurl, s3finishedurl, issubscribed, status, receipt) VALUES (NULL,?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
}
$email=$_SESSION['userid'];
$phone='1234567';
$s3rawurl=$url;
$s3finishedurl=' ';
$issubscribed=0;
$status=0;
$receipt=md5($url);
// prepared statements will not accept literals (pass by reference) in bind_params, you need to declare variables
$stmt->bind_param("ssssiis",$email,$phone,$s3rawurl,$s3finishedurl,$issubscribed,$status,$receipt);

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}


/* explicit close recommended */
$stmt->close();



$link->real_query("SELECT * FROM records");
$res = $link->use_result();

while ($row = $res->fetch_assoc()) {
//    echo " id = " . $row['id'] . "\n";
}


$link->close();




// PUT MD5 hash of raw URL to SQS QUEUE
$sqsclient = new Aws\Sqs\SqsClient([
    'region'  => 'us-west-2',
    'version' => 'latest'
]);

// Code to retrieve the Queue URLs
$sqsresult = $sqsclient->getQueueUrl([
    'QueueName' => 'vh_cubs', // REQUIRED
]);

echo "</br>";

$queueUrl = $sqsresult->get('QueueUrl');
echo "This is the SQS URL: $queueUrl";
echo "</br>";


$sqsresult = $sqsclient->sendMessage([
    'MessageBody' => $receipt, // REQUIRED
    'QueueUrl' => $queueUrl // REQUIRED
]);

echo "This is the Message Id:" . $sqsresult['MessageId'];

?>
<br>
<br>
<br>
<a href="upload.php?Back=1"> Back</a>
<html>
<style>
body {
 color: black;
 background-color: SkyBlue;
 font-family: "Comic Sans MS", cursive, sans-serif;
 }
</style>
