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
class LoginQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function check($username, $password) {
        global $filter;
        $username = $filter->SQL(trim($username));
        $password = $filter->crypt($password);

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_users.id,
            {$this->dbPrefix}_users.name
       
        FROM
            {$this->dbPrefix}_users
            
        WHERE
            {$this->dbPrefix}_users.name = "{$username}"
          AND
            {$this->dbPrefix}_users.password = "{$password}"

QUERY;
    }

    public function exec($username) {
        global $filter;
        $username = $filter->SQL(trim($username));
        $session  = $filter->SQL(session_id());

        return <<<QUERY
        
        UPDATE
            {$this->dbPrefix}_users

        SET
            {$this->dbPrefix}_users.session = "{$session}"

        WHERE
            {$this->dbPrefix}_users.name = "{$username}"

QUERY;
    }
}
?>
