<?php
/**
* @package lulzBB

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

* @author cHoBi
*/

ini_set('error_reporting', 'E_CORE_ERROR');
ob_start();

if (isset($_GET['PHPSESSID']) or isset($_POST['PHPSESSID'])) {
    die("You can't set a php session id, sorry.");
}

/**
* The php version.
*/
define('VERSION', (float) phpversion());
define('SOURCES_VERSION', 'php'.(int) VERSION);

if ((int) VERSION == 4) {
    die("PHP 4 isn't supported yet");
}
if ((int) VERSION == 6) {
    die('LOLNO');
}

/**
* The root path of the forum.
*/
define('ROOT_PATH', dirname(__FILE__));

/**
* The web root path of the forum.
*/
define('WEB_PATH', dirname($_SERVER['PHP_SELF']));

/**
* The modules path.
*/
define('MODULES_PATH', ROOT_PATH.'/modules');

/**
* The interfaces path.
*/
define('INTERFACES_PATH', ROOT_PATH.'/interfaces');

/**
* The API source path.
*/
define('API_PATH', ROOT_PATH.'/sources/api');

/**
* The correct source path for the php version installed on the server.
*/
define('SOURCES_PATH', ROOT_PATH.'/sources/'.SOURCES_VERSION);

/**
* The path to the portable sources, like filesystem functions and such.
*/
define('MISC_PATH', ROOT_PATH.'/sources/misc');

/**
* The path to the contents.
*/
define('CONTENT_PATH', ROOT_PATH.'/content');

include('install/functions.php');
checkInstall();
// Misc sources.
include(MISC_PATH.'/session.php');
include(MISC_PATH.'/filesystem.php');
include(MISC_PATH.'/misc.php');

include(SOURCES_PATH.'/misc/config.class.php');
include(SOURCES_PATH.'/misc/filter.class.php');
include(SOURCES_PATH.'/misc/module.class.php');
include(SOURCES_PATH.'/misc/user.class.php');

/**
* Session initialization.
*/
if (!sessionFileExists()) {
    createSessionFile();
}
startSession();

/**
* Init the session if there's no session.
*/
if (!isset($_SESSION[SESSION]['config'])) {
    initSessionData();
}

/**
* This global var contains the Config object, so it's useful to get
* and set configurations :D

* @global    object    $Config
*/
$Config = $_SESSION[SESSION]['config'];

/**
* The template path.
*/
define('TEMPLATE_PATH', ROOT_PATH.'/templates/'.$Config->get('template'));

include(SOURCES_PATH.'/misc/exception.class.php');

/**
* This global var contains the Filter object, so you need it to filter
* input and outputs :3

* @global    object    $Filter
*/
$Filter = $_SESSION[SESSION]['filter'];

/**
* This global var contains the User object, obvious object is obviou.

* @global    object    $User
*/
$User = $_SESSION[SESSION]['user'];

include(SOURCES_PATH.'/database/database.php');
/**
* This global var cointains the Database object, and i think it's obvious
* why you need it...

* @global    object    $Database
*/
$Database = new Database;

/**
* This global var containst the count of sent queries for the page.

* @global    int    $queries
*/
$queries = 0;

if (isset($_REQUEST['session'])) {
    die;
}

/**
* Modules initialization.
*/
$modulesList = array();
$modulePaths = glob('modules/*');
foreach ($modulePaths as $modulePath) {
    if (is_dir($modulePath)) {
        $module = new Module($modulePath);
        $MODULE_NAME = $module->get('name');

        $M_ROOT_PATH       = $module->getPath();
        $M_SOURCES_PATH    = $M_ROOT_PATH.'/sources/'.SOURCES_VERSION;
        $M_INTERFACES_PATH = $M_ROOT_PATH.'/interfaces';

        if (is_file($M_ROOT_PATH.'/config/configuration.php')) {
            $Config->parseFile($M_ROOT_PATH.'/config/configuration.php', $MODULE_NAME);
        }

        $databasePath = "{$M_SOURCES_PATH}/database/database.php";
        if (is_file($databasePath)) {
            include($databasePath);
        }

        $priority = $module->get('priority');

        if (!isset($modulesList[$priority])) {
            $modulesList[$priority] = array();
        }

        array_push($modulesList[$priority], $module);
    }
}

/**
* Modules inclusion and execution.
*/
foreach ($modulesList as $modules) {
    foreach ($modules as $module) {
        $MODULE_NAME = $module->get('name');
        $MODULE_CALL = $module->get('call');

        $M_ROOT_PATH       = $module->getPath();
        $M_SOURCES_PATH    = $M_ROOT_PATH.'/sources/'.SOURCES_VERSION;
        $M_INTERFACES_PATH = $M_ROOT_PATH.'/interfaces';

        include($M_ROOT_PATH.'/index.php');

        // If a module outputted something, kill it with fire.
        if (ob_get_length() > 0) {
            ob_end_flush();
            die();
        }
    }
}

/**
* Reset the session in case of homepage viewing.
*/
initSessionData();

/**
* Core functions.
*/
if (isset($_GET['out'])) {
    if (isset($_GET['user'])) {
        include(INTERFACES_PATH.'/output/user.out.php');
    }
    else {
        include(INTERFACES_PATH.'/output/misc.out.php');
    }
}
else if (isset($_GET['in'])) {
    if (isset($_GET['user'])) {
        include(INTERFACES_PATH.'/input/user.in.php');
    }
}
else {
    if (!isset($_GET['page'])) {
        $_REQUEST['page'] = $_GET['page'] = $Config->get('homePage');
    }
    include(INTERFACES_PATH.'/output/home.out.php');
}

?>
