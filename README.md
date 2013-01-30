oca(x)
====
web app for OCAB and similar

Yii version 1.1.13

Directory structure
|
- app
|
- protected
|
- framework	(get this from http://www.yiiframework.com/)


Installation

Create database with schema.sql
Modify database connection parameters in protected/config/main.php

File permissions
chown -R www-data protected/runtime/ protected/data/
chown www-data app/assets/

You need to copy app/images/pgridview to webroot/images
eg. # ln -s /home/ocax/app/images/pgridview/ /var/www/images/
!!need to fix this by using assets!!
