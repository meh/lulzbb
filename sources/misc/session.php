<?php
/**
* @package  lulzBB
* @category Misc

* @license  http://opensource.org/licenses/gpl-3.0.html

* @author   cHoBi
**/

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
    $session = 'lulzBB-'.md5(rand().rand().time());

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
    $file = file($relativePath.'.session.lol');
    $session = $file[0];
    
    return $session;
}

/**
* Sets cookie parameters.
*/
function setCookieParams() {
    $year = 60*60*24*365;

    if (VERSION == 4) {
        session_set_cookie_params($year);
    }
    else if (VERSION >= 5.2) {
        session_set_cookie_params($year, '/', '', true, true);
    }
}

/**
* Inits the session.

* @param    string    $relativePath    The relative path where to read the session constant.
*/
function startSession($relativePath = './') {
    define('SESSION', getSessionConstant($relativePath));
    setCookieParams();
    session_start();
}

/**
* Changes session.

* @param    string    $id    The session id.
*/
function changeSession($id) {
    session_write_close();
    session_id($id);
    setCookieParams();
    session_start();
}
?>
