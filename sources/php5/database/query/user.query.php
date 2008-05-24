<?php
/**
* @package lulzBB-PHP5
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class UserQuery extends Query {
    public function __construct() {
        parent::__construct();
    }
    
    public function exists($username) {
        global $filter;
        $username = $filter->SQL($username);

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_users.name
        
        FROM
            {$this->dbPrefix}_users
            
        WHERE
            {$this->dbPrefix}_users.name = "{$username}"

QUERY;
    }
    
    public function emailExists($email) {
        global $filter;
        $email = $filter->SQL($email);

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_users.email
            
        FROM
            {$this->dbPrefix}_users
            
        WHERE
            {$this->dbPrefix}_users.email = "{$email}"

QUERY;
    }
   
    public function getGroups($username) {
        global $filter;
        $username = $filter->SQL($username);

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_groups.name
            
        FROM
            {$this->dbPrefix}_groups

        WHERE
            {$this->dbPrefix}_groups.username = "{$username}"

QUERY;
    }
}
?>
