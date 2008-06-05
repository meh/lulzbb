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
            $page = $this->__getPages($section_id);
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

    /**
    * Gets the pages number and caches it.

    * @todo THIS IS FUCKING UGLY, think about something better.
    * @access private
    */
    private function __getPages($section_id) {
        global $Database;
        $path = ROOT_PATH."/.cache/misc/pages.section.{$section_id}.txt";
        mkdir_recursive(dirname($path));

        if (is_file($path)) {
            $pages = file_get_contents($path);
        }
        else {
            $pages = $Database->section->getPages($section_id);
            file_put_contents($path, "{$pages}");
        }

        return $pages;
    }
}
?>
