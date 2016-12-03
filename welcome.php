<?php
session_start();
$username = $_SESSION['userid'];
echo "WELCOME PAGE";
echo "</br>";
echo "Your username is: " . $username . "\n";
require 'vendor/autoload.php';
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
echo "<br/>";
echo "<br/>";
$link = mysqli_connect($endpoint,"kbryant","arizzo44","school",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("mysql db Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$link->real_query("Select upload_btn from uploadctrl");
$result = $link->use_result();
echo "</br>";
echo "</br>";
while ($row = $result->fetch_assoc()) {
    $val = $row['upload_btn'];
    echo "</br>";
}
$link->close();
?>
<html>
        <head>
                <title>Welcome.php</title>
        </head>
        <body>
                <hr />
                 <?php
                 if($username == "controller")
                 { ?>
                 <a href="gallery.php"> Gallery </a>
                 <br/><br/>
                 <a href="upload.php"> Upload </a>
                 <br/><br/>
                <a href="admin.php"> Admin </a> <br/><br/>
                <a href="index.php?logout=1"> logout</a>
                <?php }
                elseif($val == "1") {
                ?>
                <a href="gallery.php"> Gallery </a>
                <br/><br/>
                <a href="upload.php"> Upload </a>
                <br/><br/>
                <a href="index.php?logout=1"> logout</a>
                <?php
                }
                else {
                ?>
                <a href="gallery.php"> Gallery </a>
                <br/><br/>
                <a href="index.php?logout=1"> logout</a>
                <?php }
                ?>
        </body>
</html>
