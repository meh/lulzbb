<?php
/**
* @package API
* @category User

* @license http://opensource.org/licenses/gpl-3.0.html

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
