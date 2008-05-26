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

* @property    object    $login           The login database.
* @property    object    $registration    The registration database.

* @author cHoBi
*/
class UserDatabase extends DatabaseBase {
    // Login and Registration database classes
    public $login;
    public $registration;

    /**
    * In Lulz we trust.
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct($Database) {
        $Query = new UserQuery();
        parent::__construct($Database, $Query);
       
        // Initiate login and registration databases.
        $this->login        = new LoginDatabase($Database);
        $this->registration = new RegistrationDatabase($Database);
    }
    
    /**
    * Gets the username of an user.

    * @param    int    $id    The user id.

    * @return    array    The name filtered.
    */
    public function getName($id) {
        $this->Database->sendQuery($this->Query->getName($id));
        $user = $this->Database->fetchArray();

        return $user['name'];
    }

    /**
    * Checks if the username already exists in the db.
    
    * @param    string    $username    The username, no no, the FAGGET.
    
    * @return    bool    TRUE : User already exists.
    *                    FALSE: What about no?
    */
    public function exists($username) {
        $query    = $this->Database->sendQuery($this->Query->exists($username));

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
        $query = $this->Database->sendQuery($this->Query->emailExists($email));

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
        $this->Database->sendQuery($this->Query->getGroups($username));

        $groups = array();
        while ($group = $this->Database->fetchArray()) {
            array_push($groups, $group['name']);
        }

        return $groups;
    }
}
?>
