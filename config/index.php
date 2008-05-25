<?php
/**
* @package lulzBB
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

define('ROOT_PATH', realpath('../'));
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
define('SOURCE_PATH', realpath($sourcePath));

// Get the session name.
$file    = file('../.session.lol');
$session = $file[0];
define('SESSION', $session);

require_once(SOURCE_PATH.'/config.class.php');
require_once(SOURCE_PATH.'/filter.class.php');
require_once(SOURCE_PATH.'/user.class.php');
require_once(SOURCE_PATH.'/database/database.class.php');
session_start();

if (!isset($_SESSION[SESSION])) {
    die('The session died or something went wrong, refresh to the index please');
}

$Config   = $_SESSION[SESSION]['config'];
$Filter   = $_SESSION[SESSION]['filter'];
$Database = new Database;
$User     = @$_SESSION[SESSION]['user'];

/**
* @todo Check on the group etc.
*/

$DATA['command'] = @isset($_POST['command'])
                        ? $_POST['command']
                        : $_GET['command'];

switch ($_GET['command']) {
    case 'section_add': 
    try {
        $Database->section->add(
    }
    catch (lulzException $e) {

    }
    break;

    default:
    echo "Command not found."
    break;
}
?>
