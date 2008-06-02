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
class RegistrationQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function exec($username, $password, $email) {
        global $Filter;
        $username = $Filter->SQL($username);
        $password = $Filter->crypt($password);
        $email    = $Filter->SQL($email);

        return <<<QUERY
        
        INSERT
            INTO {$this->dbPrefix}_users(
                {$this->dbPrefix}_users.name,
                {$this->dbPrefix}_users.password,
                {$this->dbPrefix}_users.email,
                {$this->dbPrefix}_users.registration_date
            )
            
            VALUES(
                "{$username}",
                "{$password}",
                "{$email}",
                NOW()
            )

QUERY;
    }
}
?>
