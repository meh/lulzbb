<?php
/**
* @package PHP5
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/show/show.class.php');
require_once(SOURCE_PATH.'/show/misc/informative-message.show.php');

/**
* @todo Comments
* @author cHoBi
*/
class Login extends Show {
    public function __construct() {
        parent::__construct();
        
        $this->__update();
    }
    
    protected function __update() {
        $template = new Template('user/login.tpl');
        
        $this->output = $template->output();
    }
}
?>
