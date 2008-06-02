<?php
/**
* @package PHP5
* @category Output

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/output/output.class.php');
require_once(SOURCE_PATH.'/cache/forum/navigator.cache.php');
require_once(SOURCE_PATH.'/show/forum/navigator.show.php');

/**
* Navigator class.

* @author cHoBi
*/
class Navigator extends Output {
    /**
    * Creates the navigator and gets the cache if it exists.

    * @param    string    $type      The navigator type. section | topic
    * @param    int       $id        The section or topic id.
    * @param    int       $option    The possible option for the navigator.
    */
    public function __construct($type, $id, $option = '') {
        parent::__construct();

        $cache = new NavigatorCache($type, $id.$option);
        if (!$cache->isCached()) {
            $navigator = new NavigatorShow($type, $id, $option);
            $cache->setNavigator($navigator->getElementsId());
            $cache->put($navigator->output());
        }

        $this->output = $cache->get();
    }
}
?>
