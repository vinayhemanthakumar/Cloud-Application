<?php
session_start();
$username=$_SESSION['userid'];
?>
<html>
<style>
body {
 color: navy;
 background-color: SkyBlue;
 font-family: Arial, Helvetica, sans-serif ;
 }
</style>
<head><title>WELCOME TO UPLOAD PAGE</title></head>
<body>
<h1>UPLOAD</h1>
<form enctype="multipart/form-data" action="uploader.php" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
<input type="file" name="userfile" />
<br>
<br>
<br>
<input type="submit" value="submit" />
<a href="welcome.php?Back=1">Back</a>
</form>
</body>
</html>
