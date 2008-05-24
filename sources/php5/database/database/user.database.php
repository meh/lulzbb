<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/database/user/login.database.php');
require_once(SOURCE_PATH.'/database/database/user/registration.database.php');
require_once(SOURCE_PATH.'/database/query/user.query.php');

/**
* This class is dedicated to user stuff.

* @property    reference    $login           The login database.
* @property    reference    $registration    The registration database.

* @author cHoBi
*/
class UserDatabase extends DatabaseBase {
    // Login and Registration database classes
    public $login;
    public $registration;

    /**
    * In Lulz we trust.
    
    * @param    object    $database   The Database object, recursive object is recursive.
    */
    public function __construct($database) {
        $query = new UserQuery();
        parent::__construct($database, $query);
       
        // Initiate login and registration databases.
        $this->login        = new LoginDatabase($database);
        $this->registration = new RegistrationDatabase($database);
    }
    
    /**
    * Checks if the username already exists in the db.
    
    * @param    string    $username    The username, no no, the FAGGET.
    
    * @return    bool    TRUE : User already exists.
    *                    FALSE: What about no?
    */
    public function exists($username) {
        $username = trim($username);
        $query    = $this->database->sendQuery($this->Query->exists($username));

        if (mysql_fetch_row($query)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
    * Checks if the email address already exists in the db.
    
    * @param    string    $email    The email address to check.
    
    * @return    bool    TRUE : The email address already exists.
    *                    FALSE: I'MA 'FIRING MAH LAZOR
    */
    public function emailExists($email) {
        $query = $this->database->sendQuery($this->Query->emailExists($email));

        if (mysql_fetch_row($query)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
    * Gets the groups where the user is in.
    
    * @param    string    $username    The ketchup.
    
    * @return    array    Normal array with a group for each element.
    */
    public function getGroups($username) {
        $query = $this->database->sendQuery($this->Query->getGroups($username));

        $groups = mysql_fetch_row($query);
        return $groups;
    }
}
?>
