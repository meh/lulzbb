<?php
/**
* @package PHP5
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
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct($Database) {
        $query = new RegistrationQuery();
        parent::__construct($Database, $query);
    }
    
    /**
    * Register an account.

    * @param    string    $username    The username.
    * @param    string    $password    The password.
    * @param    string    $email       The email address.
    */
    public function exec($username, $password, $email) {
        $this->Database->sendQuery($this->Query->exec($username, $password, $email));
        $this->Database->user->group->addUser($username, 'Unconfirmed');
    } 
}
?>
