#!/bin/bash



echo "Hello" > /home/ubuntu/hello.txt



run-one-until-success sudo apt-get update -y

run-one-until-success sudo apt-get install -y apache2 php-xml php php-mysql mysql-client curl zip unzip git php7.0-xml libapache2-mod-php php7.0-cli php-gd

export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 1.0.0-alpha11
curl -sS https://getcomposer.org/installer | php
php composer.phar require aws/aws-sdk-php



sudo systemctl enable apache2

sudo systemctl start apache2


git clone git@github.com:illinoistech-itm/vinayhemanthakumar.git

cd /
sudo mv vendor/ /var/www/html

sudo cp /vinayhemanthakumar/switchonarex.png /var/www/html

sudo cp /vinayhemanthakumar/s3test.php /var/www/html

sudo cp /vinayhemanthakumar/dbtest.php /var/www/html

sudo cp /vinayhemanthakumar/index.php /var/www/html

sudo cp /vinayhemanthakumar/welcome.php /var/www/html

sudo cp /vinayhemanthakumar/upload.php /var/www/html

sudo cp /vinayhemanthakumar/uploader.php /var/www/html 

sudo cp /vinayhemanthakumar/gallery.php /var/www/html

sudo cp /vinayhemanthakumar/admin.php /var/www/html

sudo cp /vinayhemanthakumar/edit.php /var/www/html

sudo cp /vinayhemanthakumar/backup.php /var/www/html

sudo cp /vinayhemanthakumar/restore.php /var/www/html

sudo cp /vinayhemanthakumar/IIT-logo.png /var/www/html
