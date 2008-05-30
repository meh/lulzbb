<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/database/user/login.database.php');
require_once(SOURCE_PATH.'/database/database/user/registration.database.php');
require_once(SOURCE_PATH.'/database/database/user/group.database.php');
require_once(SOURCE_PATH.'/database/query/user.query.php');

/**
* This class is dedicated to user stuff.

* @property    object    $login           The login database.
* @property    object    $registration    The registration database.
* @property    object    $group           The group database.

* @author cHoBi
*/
class UserDatabase extends DatabaseBase {
    // Login and Registration database classes
    public $login;
    public $registration;
    public $group;

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
        $this->group        = new UserGroupDatabase($Database);
    }
    
    /**
    * Gets the username of an user.

    * @param    int    $id    The user id.

    * @return    array    The name filtered.
    */
    public function getName($id) {
        $this->Database->sendQuery($this->Query->getName($id));
        $user = $this->Database->fetchArray();

        if (isset($user)) {
            return $user['name'];
        }
        else {
            
        }
    }

    /**
    * Gets the session id of the user.

    * @param    mixed    The user id or name.

    * @return    string    The user's session id.
    */
    public function getSession($user) {
        if (is_numeric($user)) {
            $this->datatabase->sendQuery($this->Query->getSessionFromId($user));
        }
        else if (is_string($user)) {
            $this->Database->sendQuery($this->Query->getSessionFromName($user));
        }
        $session = $this->Database->fetchArray();

        return $session['session']['RAW'];
    }

    /**
    * Checks if the username already exists in the db.
    
    * @param    string    $username    The username, no no, the FAGGET.
    
    * @return    bool    TRUE : User already exists.
    *                    FALSE: What about no?
    */
    public function exists($username) {
        $query = $this->Database->sendQuery($this->Query->exists($username));

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
}
?>
