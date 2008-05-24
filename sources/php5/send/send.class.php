<?php
/**
* @package lulzBB-PHP5
* @category Send

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/exception.class.php');
require_once(SOURCE_PATH.'/database/database.class.php');
require_once(SOURCE_PATH.'/show/misc/informative-message.show.php');

/**
* Send base class.

* @author cHoBi
*/
abstract class Send {
    protected $data;
    protected $output;
    protected $connected;
    protected $magic;

    /**
    * Check connection and get the magic token.
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
    * Update the magic token.
    */
    public function __destruct() {
        $_SESSION[SESSION]['magic'] = md5(rand().rand().time());
    }

    /**
    * Used to send the data.
    * @access private
    */
    protected abstract function __send($data);

    /**
    * Returns the output.

    * @return    string    The output.
    */
    public function output() {
        return $this->output;
    }
}
?>
