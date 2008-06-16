<?php
/**
* @package  Misc

* @license AGPLv
* lulzBB is a CMS for the lulz but it's also serious business
* Copyright (C) 2008 lulzGroup

* This program is free software: you can redistribute it and/or modif
* it under the terms of the GNU Affero General Public License a
* published by the Free Software Foundation, either version 3 of th
* License, or (at your option) any later version

* This program is distributed in the hope that it will be useful
* but WITHOUT ANY WARRANTY; without even the implied warranty o
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See th
* GNU Affero General Public License for more details

* You should have received a copy of the GNU Affero General Public Licens
* along with this program.  If not, see <http://www.gnu.org/licenses/>.

* @author   cHoBi
*/

/**
* Says if the section exists.

* @param    string    $relativePath    

* @return    bool    True it exists, false it doesn't.
*/
function sessionFileExists($relativePath = './') {
    if (is_file($relativePath.'.session.lol')) {
        return true;
    }
    else {
        return false;
    }
}

/**
* Create a session file.

* @param    string    $relativePath    The relative path where to read the session constant.

* @return    string    Session name.
*/
function createSessionFile($relativePath = './') {
    $session = 'Misc-'.md5(rand().rand().time());

    $file = fopen('.session.lol', 'w');
    fwrite($file, $session);
    fclose($file);
    
    return $session;
}

/**
* Gets the session name.

* @param    string    $relativePath    The relative path where to read the session constant.

* @return    string    Session name.
*/
function getSessionConstant($relativePath = './') {
    $session = file_get_contents($relativePath.'.session.lol');
    return $session;
}

/**
* Sets cookie parameters.
*/
function setSessionCookieParams() {
    $year = 60*60*24*365;

    if (VERSION == 4) {
        session_set_cookie_params($year);
    }
    else if (VERSION >= 5.2) {
        session_set_cookie_params($year, '/', '', true, true);
    }
}

/**
*
*/
function deleteSessionCookie() {
    $year = 60*60*24*365;
    session_set_cookie_params(-$year);
}

/**
* Inits the session.

* @param    string    $relativePath    The relative path where to read the session constant.
*/
function startSession($relativePath = './') {
    define('SESSION', getSessionConstant($relativePath));

    if ($relativePath == './') {
    #    setSessionCookieParams();
    }

    session_start();
}

/**
* Changes session.

* @param    string    $id    The session id.
*/
function changeSession($id) {
    session_write_close();
    session_id($id);
    setSessionCookieParams();
    session_start();
}

/**
* Destroys the session.
*/
function destroySession() {
    $year = 60*60*24*365;

    deleteSessionCookie();
    session_unset();
    session_destroy();
    
    $_REQUEST['session'] = true;
    require_once(ROOT_PATH.'/index.php');
}

/**
* Initiates session data.
*/
function initSessionData() {
    global $Config;
    global $Filter;

    $_SESSION[SESSION]['magic'] = md5(rand().rand().time());

    $Config = $_SESSION[SESSION]['config'] = new Config;
    $Filter = $_SESSION[SESSION]['filter'] = new Filter;
}
?>
