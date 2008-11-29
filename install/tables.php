<?php
/**
* @package  Install

* @license AGPLv3
* lulzBB is a CMS for the lulz but it's also serious business.
* Copyright (C) 2008 lulzGroup
*
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

if (isset($_GET['PHPSESSID']) or isset($_POST['PHPSESSID'])) {
    die("You can't set a php session id, sorry.");
}

define('VERSION', (float) phpversion());
define('SOURCES_VERSION', 'php'.(int) VERSION);

if ((int) VERSION == 4) {
    die("PHP 4 isn't supported yet");
}
if ((int) VERSION == 6) {
    die('LOLNO');
}

define('ROOT_PATH', realpath('../'));
define('WEB_PATH', dirname($_SERVER['PHP_SELF']));
define('MODULES_PATH', ROOT_PATH.'/modules');
define('INTERFACES_PATH', ROOT_PATH.'/interfaces');
define('API_PATH', ROOT_PATH.'/sources/api');
define('SOURCES_PATH', ROOT_PATH.'/sources/'.SOURCES_VERSION);
define('MISC_PATH', ROOT_PATH.'/sources/misc');

require_once(ROOT_PATH.'/install/functions.php');

// Misc sources.
require_once(MISC_PATH.'/session.php');
require_once(MISC_PATH.'/filesystem.php');

// Session creation.
require_once(SOURCES_PATH.'/misc/config.class.php');
require_once(SOURCES_PATH.'/misc/filter.class.php');

startSession('../');

$Config   = new Config;
$Filter   = new Filter;

require_once(SOURCES_PATH.'/database/database.php');
$Database = new Database;
$Database->executeSource('install/tables/mysql.sql');


if ($error = mysql_error()) {
    echo $error;
}
else {
    echo 'Done, now delete the install directory :)';
    installed();
}
?>
