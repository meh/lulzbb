<?php
/**
* @package lulzBB-PHP5
* @category Output

* @license http://opensource.org/licenses/gpl-3.0.html
*/

/**
* Output base class.
* @author cHoBi
*/
class Output {
    protected $output;
    protected $magic;
    
    /**
    * Initialize connection and the magic token.
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
    * Get the output.
    
    * @return    string    The output.
    */
    public function output() {
        return $this->output;
    }
}
?>
