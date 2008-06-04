<?php
/**
* @package Misc
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
* Recursive mkdir.

* @param    string    $path    The path.
*/
function mkdir_recursive($pathname, $mode = 0755)
{
      is_dir(dirname($pathname))
    ||
      mkdir_recursive(dirname($pathname), $mode);

      return is_dir($pathname)
    ||
      mkdir($pathname, $mode);
}

/**
* (DEPRECATED) Checks if something is cached or not.

* @param    string    $type    The caching type. (section, topic, navigator)
* @param    int       $id      The cache's id.

* @return    bool    Guess what?

* @todo This is deprecated, let's find something else.
*/
function isCached($type, $id, $page) {
    $path = ROOT_PATH.'/.cache';

    if (is_file("{$path}/{$type}/{$id}-{$page}.html")) {
        return true;
    }
    else {
        return false;
    }
}
?>
