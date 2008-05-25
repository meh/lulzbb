<?php
/**
* @package lulzBB-PHP5
* @category Database
*
* @license http://opensource.org/licenses/gpl-3.0.html
*/

/**
* @ignore
*
* @author cHoBi
*/
class Query {
    protected $dbPrefix;

    public function __construct() {
        global $Config;    
        $this->dbPrefix = $Config->get('dbPrefix');
    }
}
?>
