<?php
/**
* @package PHP5
* @category Output

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
    * @param    int    $page          The page to show.
    */
    public function __construct($section_id, $page = 1) {
        parent::__construct();
        
        try {
            $section_id = (int) $section_id;
            $page       = ((int) $page < 1) ? 1 : (int) $page;

            $cache = new SectionCache($section_id, $page);
            if (!$cache->isCached()) {
                $section = new SectionShow($section_id, $page);
                $cache->put($section->output());
            }

            $this->output = $cache->get();
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }
    }
}
?>
