<?php
/**
* @package API
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

if (count($_GET) == 0 && count($_POST) == 0) {
    die();
}

if (!isset($User) or !$User->isIn('administrator')) {
    die("You don't have the permissions to change configurations.");
}

/**
* Adds a group of sections.

* @param    int       $parent    The section where to add the group.
* @param    int       $weight    The group's weight.
* @param    string    $name      The group's name.
*/
function add_group($parent, $weight, $name) {
    if (!isset($parent) or !isset($weight) or empty($name)) {
        die('Not enough parmeters.');
    }

    $Database->section->group->add($parent, $weight, $name);
    rm('/output/cache/sections/*');   
}

/**
* Adds a section.

* @param    int       $group_id    The group where to add the section.
* @param    int       $weight      The section's weight.
* @param    string    $title       The section's title.
* @param    string    $subtitle    The section's subtitle.
*/
function add_section($group_id, $weight, $title, $subtitle = '') {
    if (!isset($group_id) or !isset($weight) or empty($title)) {
        die('Not enough parameters.');
    }

    $Database->section->add($group_id, $weight, $title, $subtitle);
    rm('/output/cache/sections/*');
}

/**
* Adds a user to a group.

* @param    string    $user     The username.
* @param    string    $group    The group's name.
*/
function add_user_to_group($user, $group) {
    if (!isset($user) or !isset($group)) {
        die('Not enough parameters.');
    }

    if (is_numeric($user)) {
        $user = $Database->user->getName($user);
    }

    $oldSession = session_id();
    $newSession = $Database->user->getSession($user);

    if (isset($newSession)) {
        changeSession($newSession);
        $User->addTo($group);
        changeSession($oldSession);
    }
    else {
        stopSession();
    }
}

$command = $_REQUEST['command'];
switch ($command) {
    case 'add_group':
    $DATA['parent'] = $_REQUEST['parent'];
    $DATA['weight'] = $_REQUEST['weight'];
    $DATA['name']   = $_REQUEST['name'];

    add_group($DATA['parent'], $DATA['weight'], $DATA['name']);
    break;

    case 'add_section':
    $DATA['group_id'] = $_REQUEST['group'];
    $DATA['weight']   = $_REQUEST['weight'];
    $DATA['title']    = $_REQUEST['title'];
    $DATA['subtitle'] = $_REQUEST['subtitle'];

    add_section($DATA['group_id'], $DATA['weight'], $DATA['title'], $DATA['subtitle']);
    break;

    case 'add_user_to_group':
    $DATA['user']   = $_REQUEST['user'];
    $DATA['group']  = $_REQUEST['group'];

    add_user_to_group($DATA['user'], $DATA['group']);
    break;

    default:
    echo 'Command not found.';
    break;
}
?>
