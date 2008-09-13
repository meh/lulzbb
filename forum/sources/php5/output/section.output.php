<?php
/**
* @package PHP5
* @category Show

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

require_once($M_SOURCES_PATH.'/output/show.class.php');
require_once($M_SOURCES_PATH.'/template/forum/section.template.php');

/**
* Section output class.

* @author cHoBi
*/
class Section extends Output
{
    private $section_id;
    private $page;

    /**
    * Initialize the showing class.

    * @param    int    $section_id    The section id.
    */
    public function __construct ($section_id, $page)
    {
        parent::__construct();
        global $Database;
        
        $this->section_id = (int) $section_id;

        if ($page == 'first') {
            $this->page = 1;
        }
        else if ($page == 'last') {
            $this->page = $this->Database->section->getPages($section_id);
        }
            
        $this->page = (int) $page;
        if ($this->page < 1) {
            $this->page = 1;
        }

        $this->__update();
    }

    /**
    * Get the data from the db and create the template.
    */
    protected function __update ()
    {
        global $Database;

        try {
            if ($Database['forum']->section->exists($this->section_id)) {
                $groups = $Database['forum']->section->getGroups($this->section_id);
                foreach ($groups as $n => $group) {
                    $groups[$n]['data'] = $Database['forum']->section->group->getSections($group['id']['RAW']);
                }
                
                $topics = $Database['forum']->section->getTopics($this->section_id, $this->page);

                if ($this->section_id == 0 && empty($groups) && empty($topics)) {
                    $message = new InformativeMessage('The section is empty.');
                    die($message->output());
                }
            }
            else {
                die("The section doesn't exist.");
            }
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }

        $template = new SectionTemplate(
            $this->section_id,
            $this->page,
            $groups,
            $topics
        );

        $this->output = $template->output();
    }
}
?>
