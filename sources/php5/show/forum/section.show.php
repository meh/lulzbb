<?php
/**
* @package lulzBB-PHP5
* @category Show

* @license http://opensource.org/licenses/gpl-3.0.html
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
            $groups,
            $topics
        );

        $this->output = $template->output();
    }
}
?>
