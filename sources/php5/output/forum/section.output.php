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
    public function __construct($section_id, $page = 'first') {
        parent::__construct();
        global $Database;

        $section_id = (int) $section_id;

        if ($page == 'first') {
            $page = 1;
        }
        else if ($page == 'last') {
            $page = $Database->section->getPages($section_id);
        }
        else {
            $page = (int) $page;

            if ($page < 1) {
                $page = 1;
            }
        }
        
        try {
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
