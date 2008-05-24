<?php
/**
* @package lulzBB-PHP5
* @category Show

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/exception.class.php');
require_once(SOURCE_PATH.'/show/misc/informative-message.show.php');

/**
* Show base class

* @author cHoBi
*/
abstract class Show {
    protected $id;
    protected $data;
    protected $output;
    protected $connected;
    protected $magic;

    /**
    * Initialize the connection and get the magic token.
    */
    public function __construct() {
        if (isset($_SESSION[SESSION]['user'])) {
            $this->connected = true;
        }
        else {
            $this->connected = false;
        }

        $this->magic = $_SESSION[SESSION]['magic'];
    }

    /**
    * Used to update the content being showed.
    * @access private
    */
    protected abstract function __update();

    /**
    * Returns the output.

    * @return    string    The output.
    */
    public function output() {
        return $this->output;
    }
}
?>
