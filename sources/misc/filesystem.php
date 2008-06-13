<?php
/**
* @package Misc

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
* rm posix function.
*
* @param    string    $filename    The path to delete.
*/
function rm($filename) {
    $filename = preg_replace('|\.+/+|', '', ROOT_PATH.$filename);
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
* Checks if the dir exists and creates it if it doesn't.

* @param    string    The file to check the path.

* @return    string    The full path.
*/
function checkDir($path) {
    if (is_dir($path)) {
        mkdir_recursive($path);
    }
    else {
        mkdir_recursive(dirname($path));
    }

    return $path;
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
