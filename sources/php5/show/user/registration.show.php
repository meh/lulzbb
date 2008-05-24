<?php
/**
* @package lulzBB-PHP5
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/show/show.class.php');

/**
* @todo Comments
* @author cHoBi
*/
class Registration extends Show {
    public function __construct() {
        if ($this->connected) {
            die('LOLNO');
        }

        $this->__update();
    }
    
    protected function __update() {
        $template = new Template('user/registration.tpl');
        
        $this->output = $template->output();
    }
}
?>
