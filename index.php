<?php
/**
* @package lulzBB
* @license http://opensource.org/licenses/gpl-3.0.html

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
define('SOURCE_PATH', ROOT_PATH.'/sources/php'.((int) VERSION));

/**
* The path to the portable sources, like filesystem functions and such.
*/
define('MISC_PATH', ROOT_PATH.'/sources/misc');

// Misc sources.
require_once(MISC_PATH.'/session.php');
require_once(MISC_PATH.'/filesystem.php');

// Session creation.
require_once(SOURCE_PATH.'/config.class.php');
require_once(SOURCE_PATH.'/filter.class.php');
require_once(SOURCE_PATH.'/user.class.php');
require_once(SOURCE_PATH.'/database/database.class.php');

if (!sessionFileExists()) {
    createSessionFile();
}
startSession();

if (count($_REQUEST) == 1) {
    $_REQUEST['home'] = true;
    $_SESSION[SESSION]['magic'] = md5(rand().rand().time());
    
    $_SESSION[SESSION]['config'] = new Config;
    $_SESSION[SESSION]['filter'] = new Filter;
}

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
$Database = new Database;

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

if (!isset($_REQUEST['session'])) {
    if (isset($_REQUEST['home'])) {
        $_REQUEST['page'] = 'home.php';
        require(INTERFACES_PATH.'/output.php');
    }

    else if (isset($_REQUEST['output'])) {
        unset($_REQUEST['output']);
        require(INTERFACES_PATH.'/output.php');
    }

    else if (isset($_REQUEST['input'])) {
        unset($_REQUEST['input']);
        require(INTERFACES_PATH.'/input.php');
    }

    else if (isset($_REQUEST['user'])) {
        unset($_REQUEST['user']);
        require(INTERFACES_PATH.'/user.php');
    }

    else if (isset($_REQUEST['config'])) {
        unset($_REQUEST['config']);
        require(INTERFACES_PATH.'/config.php');
    }
}
?>
