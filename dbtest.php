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
$create_table = 'CREATE TABLE IF NOT EXISTS Students
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    age INT(3) NOT NULL,
    PRIMARY KEY(id)
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
$sql = "delete FROM Students";

if ($link->query($sql) === TRUE) {
  // Do Nothing
} else {
    echo "Error while deleting: " . $sql . "<br>" . $link->error;
}
//Adding Records
$sql = "INSERT INTO Students (name, age)
VALUES ('kbryant', 24), ('jbaez', 23), ('arizzo', 27), ('jarrieta', 30), ('dross', 39)";

if ($link->query($sql) === TRUE) {
    echo "New records inserted successfully are:\n";
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}

//Displaying the records
$link->real_query("SELECT * FROM Students");
$res = $link->use_result();

   echo "<br/>";
   echo "<table>";
   echo "<tr>";
   echo "<th> ID </th>";
   echo "<th> Name </th>";
   echo "<th> Age </th>";
   echo "</tr>";

while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td>";
    echo $row['id'];
    echo "</td>";
    echo "<td>";
    echo $row['name'];
    echo "</td>";
    echo "<td>";
    echo $row['age'];
    echo "</td>";
    echo "</tr>";

}

$link->close();

?>
