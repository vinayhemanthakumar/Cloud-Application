#!/bin/bash

run-one-until-success sudo apt-get update -y

run-one-until-success sudo apt-get install -y apache2 php php-xml php-mysql curl php-curl php-gd zip unzip git libapache2-mod-php php7.0-xml php7.0-cli

export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 1.0.0-alpha11

curl -sS https://getcomposer.org/installer | php

php composer.phar require aws/aws-sdk-php

sudo systemctl enable apache2

sudo systemctl start apache2

sudo mv /vendor /var/www/html

sudo git clone git@github.com:illinoistech-itm/vinayhemanthakumar.git

sudo mv /vinayhemanthakumar/edit.php /var/www/html

sudo mv /vinayhemanthakumar/IIT-logo.png /var/www/html

echo "Place in /var/spool/cron folder..."
(crontab -1 2>/dev/null; echo "*/3 * * * * /usr/bin/php /var/www/html/edit.php") | crontab -

sleep 30

sudo service apache2 restart

echo "Cron job executed successfully";
