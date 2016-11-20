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
$create_table = 'CREATE TABLE IF NOT EXISTS Login
(
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY(username)
)';
$create_tbl = $link->query($create_table);
if ($create_table) {
        echo "<b>Table is created successfully</b>";
        echo "</br>";
}
else {
        echo "error!!";
}
//Deleting Records
$sql = "delete FROM Login";
if ($link->query($sql) === TRUE) {
  // Do Nothing
} else {
    echo "Error while deleting: " . $sql . "<br>" . $link->error;
}
//Adding Records
$sql = "INSERT INTO Login (username, password)
VALUES ('vhemanth@hawk.iit.edu','password')";
if ($link->query($sql) === TRUE) {
    echo "New record is inserted successfully:\n";
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
$username = mysqli_real_escape_string($link,$_POST['username']);
$password = mysqli_real_escape_string($link,$_POST['password']);
$query = "SELECT * FROM Login WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($link,$query)or die(mysqli_error());
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
$active = $row['active'];
$num_row = mysqli_num_rows($result);
if( $num_row == 1 )
   {
        echo "logged in";
        $_SESSION['userid']=$username;
        header("location: welcome.php");
   }
else
   {
    echo 'Invalid username or password';
   }

}
$link->close();
?>
<form id='login' action='index.php' method='post' accept-charset='UTF-8'>
<fieldset >
<h1>Login Page</h1>
<input type='hidden' name='submitted' id='submitted' value='1'/>
<label for='username' >Enter UserName:</label>
<input type='text' name='username' id='username'  maxlength="50" />
<br>
<br>
<label for='password' >Enter Password:</label>
<input type='password' name='password' id='password' maxlength="50" />
<br>
<br>
<input type="submit" name='submit' value='Submit'>
</fieldset>
</form>
