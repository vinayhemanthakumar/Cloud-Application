<html>
<head>
<h1>RESTORE PAGE</h1>
<meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
<style>
body {
 color: black;
 background-color: SkyBlue;
 font-family: "Comic Sans MS", cursive, sans-serif;
 }
</style>
<body>
<br>
<br>
<a href="welcome.php"> Home </a> | <a href="admin.php"> Back </a>
</body>
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
$restore_file  = "/var/tmp/backup.sql";
$username      = "kbryant";
$password      = "arizzo44";
$database_name = "vinaydb";

$cmd = "mysql -h {$endpoint} -u {$username} -p{$password} {$database_name} < $restore_file";
exec($cmd);

echo "<br>";
echo "<br>";
echo "<br>";

echo "Restored Successfully";

?>
