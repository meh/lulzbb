<?php
/**
* Package  lulzBB
* Author   cHoBi
* License  http://opensource.org/licenses/gpl-3.0.html
**/

$config = file('../config/configuration.php');
foreach ($config as $value) {
    if (preg_match('/^db(Username|Password|Host|Name|Prefix)/', $value)) {
        $config = split('=', chop($value));

        switch ($config[0]) {
            case 'dbUsername':
            $dbUsername = $config[1];
            break;

            case 'dbPassword':
            $dbPassword = $config[1];
            break;

            case 'dbHost':
            $dbHost = $config[1];
            break;

            case 'dbName':
            $dbName = $config[1];
            break;

            case 'dbPrefix':
            $dbPrefix = $config[1];
            break;
        }
    }
}

mysql_connect($dbHost, $dbUsername, $dbPassword);
mysql_select_db($dbName);

mysql_query('CREATE TABLE '.$dbPrefix.'_sections(
id INT UNSIGNED NOT NULL auto_increment,
parent INT UNSIGNED ,
type SMALLINT,
weight INT,
title TINYTEXT,
subtitle TINYTEXT,

count_topics INT UNSIGNED NOT NULL DEFAULT 0,
count_posts INT UNSIGNED NOT NULL DEFAULT 0,

last_topic_id INT UNSIGNED,
last_topic_title TINYTEXT,
last_post_id INT UNSIGNED,
last_post_time DATETIME,
last_user_id INT UNSIGNED,
last_user_name TINYTEXT,

primary key(id))');

mysql_query('CREATE TABLE '.$dbPrefix.'_topics(
id INT UNSIGNED NOT NULL auto_increment,
type smallINT UNSIGNED NOT NULL,
parent INT UNSIGNED NOT NULL,
user_id INT UNSIGNED NOT NULL,
title TINYTEXT NOT NULL,
subtitle TINYTEXT,

count_posts INT UNSIGNED NOT NULL DEFAULT 0,
count_views INT UNSIGNED NOT NULL DEFAULT 0,

last_post_id INT UNSIGNED NOT NULL,
last_post_time DATETIME NOT NULL,
last_user_id INT UNSIGNED NOT NULL,
last_user_name TINYTEXT NOT NULL,

primary key(id))');

mysql_query('CREATE TABLE '.$dbPrefix.'_topics_read(
topic INT UNSIGNED NOT NULL,
user INT UNSIGNED NOT NULL)');

mysql_query('CREATE TABLE '.$dbPrefix.'_posts(
topic_id INT UNSIGNED NOT NULL,
post_id INT UNSIGNED NOT NULL,
user_id INT UNSIGNED NOT NULL,
time DATETIME,
title TINYTEXT NOT NULL,
content longTEXT NOT NULL)');

mysql_query('CREATE TABLE '.$dbPrefix.'_users(
id INT UNSIGNED NOT NULL auto_increment,
session TINYTEXT,
name TINYTEXT NOT NULL,
password TINYTEXT NOT NULL,
email TINYTEXT NOT NULL,
avatar TEXT,

real_name TINYTEXT,
real_surname TINYTEXT,
sex TINYTEXT,
birth DATE,
location TINYTEXT,

biography TEXT,
hobby TEXT,
job TINYTEXT,
signature TEXT,

homepage TEXT,
msn TINYTEXT,
icq TINYTEXT,
yahoo TINYTEXT,

option_email BOOL NOT NULL,
registration_date DATETIME,

primary KEY(id))');

mysql_query('CREATE TABLE '.$dbPrefix.'_groups(
name VARCHAR(150) NOT NULL,
username VARCHAR(150) DEFAULT NULL,
description TEXT DEFAULT NULL,

UNIQUE KEY(name, username))');

mysql_query('INSERT INTO '.$dbPrefix.'_groups
       (name, description)
VALUES ("Unconfirmed", "LOLOL, THEY HAVE TO CONFIRM, FOR SRS")');

mysql_query('INSERT INTO '.$dbPrefix.'_sections
       (parent, type, weight, title, subtitle)
VALUES (0, 1, 1, "Sections", NULL)');

mysql_query('INSERT INTO '.$dbPrefix.'_sections
       (parent, type, weight, title, subtitle) 
VALUES (0, 0, 2, "Main section", "Main section subtitle")');

mysql_query('INSERT INTO '.$dbPrefix.'_sections
       (parent, type, weight, title, subtitle)
VALUES (0, 2, 3, NULL, NULL)');

if ($error = mysql_error()) {
    echo $error;
}
else {
    echo 'Done :D';
}
?>
