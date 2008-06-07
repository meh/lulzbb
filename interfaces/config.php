<?php
/**
* @package Interfaces

* @license AGPLv3
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

if (!isset($Config)) {
    die("You can't access this directly.");
}

if (!isset($User) or !$User->isIn('administrator')) {
    die("You don't have the permissions to change configurations.");
}

$command = $_REQUEST['c'];
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
