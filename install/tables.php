<?php
/**
* @package  Install

* @license AGPLv3
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.

* @author   cHoBi
**/

ini_set('error_reporting', 'E_CORE_ERROR');

define('VERSION', (float) phpversion());
if ((int) VERSION == 4) {
    die("PHP 4 isn't supported yet");
}
if ((int) VERSION == 6) {
    die('LOLNO');
}

// Paths
define('ROOT_PATH', realpath('../'));
define('SOURCE_PATH', ROOT_PATH.'/sources/php'.((int) VERSION));
define('MISC_PATH', ROOT_PATH.'/sources/misc');

// Misc sources.
require_once(MISC_PATH.'/session.php');
require_once(MISC_PATH.'/filesystem.php');

// Session creation.
require_once(SOURCE_PATH.'/config.class.php');
require_once(SOURCE_PATH.'/filter.class.php');
require_once(SOURCE_PATH.'/user.class.php');
require_once(SOURCE_PATH.'/database/database.class.php');
startSession('../');

$Config   = $_SESSION[SESSION]['config'];
$Filter   = $_SESSION[SESSION]['filter'];
$Database = new Database();
$User     = $_SESSION[SESSION]['user'];

if ($Database->exists()) {
    die('The installation has already been done.');
}

$dbPrefix = $Config->get('dbPrefix');

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_section_groups(
id INT UNSIGNED NOT NULL auto_increment,
parent INT UNSIGNED,
weight INT,
name TINYTEXT,

PRIMARY KEY(id))');

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_sections(
id INT UNSIGNED NOT NULL auto_increment,
group_id INT UNSIGNED NOT NULL,
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

PRIMARY KEY(id))');

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

PRIMARY KEY(id))');

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_topics_read(
topic INT UNSIGNED NOT NULL,
user INT UNSIGNED NOT NULL)');

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_topic_posts(
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

$Database->sendQuery('CREATE TABLE '.$dbPrefix.'_user_groups(
name VARCHAR(150) NOT NULL,
username VARCHAR(150) DEFAULT NULL,
description TEXT DEFAULT NULL,

UNIQUE KEY(name, username))');

// Index creation
$Database->sendQuery('CREATE INDEX topic_id ON '.$dbPrefix.'_topics(id)');
$Database->sendQuery('CREATE INDEX section_id ON '.$dbPrefix.'_sections(id)');
$Database->sendQuery('CREATE INDEX group_id ON '.$dbPrefix.'_section_groups(id)');

// User groups creations.
$Database->sendQuery('INSERT INTO '.$dbPrefix.'_user_groups
       (name, description)
VALUES ("Unconfirmed", "LOLOL, THEY HAVE TO CONFIRM, FOR SRS")');

$Database->sendQuery('INSERT INTO '.$dbPrefix.'_user_groups
       (name, description)
VALUES ("Administrator", "OH SHI-")');

$Database->sendQuery('INSERT INTO '.$dbPrefix.'_user_groups
       (name, description)
VALUES ("Moderator", "MODS = FAGS")');

// Sections creation.
$Database->sendQuery('INSERT INTO '.$dbPrefix.'_section_groups
       (parent, weight, name)
VALUES (0,      1,      "Sections")
');

$Database->sendQuery('INSERT INTO '.$dbPrefix.'_sections
       (group_id, weight, title,          subtitle)
VALUES (1,        1,      "Main section", "Main section subtitle.")');

if ($error = mysql_error()) {
    echo $error;
}
else {
    echo 'Done :D';
}
?>
