<?php
/**
* @package lulzBB
* @license http://opensource.org/licenses/gpl-3.0.html
*
* @author cHoBi
*/

if (isset($_GET['PHPSESSID']) or isset($_POST['PHPSESSID'])) {
    die("You can't set a php session id, sorry.");
}
session_set_cookie_params(60*60*24*365);

/**
* The root path for the forum.
*/
define('ROOT_PATH', dirname(__FILE__));

/**
* The path to the portable sources, like filesystem functions and such.
*/
define('MISC_PATH', ROOT_PATH.'/sources/misc');
require_once(MISC_PATH.'/filesystem.php');

if (((int) phpversion()) == 4) {
    die("PHP 4 isn't supported yet");
}
if (((int) phpversion()) == 5) {
    $sourcePath = ROOT_PATH.'/sources/php5';
}
if (((int) phpversion()) == 6) {
    die('LOLNO');
}
/**
* The correct source path for the php version installed on the server.
*/
define('SOURCE_PATH', $sourcePath);

if (!is_file('.session.lol')) {
    $session = 'lulzBB-'.md5(rand().rand().time());

    $file = fopen('.session.lol', 'w');
    fwrite($file, $session);
    fclose($file);

    /**
    * This is just a trick to prevent other products to override
    * session data of the current lulzBB.
    */
    define('SESSION', $session);
}
else {
    $file = file('.session.lol');
    $session = $file[0];

    define('SESSION', $session);
}

require_once(SOURCE_PATH.'/config.class.php');
require_once(SOURCE_PATH.'/filter.class.php');
require_once(SOURCE_PATH.'/user.class.php');
require_once(SOURCE_PATH.'/database/database.class.php');
session_start();

$_SESSION[SESSION]['ROOT_PATH'] = ROOT_PATH;

/**
* This global var contains the Config object, so it's useful to get
* and set configurations :D

* @global    object    $Config
*/
$Config = $_SESSION[SESSION]['config'] = new Config();

/**
* This global var contains the Filter object, so you need it to filter
* input and outputs :3

* @global    object    $Filter
*/
$Filter = $_SESSION[SESSION]['filter'] = new Filter();

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
$User = @$_SESSION[SESSION]['user'];

/**
* This global var containst the count of sent queries for the page.

* @global    int    $queries
*/
$queries = 0;

$_SESSION[SESSION]['magic'] = md5(rand().rand().time());

$_GET['home'] = TRUE;
$_GET['page'] = (isset($_GET['page']) ? $_GET['page'] : 'home.php');
require(ROOT_PATH.'/output/index.php');
?>
