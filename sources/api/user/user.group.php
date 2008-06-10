<?php
/**
* @package API
* @category User

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
?>
