<?php
/**
* @package lulzBB-PHP5
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/show/show.class.php');
require_once(SOURCE_PATH.'/template/misc/error-message.template.php');

/**
* Shows an error message.

* @author cHoBi
*/
class ErrorMessage extends Show {
    private $message;
    private $type;

    /**
    * Initializes the message and the output.

    * @param    string    $message    The message that will be shown.
    */
    public function __construct($message) {
        parent::__construct();

        $this->message = $message;

        $this->__update();
    }

    /**
    * Create the template to be outputted with the message in it.
    * @access private
    */
    protected function __update() {
        $message = $this->message;

        $template = new ErrorMessageTemplate($message);

        $this->output = $template->output();
    }
}
?>
