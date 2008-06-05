<?php
/**
* @package PHP5
* @category Show

* @license AGPLv3
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

require_once(SOURCE_PATH.'/show/show.class.php');
require_once(SOURCE_PATH.'/template/forum/section.template.php');
require_once(SOURCE_PATH.'/database/database.class.php');

/**
* Section show class.

* @author cHoBi
*/
class SectionShow extends Show {
    private $page;

    /**
    * Initialize the showing class.

    * @param    int    $section_id    The section id.
    */
    public function __construct($section_id, $page) {
        parent::__construct();
        
        $this->id   = $section_id;
        $this->page = $page;
        $this->__update();
    }

    /**
    * Get the data from the db and create the template.
    * @access private
    */
    protected function __update() {
        global $Database;

        try {
            if ($Database->section->exists($this->id)) {
                $groups = $Database->section->getGroups($this->id);
                foreach ($groups as $n => $group) {
                    $groups[$n]['data'] = $Database->section->group->getSections($group['id']['RAW']);
                }
                
                $topics = $Database->section->getTopics($this->id, $this->page);

                if ($this->id == 0 && empty($groups) && empty($topics)) {
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
            $this->id,
            $this->page,
            $groups,
            $topics
        );

        $this->output = $template->output();
    }
}
?>
