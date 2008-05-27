<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/query/user/group.query.php');

/**
* This class is dedicated to group stuff.
*
* @author cHoBi
*/
class UserGroupDatabase extends DatabaseBase {
    /**
    * Guess what?
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct($Database) {
        $query = new GroupQuery();
        parent::__construct($Database, $query);
    }
    
    /**
    * Gets the groups where the user is in.
    
    * @param    string    $username    The ketchup.
    
    * @return    array    Normal array with a group for each element.
    */
    public function get($username) {
        $this->Database->sendQuery($this->Query->get($username));

        $groups = array();
        while ($group = $this->Database->fetchArray()) {
            array_push($groups, $group['name']);
        }

        return $groups;
    }

    /**
    * Adds a group.
    
    * @param    string    $group          The group name.
    * @param    string    $description    The description of the group.
    */
    public function add($group, $description) {
        $this->Database->sendQuery($this->Query->add($group, $description));
    }

    /**
    * Adds a user to a group.
    
    * @param    string    $username    The username to add.
    * @param    string    $group       The group where to add the user.
    */
    public function addUser($username, $group) {
        $this->Database->sendQuery($this->Query->addUser($username, $group));
    }

    /**
    * Removes a group.
    
    * @param    string    $group    The group name to remove.
    */
    public function remove($group) {
        $this->Database->sendQuery($this->Query->remove($group));
    }

    /**
    * Removes a user from a group.
    
    * @param    string    $username    The username to remove from a group.
    * @param    string    $group       The group name.
    */
    public function removeUser($username, $group) {
        $this->Database->sendQuery($this->Query->removeUser($username, $group));
    }
}
?>