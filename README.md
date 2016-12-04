# vinayhemanthakumar
ITMO-544

Instruction to run scripts:

Step 1: Run the "install-app-env.sh" script with the two bucket names mentioned as positional parameters.

Step 2: Run the "install-env.sh" and give 6 positional parameters which are AMI ID, key-name, security-group, launch-configuration, count and IAM role name.

Step 3: Login credentials for index.php are all listed below: Admin is the "controller" and the users being "hajek@iit.edu" and "vhemanth@hawk.iit.edu"
	i.controller: username is controller and password is password 
	ii.Username: vhemanth@hawk.iit.edu and password is password
	iii.Username: hajek@iit.edu and password is password

Step 4. Welcome.php is the next page which is navigated from the login page based on the kind of user. If there is an admin login then an additional link for admin is provided.

Step 5. Gallery.php will show you the pictures based on the user from the S3 bucket.

Step 6. Upload.php will upload the selected image to the  and calls the uploader.php .

Step 7. Uploader.php will retrieve all the information from the database and will display the file.

Step 8. Admin.php has an feature for "on or off" which will turn off the upload feature. In the Admin.php, it also has a reference button to take the backup of database which will call backup.php and open a dialog box to save the .sql file

Step 9. In the edit.php it will process the image and place the watermark on the image that we already have it in s3 bucket.
