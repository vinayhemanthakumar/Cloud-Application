<?php
session_start();
require 'vendor/autoload.php';


// make sure you have php-gd installed and you may need to reload the webserver (apache2)

// get SQS queue name

$sqsclient = new Aws\Sqs\SqsClient([
    'region'  => 'us-west-2',
    'version' => 'latest'
]);

// Code to retrieve the Queue URLs
$sqsresult = $sqsclient->getQueueUrl([
    'QueueName' => 'vh_cubs', 
]);

echo "</br>";

$queueUrl = $sqsresult->get('QueueUrl');
echo "This is the SQS URL: $queueUrl";
echo "</br>";

// query to see if any messages
$result = $sqsclient->receiveMessage(array(
    'QueueUrl' => $queueUrl,
  ));

  //if so then retreive the body of the first queue message and assign it to a variable
  $receiptHandle = $result['Messages'][0]['ReceiptHandle'];

  $messageBody = $result['Messages'][0]['Body'];
  echo $messageBody;
  echo "</br>";


use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));
$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'vinaydb',
));
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print_r($endpoint);
echo "</br>";

$link = mysqli_connect($endpoint,"kbryant","arizzo44","school",3306) or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$link->real_query("SELECT * FROM records WHERE receipt = '$messageBody'");
$res = $link->use_result();

while ($row = $res->fetch_assoc()) {

$rawurl = $row['s3rawurl'];
}
echo $rawurl;


// load the "stamp" and photo to apply the water mark to
$stamp = imagecreatefrompng('/var/www/html/IIT-logo.png');  // grab this locally or from an S3 bucket probably easier from an S3 bucket...
$im = imagecreatefromjpeg($rawurl);  // replace this path with $rawurl

//Set the margins for the stamp and get the height and width of the stamp image
$marge_right=10;
$marge_bottom=10;
$sx = imagesx($stamp);
$sy = imagesy($stamp);
echo "</br>";
echo $sy . "\n";

//Copy the stamp image onto our photo using the margin offsets and the photo
// width to calculate positioning of the stamp
imagecopy($im,$stamp,imagesx($im) - $sx -$marge_right, imagesy($im) - $sy -$marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

//output and free memory
if ($im !== false){
//header('Content-type: image/png');
imagepng($im,'/tmp/rendered.png');
imagedestroy($im);
echo "Rendered Image kept in temp directory";
}
else{
echo "An error occured";
}

// place the rendred image into S3 finished-url bucket
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);


$resultimg = $s3->putObject(array(
    'Bucket' => 'raw-hem',
    'Key'    => $messageBody,
    'SourceFile' => '/tmp/rendered.png',
    'ACL' => 'public-read',
    'ContentType' => 'image.png'
));

$s3finurl= $resultimg['ObjectURL'];

echo "</br>";
echo $s3finurl;


$link->real_query("UPDATE records SET status=1,s3finishedurl='$s3finurl' where receipt='$messageBody'");

$res = $link->use_result;

echo "Updating Done";



//Delete the message from the SQS queue

$sqsclient->deleteMessage(array(

'QueueUrl' => $queueUrl,

'ReceiptHandle' => $receiptHandle,

));



//Sending message to customer

 $sns = new Aws\Sns\SnsClient([

          'version' => 'latest',

          'region'  => 'us-west-2'

       ]);



       $snsresult = $sns->listTopics([

       ]);



       $topicArn = $snsresult['Topics'][0]['TopicArn'];



       $sns->publish([

         'TopicArn' => $topicArn,

         'Message' => 'success'

       ]);

$link->close();
?>
