<?php
/**
* @package PHP5
* @category Output

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
        $path = checkDir(ROOT_PATH."/.cache/misc/pages.section.{$section_id}.txt");

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
