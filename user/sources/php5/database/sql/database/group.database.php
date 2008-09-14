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

require_once(SOURCES_PATH.'/database/sql/database.base.class.php');
require_once($M_SOURCES_PATH.'/database/sql/query/group.query.php');

/**
* This class is dedicated to group stuff.
*
* @author cHoBi
*/
class UserGroupDatabase extends DatabaseBase
{
    /**
    * Guess what?
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct ($Database)
    {
        $query = new GroupQuery();
        parent::__construct($Database, $query);
    }

    /**
    * Says if the group exists or not.

    * @param    string    $group    The group name.

    * @return    bool    True if it exists, false if it doesn't.
    */
    public function exists ($group)
    {
        $query = $this->Database->sendQuery($this->Query->exists($group));

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
    public function get ($user_id)
    {
        $this->Database->sendQuery($this->Query->get($user_id));

        $groups = array();
        while ($group = $this->Database->fetchArray()) {
            array_push($groups, array(
                'id'    => $group['id'],
                'name'  => $group['name'],
                'level' => $group['level']
            ));
        }

        return $groups;
    }

    /**
    * Adds a group.
    
    * @param    string    $group          The group name.
    * @param    string    $description    The description of the group.
    */
    public function add ($group, $description)
    {
        $this->Database->sendQuery($this->Query->add($group, $description));
    }

    /**
    * Adds a user to a group.
    
    * @param    string    $username    The username to add.
    * @param    string    $group       The group where to add the user.

    * @return    bool    True if it added the user, false if it didn't.
    */
    public function addUser ($username, $group)
    {
        if (!$this->Database->group->exists($group)) {
            return false;
        }
        if (!$this->Database->exists($username)) {
            return false;
        }

        $user_id  = $this->getId($username);
        $group_id = $this->getId($group);

        $this->Database->sendQuery($this->Query->addUser($user_id, $group_id));

        return true;
    }

    /**
    * Removes a group.
    
    * @param    string    $group    The group name to remove.
    */
    public function remove ($group)
    {
        $this->Database->sendQuery($this->Query->remove($group));
    }

    /**
    * Removes a user from a group.
    
    * @param    string    $username    The username to remove from a group.
    * @param    string    $group       The group name.
    */
    public function removeUser ($username, $group)
    {
        $this->Database->sendQuery($this->Query->removeUser($username, $group));
    }
}
?>
