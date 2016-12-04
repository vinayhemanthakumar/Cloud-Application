<html>
<head>
<h1>BACKUP PAGE</h1>
<meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
<style>
body {
 color: black;
 background-color: SkyBlue;
 font-family:"Comic Sans MS", cursive, sans-serif;
 }
</style>
<body>
<br>
<br>
<a href="welcome.php"> Home </a> | <a href="admin.php"> Back </a>
</body>
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
$res=exec('mysqldump --user=kbryant --password=arizzo44 --host=$endpoint vinaydb -P 3306  > /var/tmp/backup.sql'
);
if($res=='')
{
echo "<br>";
echo "<br>";
echo "<br>";
echo "Database backup is successfully saved at /var/tmp location";
}
else
{
}
?>
