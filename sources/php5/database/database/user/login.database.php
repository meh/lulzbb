<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/query/user/login.query.php');

/**
* This class is dedicated to login stuff.

* @author cHoBi
*/
class LoginDatabase extends DatabaseBase {
    /**
    * Always the same, this is the way :D
    
    * @param    object    $database   The Database object, recursive object is recursive.
    */
    public function __construct($database) {
        $query = new LoginQuery();
        parent::__construct($database, $query);
    }
    
    /**
    * Checks if the username and password are right.
    
    * @param    string    $username    The username.
    * @param    string    $password    The password... fail
    
    * @return    array    (id, name)
    */
    public function check($username, $password) {
        $this->database->sendQuery($this->Query->check($username, $password));
        return $this->database->fetchArray();
    }

    /**
    * Logins the user by updating the session id with the actual.
    
    * @param    string    $username    The username.
    */
    public function exec($username) {
        $this->database->sendQuery($this->Query->exec($username));
    }
}
?>
