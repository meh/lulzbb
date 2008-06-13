<?php
/**
* @package PHP5
* @category Cache

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
*/

/**
* This class is the base class to cache pages.

* @author cHoBi
*/
class Cache {
    protected $connected;
    protected $cached;
    protected $cache;
    protected $file;

    /**
    * The constructor initializes the filepath to the right dir and gets
    * the cache content, if the file is not cached it sets to false the
    * $cached property.

    * @param    string    $file    The relative path in the /output/cache/ dir
    */
    public function __construct($file) {
        $this->file  = checkDir(ROOT_PATH."/.cache/{$file}");
        $this->cache = file_get_contents($this->file);

        if ($this->cache) {
            $this->cached = true;
        }
        else if (!$this->cache || $file == 'set_file') {
            $this->cached = false;
        }

        if (isset($_SESSION[SESSION]['user'])) {
            $this->connected = true;
        }
        else {
            $this->connected = false;
        }
    }

    /**
    * Gets the $cached property.

    * @return    bool    True if it's cached, false if it's not.
    */
    public function isCached() {
        return $this->cached;
    }

    /**
    * Changes the path of the cached file.
    
    * @param    string    $file    The relative path.
    */
    public function setCache($file) {
        $this->file = checkDir(ROOT_PATH."/.cache/{$file}");
    }

    /**
    * Puts the string passed in the cached file and sets to true the $cached property.

    * @param    string    $content    The content to put in the cache.    
    */
    public function put($content) {
        file_put_contents($this->file, $content);
        $this->cache  = $content;
        $this->cached = true;
    }

    /**
    * Gets the cache.

    * @return    string
    */
    public function get() {
        return $this->cache;
    }
}
?>
