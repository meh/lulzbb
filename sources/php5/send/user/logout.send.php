<?php
/**
* @package PHP5
* @category Send

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/send/send.class.php');

/**
* Logout yay.

* @author cHoBi
*/
class Logout extends Send {
    /**
    * Logout.
    */
    public function __construct() {
        
        $this->output = $this->__send(0);
    }

    /**
    * Delete the user data from the session, so it's a logout :D
    * @access private
    */
    protected function __send($data) {
        $template = new InformativeMessage('logout_successful');
       
        destroySession();
        return $template->output();
    }
}
?>
