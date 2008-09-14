<?php
/**
* @package PHP5
* @category Database

* @license AGPLv3
* lulzBB is a CMS for the lulz but it's also serious business.
* Copyright (C) 2008 lulzGroup
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once(SOURCES_PATH.'/database/sql/database.class.php');
require_once($M_SOURCES_PATH.'/database/sql/database/login.database.php');
require_once($M_SOURCES_PATH.'/database/sql/database/registration.database.php');
require_once($M_SOURCES_PATH.'/database/sql/database/group.database.php');
require_once($M_SOURCES_PATH.'/database/sql/database.query.php');

/**
* This class is dedicated to user stuff.

* @property    object    $login           The login database.
* @property    object    $registration    The registration database.
* @property    object    $group           The group database.

* @author cHoBi
*/
class UserDatabase extends Database
{
    // Login and Registration database classes
    public $login;
    public $registration;
    public $group;

    /**
    * In Lulz we trust.
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct ()
    {
        $this->Query = new UserQuery();
        parent::__construct();
       
        // Initiate login and registration databases.
        $this->login        = new LoginDatabase($this);
        $this->registration = new RegistrationDatabase($this);
        $this->group        = new UserGroupDatabase($this);
    }

    /**
    * Gets the id from a user name.

    * @param    int    $user_name    The user name.

    * @return    string    The user's id.
    */
    public function getId ($username)
    {
        $this->sendQuery($this->Query->getId($username));
        $user = $this->fetchArray();

        if (isset($user)) {
            return (int) $user['id']['RAW'];
        }
        else {
            return false;
        }
    }
    
    /**
    * Gets the username of an user.

    * @param    int    $id    The user id.

    * @return    array    The name filtered.
    */
    public function getName ($id)
    {
        $this->sendQuery($this->Query->getName($id));
        $user = $this->fetchArray();

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
    public function getSession ($user)
    {
        if (is_numeric($user)) {
            $this->datatabase->sendQuery($this->Query->getSessionFromId($user));
        }
        else if (is_string($user)) {
            $this->sendQuery($this->Query->getSessionFromName($user));
        }
        $session = $this->fetchArray();

        return $session['session']['RAW'];
    }

    /**
    * Updates the session id with the passed one.
    
    * @param    string    $username    The username.
    */
    public function updateSession ($user_id, $session = 0)
    {
        if (empty($session)) {
            $session = session_id();
        }
        
        $this->sendQuery($this->Query->updateSession($user_id, $session));
    }

    /**
    * Checks if the username already exists in the db.
    
    * @param    string    $username    The username, no no, the FAGGET.
    
    * @return    bool    TRUE : User already exists.
    *                    FALSE: What about no?
    */
    public function exists ($username)
    {
        $query = $this->sendQuery($this->Query->exists($username));

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
    public function emailExists ($email)
    {
        $query = $this->sendQuery($this->Query->emailExists($email));

        if (mysql_fetch_row($query)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
    * Gets the bbcode state.

    * @param    int    $user_id    The user id.
    */
    public function getBBCode ($user_id)
    {
        $query = $this->sendQuery($this->Query->getBBCode($user_id));
        $state = mysql_fetch_row($query);

        return $state[0];
    }

    /**
    * Sets the bbcode state for a user.

    * @param    int     $user_id    The user id.
    * @param    bool    $state      The state.
    */
    public function setLulzCode ($user_id, $state)
    {
        $this->sendQuery($this->Query->setLulzCode($user_id, $state));
    }

    /**
    * Gets all the infos about a user.

    * @param    int    $user_id    The user id.

    * @return    array
    */
    public function getInfos ($user_id)
    {
        $this->sendQuery($this->Query->getInfos($user_id));

        return $this->fetchArray();
    }
}
?>
