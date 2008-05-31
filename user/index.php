<?php
/**
* @package lulzBB
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

if (isset($_GET['PHPSESSID']) or isset($_POST['PHPSESSID'])) {
    die("You can't set a php session id, sorry.");
}

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

if (!isset($_SESSION[SESSION])) {
    die('The session died or something went wrong, refresh to the index please');
}

$Config   = $_SESSION[SESSION]['config'];
$Filter   = $_SESSION[SESSION]['filter'];
$Database = new Database;
$User     = @$_SESSION[SESSION]['user'];

if (isset($_GET['show'])) {
    if (isset($_GET['profile'])) {
        require_once(SOURCE_PATH.'/output/user/profile.output.php');

        $DATA['user_id'] = @$_REQUEST['id'];

        $template = new UserProfile($DATA['user_id']);
        echo $template->output();
    }
}

else if (isset($_GET['send'])) {

}
?>
