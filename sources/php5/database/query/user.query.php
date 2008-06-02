<?php
/**
* @package PHP5
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

    public function getSessionFromId($user_id) {
        $user_id = (int) $user_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_users.session

        FROM
            {$this->dbPrefix}_users

        WHERE
            {$this->dbPrefix}_users.id = {$user_id}

QUERY;
    }

    public function getSessionFromName($user_name) {
        global $Filter;
        $user_name = $Filter->SQL($user_name);

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_users.session

        FROM
            {$this->dbPrefix}_users

        WHERE
            {$this->dbPrefix}_users.name = "{$user_name}"

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

    public function updateSession($id, $session) {
        global $Filter;
        $id      = (int) $id;
        $session = $Filter->SQL($session);

        return <<<QUERY
        
        UPDATE
            {$this->dbPrefix}_users

        SET
            {$this->dbPrefix}_users.session = "{$session}"

        WHERE
            {$this->dbPrefix}_users.id = {$id}

QUERY;
    }

}
?>
