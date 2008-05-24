<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/query/user/registration.query.php');

/**
* Registration database class.

* @author cHoBi
*/
class RegistrationDatabase extends DatabaseBase {
    /**
    * Girls are evil, remember 
    
    * @param    object    $database   The Database object, recursive object is recursive.
    */
    public function __construct($database) {
        $query = new RegistrationQuery();
        parent::__construct($database, $query);
    }
    
    /**
    * Register an account.

    * @param    string    $username    The username.
    * @param    string    $password    The password.
    * @param    string    $email       The email address.
    */
    public function exec($username, $password, $email) {
        $this->database->sendQuery($this->Query->exec($username, $password, $email));
        $this->database->group->addUser($username, 'Unconfirmed');
    } 
}
?>
