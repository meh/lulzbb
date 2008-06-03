<?php
/**
* @package PHP5
* @category Cache

* @license http://opensource.org/licenses/gpl-3.0.html
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
        $this->file  = $this->__checkDir(ROOT_PATH."/.cache/{$file}");
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
    * Checks if the dir exists and creates it if it doesn't.
    * @access private
    */
    private function __checkDir($path) {
        mkdir_recursive(dirname($path));
        
        return $path;
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
        $this->file = $this->__checkDir(ROOT_PATH."/.cache/{$file}");
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
    */
    public function get() {
        return $this->cache;
    }
}
?>
