<?php
/**
* @package PHP5
* @category Cache

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/cache/cache.class.php');

/**
* Caching system for the navigator.

* @author cHoBi
*/
class NavigatorCache extends Cache {
    private $type;

    /**
    * Creates the cache for the navigator.
    * The creation of the id is pretty complex, the idea is to have a file formed by
    * type.parent-parent-id.html in that way you could remove parents and child
    * when something is changed like the title of the topic/section.
    * To get the cache file i just use the glob() and i made sure that the cache file
    * is unique.

    * @param    string    $type    The navigator type, section or topic.
    * @param    int       $id      The id of the section or topic.
    */
    public function __construct($type, $id) {
        $this->type = $type;

        $file = glob(ROOT_PATH."/.cache/navigator/{$type}.*-{$id}.html");
        if (!empty($file)) {
            $file = preg_replace('|.+?/(navigator.+)$|i', '${1}', $file[0]);
            parent::__construct($file);
        }
        else {
            parent::__construct('set_file');
        }
    }

    /**
    * Set the cache object to a file.
    * The file name is gotten through the elements id took from the navigator object.

    * @param    array    $elements    The $navigator->getElementsId() result.
    */
    public function setNavigator($elements) {
        $id = "{$this->type}.";
        foreach ($elements as $n => $element) {
            $id .= "{$element}-";
        }
        $id = rtrim($id, '-');

        $this->setCache("navigator/{$id}.html");
    }
}
?>
