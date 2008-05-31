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

if (!isset($User) or !$User->isIn('administrator')) {
    die("You don't have the permissions to change configurations.");
}

$command = @$_REQUEST['command'];
switch ($command) {
    case 'add_group':
    $DATA['parent'] = @$_REQUEST['parent'];
    $DATA['weight'] = @$_REQUEST['weight'];
    $DATA['name']   = @$_REQUEST['name'];

    if (!isset($DATA['parent']) or !isset($DATA['weight']) or empty($DATA['name'])) {
        die('Not enough parmeters.');
    }

    $Database->section->group->add($DATA['parent'], $DATA['weight'], $DATA['name']);
    rm('/output/cache/sections/*');
    break;

    case 'add_section':
    $DATA['group_id'] = @$_REQUEST['group'];
    $DATA['weight']   = @$_REQUEST['weight'];
    $DATA['title']    = @$_REQUEST['title'];
    $DATA['subtitle'] = @$_REQUEST['subtitle'];

    if (!isset($DATA['group_id']) or !isset($DATA['weight']) or empty($DATA['title'])) {
        die('Not enough parameters.');
    }

    $Database->section->add(
        $DATA['group_id'], $DATA['weight'],
        $DATA['title'], $DATA['subtitle']
    );
    rm('/output/cache/sections/*');
    break;

    case 'add_user_to_group':
    $DATA['user']   = @$_REQUEST['user'];
    $DATA['group']  = @$_REQUEST['group'];

    if (!isset($DATA['user']) or !isset($DATA['group'])) {
        die('Not enough parameters.');
    }

    if (is_numeric($DATA['user'])) {
        $DATA['user'] = $Database->user->getName($DATA['user']);
    }

    $oldSession = session_id();
    $newSession = $Database->user->getSession($DATA['user']);

    if (isset($newSession)) {
        changeSession($newSession);
        $User->addTo($DATA['group']);
        changeSession($oldSession);
    }
    else {
        stopSession();

    }
    break;

    default:
    echo "Command not found.";
    break;
}
?>
