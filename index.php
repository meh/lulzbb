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
define('SOURCES_PATH', ROOT_PATH.'/sources/.'SOURCES_VERSION));

/**
* The path to the portable sources, like filesystem functions and such.
*/
define('MISC_PATH', ROOT_PATH.'/sources/misc');

require('install/functions.php');
checkInstall();
// Misc sources.
require_once(MISC_PATH.'/session.php');
require_once(MISC_PATH.'/filesystem.php');
require_once(MISC_PATH.'/misc.php');

// Session creation.
require_once(SOURCES_PATH.'/misc/config.class.php');
require_once(SOURCES_PATH.'/misc/filter.class.php');
require_once(SOURCES_PATH.'/misc/user.class.php');

if (!sessionFileExists()) {
    createSessionFile();
}
startSession();

/**
* This global var contains the Config object, so it's useful to get
* and set configurations :D

* @global    object    $Config
*/
$Config = $_SESSION[SESSION]['config'];

/**
* This global var contains the Filter object, so you need it to filter
* input and outputs :3

* @global    object    $Filter
*/
$Filter = $_SESSION[SESSION]['filter'];


/**
* This global var cointains the Database object, and i think it's obvious
* why you need it...

* @global    object    $Database
*/
$Database;

/**
* This global var contains the User object, obvious object is obviou.

* @global    object    $User
*/
$User = $_SESSION[SESSION]['user'];

/**
* This global var containst the count of sent queries for the page.

* @global    int    $queries
*/
$queries = 0;

if (isset($_GET['api'])) {
    require_once(SOURCES_PATH.'/database/database.php');
    $Database = new Database;

    require(INTERFACES_PATH.'/api.php');
}

else {
    initSessionData();

    if (isset($_REQUEST['session'])) {
        die;
    }

    $modules = glob('modules/*');
    foreach ($modules as $module) {
        if (is_dir($module)) {
            define('MODULE_NAME', str_replace('modules/', '', $module));
            define('M_ROOT_PATH', ROOT_PATH."/{$module}");
            define('M_SOURCES_PATH', ROOT_PATH."/{$module}/sources/".SOURCES_VERSION);

            require(M_ROOT_PATH.'/index.php');
        }
    }

    if (!isset($_REQUEST['page'])) {
        $_REQUEST['page'] = $Config->get('homePage');
    }
    require(INTERFACES_PATH.'/output/home.out.php');
}

?>
