<?php
/**
* @package lulzBB
* @license http://opensource.org/licenses/gpl-3.0.html
*
* @author cHoBi
*/


/**
* rm posix function.
*
* @param    string    $filename    The path to delete.
*/
function rm($filename) {
    $filename = str_replace('../', '', ROOT_PATH.$filename);
    $files = glob($filename);

    if ($files) {
        foreach ($files as $file) {
            unlink($file);
        }
    }
}

/**
* Checks if something is cached or not.

* @param    string    $type    The caching type. (section, topic, navigator)
* @param    int       $id      The cache's id.

* @return    bool    Guess what?
*/
function isCached($type, $id) {
    $path = ROOT_PATH.'/output/cache';

    if (is_file("{$path}/{$type}/{$id}.html")) {
        return true;
    }
    else {
        return false;
    }
}
?>
