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

require_once(SOURCE_PATH.'/cache/cache.class.php');
require_once(SOURCE_PATH.'/database/database.class.php');
require_once(SOURCE_PATH.'/template/forum/section.template.php');

/**
* Topic cache class.

* @author cHoBi
*/
class TopicCache extends Cache
{
    private $parent;
    private $topic_id;
    private $page;

    /**
    * Create the file for the cache.

    * @param    int    $parent      The topic's parent.
    * @param    int    $topic_id    The topic id.
    */
    public function __construct ($parent, $topic_id)
    {
        $file = "topics/{$topic_id}-{$topic_page}.php";

        $this->parent   = $parent;
        $this->topic_id = $topic_id;

        $this->__setPage();

        parent::__construct($file);
    }

    /**
    * Update the views count of the parent section of the topic.
    */
    public function updateViews ()
    {
        global $Database;
        $Database->topic->increaseViewsCount($this->topic_id);

        $file = ROOT_PATH."/.cache/sections/{$this->parent}-{$this->page}";
        $text = file_get_contents($file);

        preg_match(
            "|(\d+)<span title='{$this->topic_id}' style='display: none;'/>|ims",
            $text,
            $views
        );
        $views = $views[1] + 1;
        $text = preg_replace(
            "|\d+<span title='{$this->topic_id}' style='display: none;'/>|ims",
            "{$views}<span title='{$this->topic_id}' style='display: none;'/>",
            $text
        );

        file_put_contents($file, $text);
    }

    /**
    * Updates the $page property.
    */
    private function __setPage ()
    {
        $path = checkDir(ROOT_PATH."/.cache/misc/page.topic.{$this->topic_id}.txt");

        if (is_file($path)) {
            $page       = file($path);
            $this->page = $page[0];
        }
        else {
            global $Database;

            try {
                $this->page = $Database->topic->getPage($this->topic_id, $this->parent);
            }
            catch (lulzException $e) {
                die($e->getMessage());
            }

            file_put_contents($path, "{$this->page}");
        }
    }
}
?>
