<?php
echo "Hello World!\t";
require 'vendor/autoload.php';
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$output = $s3->listBuckets();
$outputdelete = $s3->deleteObject(array(
    // Bucket is required
    'Bucket' => 'raw-vin',
    // Key is required
    'Key' => 'switchonarex.png',
));
// Convert the result object to a PHP array
$array = $output->toArray();
$outputimage = $s3->putObject(array(
    'Bucket' => 'raw-vin',
    'Key'    => 'switchonarex.png',
    'SourceFile' => '/var/www/html/switchonarex.png',
    'ACL' => 'public-read',
    'Body'   => 'Hello!'
));
echo $outputimage['ObjectURL'] . "<br>";
?>
<html>
<body>
<img src="<?php echo $outputimage['ObjectURL'] ?>" width="600" height="600">
</body>
</html>
