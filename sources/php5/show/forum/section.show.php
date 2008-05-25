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
    private $Database;

    /**
    * Initialize the showing class.

    * @param    int    $section_id    The section id.
    */
    public function __construct($section_id) {
        parent::__construct();
        
        $this->id = $section_id;
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
                $sections = $Database->section->getSections($this->id);
                $topics   = $Database->section->getTopics($this->id);
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
            $sections,
            $topics
        );

        $this->output = $template->output();
    }
}
?>
