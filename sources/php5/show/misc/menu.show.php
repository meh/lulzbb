<?php
/**
* @package PHP5
* @category Show

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/template/template.class.php');

/**
* Menu class.

* @todo Admin, moderation etc menus.
* @author cHoBi
*/
class Menu extends Show {
    /**
    * Create the menu.
    */
    public function __construct() {
        parent::__construct();
        $this->__update();
    }

    /**
    * Sets the right output if the user is connected or something else.

    * @access private
    */
    protected function __update() {
        if ($this->connected) {
            $output = $this->__user();
        }
        else {
            $output = $this->__guest();
        }
        
        $this->output = $output;
    }
    
    /**
    * The guest menu.
    * @access private
    */
    private function __guest() {
        $template = new Template('menu/menu.guest.tpl');
        return $template->output();
    }

    /**
    * The normal user menu.
    * @access private
    */
    private function __user() {
        $template = new Template('menu/menu.user.tpl');
        return $template->output();
    }
}
?>
