<?php
/**
* @package PHP5
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/template/template.class.php');

/**
* Creates the template for an error message.

* @author cHoBi
*/
class ErrorMessageTemplate extends Template {
    private $type;
    private $message;

    /**
    * Initialize the parent template and insert the message.

    * @param    string    $message    The message to insert in the template.
    */
    public function __construct($message) {
        parent::__construct('misc/error-message.tpl');

        $this->message = $message;

        $this->__parse();
    }

    /**
    * Add the message to the template.
    * @access private
    */
    private function __parse() {
        $text = $this->output();

        $text = preg_replace(
            '|<%ERROR-MESSAGE%>|i',
            $this->message,
            $text
        );

        $this->parsed = $text;
    }
}
?>
