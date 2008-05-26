<?php
/**
* Package  lulzBB
* Author   cHoBi
* License  http://opensource.org/licenses/gpl-3.0.html
**/

if (((int) phpversion()) == 4) {
    die("PHP 4 isn't supported yet");
}
if (((int) phpversion()) == 5) {
    $SOURCE_PATH = '../sources/php5';
}
if (((int) phpversion()) == 6) {
    die('LOLNO');
}

define('SOURCE_PATH', realpath($SOURCE_PATH));
define('MISC_PATH', realpath('../sources/misc'));

require_once(MISC_PATH.'/filesystem.php');

// Get the session name.
$file    = file('../.session.lol');
$session = $file[0];
define('SESSION', $session);

require_once(SOURCE_PATH.'/config.class.php');
require_once(SOURCE_PATH.'/filter.class.php');
require_once(SOURCE_PATH.'/user.class.php');
require_once(SOURCE_PATH.'/database/database.class.php');
session_start();
define('ROOT_PATH', $_SESSION[SESSION]['ROOT_PATH']);

$Config   = $_SESSION[SESSION]['config'];
$Filter   = $_SESSION[SESSION]['filter'];
$Database = new Database();
$User     = @$_SESSION[SESSION]['user'];

if ($Database->exists()) {
    die('The installation has already been done.');
}

$dbPrefix = $Config->get('dbPrefix');

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_sections(
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

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_topics(
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

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_topics_read(
topic INT UNSIGNED NOT NULL,
user INT UNSIGNED NOT NULL)');

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_posts(
topic_id INT UNSIGNED NOT NULL,
post_id INT UNSIGNED NOT NULL,
user_id INT UNSIGNED NOT NULL,
time DATETIME,
title TINYTEXT NOT NULL,
content longTEXT NOT NULL)');

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_users(
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

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_groups(
name VARCHAR(150) NOT NULL,
username VARCHAR(150) DEFAULT NULL,
description TEXT DEFAULT NULL,

UNIQUE KEY(name, username))');

$Database->sendQuery('INSERT INTO '.$dbPrefix.'_groups
       (name, description)
VALUES ("Unconfirmed", "LOLOL, THEY HAVE TO CONFIRM, FOR SRS")');

$Database->sendQuery('INSERT INTO '.$dbPrefix.'_groups
       (name, description)
VALUES ("Administrator", "OH SHI-")');

$Database->sendQuery('INSERT INTO '.$dbPrefix.'_groups
       (name, description)
VALUES ("Moderator", "MODS = FAGS")');

$Database->sendQuery('INSERT INTO '.$dbPrefix.'_sections
       (parent, type, weight, title, subtitle)
VALUES (0, 1, 1, "Sections", NULL)');

$Database->sendQuery('INSERT INTO '.$dbPrefix.'_sections
       (parent, type, weight, title, subtitle) 
VALUES (0, 0, 2, "Main section", "Main section subtitle")');

$Database->sendQuery('INSERT INTO '.$dbPrefix.'_sections
       (parent, type, weight, title, subtitle)
VALUES (0, 2, 3, NULL, NULL)');

if ($error = mysql_error()) {
    echo $error;
}
else {
    echo 'Done :D';
}
?>
