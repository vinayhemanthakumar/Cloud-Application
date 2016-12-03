<html>
<head>
<body>
<form action = "" method = "post">
Image Upload
<select name="upload_select_status">
  <option value="blank"> </option>
  <option value="On">ON</option>
  <option value="Off">OFF</option>
</select>
<input type="submit" value="Submit" /></br>
</form>
</body>
<br>
<br>
<a href="gallery.php"> Gallery </a>
<br/><br/>
<a href="backup.php"> Backup DB </a>
<br/><br/>
<a href="index.php"> Logout </a>
</head>
</html>
<?php
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
//print_r($endpoint);
$link = mysqli_connect($endpoint,"kbryant","arizzo44","school",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$upload_status=$_POST["upload_select_status"];
if ( $upload_status == "On" )
{
echo "\n Upload Button On";
$sql_update_status="update uploadctrl set upload_btn=1 where id=1";
}
elseif ($upload_status == "blank" )
{
$sql_update_status="select upload_btn from uploadctrl where id=1";
}
elseif ($upload_status == "Off" )
{
echo "Upload Button Off";
$sql_update_status="update uploadctrl set upload_btn=0 where id=1";
}
if ($link->query($sql_update_status) === TRUE) {
//    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
?>
