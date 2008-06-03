<?php
/**
* @package API
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

if (count($_REQUEST) <= 1) {
    die('No parameters eh? Are you trying to hax me? :(');
}

if (!isset($User) or !$User->isIn('administrator')) {
    die("You don't have the permissions to change configurations.");
}

$command = $_REQUEST['command'];
switch ($command) {
    case 'add_group':
    require_once(API_PATH.'/forum/section/group.php');

    $DATA['parent'] = $_REQUEST['parent'];
    $DATA['weight'] = $_REQUEST['weight'];
    $DATA['name']   = $_REQUEST['name'];

    add_group($DATA['parent'], $DATA['weight'], $DATA['name']);
    break;

    case 'add_section':
    require_once(API_PATH.'/forum/section.php');

    $DATA['group_id'] = $_REQUEST['group'];
    $DATA['weight']   = $_REQUEST['weight'];
    $DATA['title']    = $_REQUEST['title'];
    $DATA['subtitle'] = $_REQUEST['subtitle'];

    add_section($DATA['group_id'], $DATA['weight'], $DATA['title'], $DATA['subtitle']);
    break;

    case 'add_user_to_group':
    require_once(API_PATH.'/user/group.php');

    $DATA['user']   = $_REQUEST['user'];
    $DATA['group']  = $_REQUEST['group'];

    add_user_to_group($DATA['user'], $DATA['group']);
    break;

    default:
    echo 'Command not found.';
    break;
}
?>
