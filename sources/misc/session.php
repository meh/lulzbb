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
function sessionExists($relativePath = './') {
    if (is_file($relativePath.'.session.lol')) {
        return true;
    }
    else {
        return false;
    }
}

/**
* Create a session file.

* @return    string    Session name.
*/
function createSession() {
    $session = 'lulzBB-'.md5(rand().rand().time());

    $file = fopen('.session.lol', 'w');
    fwrite($file, $session);
    fclose($file);
    
    return $session;
}

/**
* Gets the session name.

* @return    string    Session name.
*/
function getSession($relativePath = './') {
    $file = file($relativePath.'.session.lol');
    $session = $file[0];
    
    return $session;
}
?>