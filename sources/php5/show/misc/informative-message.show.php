<?php
/**
* @package lulzBB-PHP5
* @category Show

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/show/show.class.php');
require_once(SOURCE_PATH.'/template/misc/informative-message.template.php');

/**
* Informative message class to show a message to the user.

* @author cHoBi
*/
class InformativeMessage extends Show {
    private $message;
    private $type;

    /**
    * Initialize the message to send.

    * @param    string    $message    The message to show.
    * @param    array     $data       The data used by the message.
    */
    public function __construct($message, $data = array()) {
        parent::__construct();
    
        $this->type = $message;
        $this->data = $data;

        switch ($message) {
            case 'login_successful':
            $template = new Template('informative-messages/login_successful.tpl');

            $this->message = $template->output();
            break;
            
            case 'login_unsuccessful':
            $template = new Template('informative-messages/login_unsuccessful.tpl');

            $this->message = $template->output();
            break;

            case 'logout_successful':
            $template = new Template('informative-messages/logout_successful.tpl');

            $this->message = $template->output();
            break;

            case 'registration_successful':
            $template = new Template('informative-messages/registration_successful.tpl');

            $this->message = $template->output();
            break;

            case 'topic_sent':
            $template = new Template('informative-messages/topic_sent.tpl');

            $this->message = $template->output();
            break;

            case 'post_sent':
            $template = new Template('informative-messages/post_sent.tpl');

            $this->message = $template->output();
            break;

            default:
            $this->message = $message;
            break;
        }

        $this->__update();
    }

    /**
    * Initialize data and type, create the template and put it in the output.
    * @access private
    */
    protected function __update() {
        $message = $this->message;

        $type    = isset($this->type) ? $this->type : '';
        $data    = isset($this->data) ? $this->data : array();

        $template = new InformativeMessageTemplate($type, $data, $message);

        $this->output = $template->output();
    }
}
?>
