<?php
/**
* @package PHP5
*
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/show/misc/error-message.show.php');

/**
* Exception handling for Misc.
*
* @author cHoBi
*/
class lulzException extends Exception {
    /**
    * Creates an exception and initialize the message with the type.

    * @param    string    $type    The exception type.
    */
    public function __construct($type) {
        switch ($type) {
            case 'database_connection':
            $message  = 'The database connection failed :(<br/><br/>';
            $message .= 'Did you configure the board correctly?';
            $code = 31;
            break;
            
            case 'database_query':
            $message  = 'Something went wrong with a query, check the database integrity.';
            $code = 32;
            break;

            case 'section_not_existent':
            $message = "The section doesn't exist.";
            $code = 41;
            break;

            case 'topic_not_existent':
            $message = "The topic doesn't exist.";
            $code = 51;
            break;
        }
                    
        $message = new ErrorMessage($message);
        parent::__construct($message->output(), $code);
    }
}
?>
