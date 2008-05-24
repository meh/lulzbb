<?php
/**
* @package lulzBB-PHP5
* @category Database
*
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/exception.class.php');

/**
* This is just the base class for the database classes.
*
* @author cHoBi
*/
class DatabaseBase {
    protected $database;
    protected $Query;

    /**
    * Just to write less code and use OO programming :>
    *
    * @param    object    $database    The Database object.
    * @param    object    $query       The Query object.
    */
    public function __construct($database, $query) {
        $this->database = $database;
        $this->Query    = $query;
    }
}
?>
