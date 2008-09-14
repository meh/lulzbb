<?php
/**
* @package Core-PHP5
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

require_once(SOURCES_PATH.'/cache/cache.class.php');
require_once(SOURCES_PATH.'/template/template.class.php');

/**
* Section cache class.

* @author cHoBi
*/
class SectionCache extends Cache
{
    private $section_id;

    /**
    * Initialize the cache and the file.

    * @param    int    $section_id    The section id.
    * @param    int    $page          The page to show.
    */
    public function __construct ($section_id, $page)
    {
        $this->section_id = $section_id;

        $file = "sections/{$section_id}-{$page}";
        parent::__construct($file);

        if ($this->isCached()) {
            $this->__newTopic();
        }
    }

    /**
    * Parse the new topic button.
    * @access private
    */
    private function __newTopic ()
    {
        $text = $this->cache;
        $text = preg_replace(
            '|<%NEW-TOPIC%>|i',
            $this->__newTopicTemplate(),
            $text
        );

        $this->cache = $text;
    }

    /**
    * Get the new topic template.

    * @return    string    The parsed template.
    */
    private function __newTopicTemplate ()
    {
        global $Config;

        if (($this->connected || $Config->get('anonymousPosting')) && !$this->__isLocked($this->section_id)) {
            $template = new SectionTemplate(0,0,0,0);
            $template = $template->getTemplatePart('new_topic');

            $template = preg_replace(
                '|<%POST-SECTION-ID%>|i',
                $this->section_id,
                $template
            );
        }
        else {
            $template = '';
        }

        return $template;
    }

    /**
    * Get if the section is locked or a container.

    * @param    int    $section_id    The section's id.

    * @return    bool    True if the section is writeable false if it's not.
    */
    private function __isLocked ($section_id)
    {
        $fileName = checkDir(ROOT_PATH."/.cache/misc/section.lock.{$section_id}.txt");
        if (is_file($fileName)) {
            $locked = file_get_contents($fileName);

            switch ($locked) {
                case 'true':
                $locked = true;
                break;

                case 'false':
                $locked = false;
                break;
            }
        }
        else {
            global $Database;
            $locked = $Database->section->isLocked($section_id);

            file_put_contents($fileName, ($locked) ? 'true' : 'false');
        }

        return $locked;
    }

    /**
    * Ovveride of the parent::put, sets the new topic in addition to the base method.

    * @param    string    $content    The content to put in the cache.
    */
    public function put ($content) {
        parent::put($content);
        $this->__newTopic();
    }
}
?>
