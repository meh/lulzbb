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

    public function getName($id) {
        $id = (int) $id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_users.name

        FROM
            {$this->dbPrefix}_users

        WHERE
            {$this->dbPrefix}_users.id = {$id}

QUERY;
    }
    
    public function exists($username) {
        global $Filter;
        $username = $Filter->SQL(trim($username));

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
        global $Filter;
        $email = $Filter->SQL($email);

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
        global $Filter;
        $username = $Filter->SQL($username);

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
