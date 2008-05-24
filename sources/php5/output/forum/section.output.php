<?php
/**
* @package lulzBB-PHP5
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/output/output.class.php');
require_once(SOURCE_PATH.'/cache/forum/section.cache.php');
require_once(SOURCE_PATH.'/show/forum/section.show.php');

/**
* Section output class.

* @author cHoBi
*/
class Section extends Output {
    /**
    * Initialize the section and output the Show or the Cache.

    * @param    int    $section_id    The section id to get.
    */
    public function __construct($section_id) {
        parent::__construct();

        $cache = new SectionCache($section_id);
        if (!$cache->isCached()) {
            $section = new SectionShow($section_id);
            $cache->put($section->output());
        }

        $this->output = $cache->get();
    }
}
?>
