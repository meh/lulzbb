<?php
/**
* @package Core-PHP5
* @category User

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

/**
* The user class, i'm not good with descriptions :S

* @author cHoBi
*/
class User
{
    private $id;
    private $name;
    private $groups;
    private $session;

    private $bbcode;

    /**
    * Initialize important data, like the name and the groups.

    * @param    int    $id    The user id.
    */
    public function __construct ($id)
    {
        global $Database;
        $Database->_('core')->user->updateSession($id);

        $this->id      = $id;
        $this->name    = $Database->_('core')->user->getName($id);
        $this->groups  = $Database->_('core')->user->group->get($id);
        $this->session = session_id();

        $this->bbcode = $Database->_('core')->user->getBBCode($id);
    }

    /**
    * Gives the user id.

    * @return    int    The user id.
    */
    public function getId ()
    {
        return $this->id;
    }

    /**
    * Gives the user name.

    * @param    string    $type    The filtering type.

    * @return    mixed    The user name.
    */
    public function getName ($type)
    {
        switch ($type) {
            case 'RAW':
            return $this->name['RAW'];
            break;

            case 'HTML':
            return $this->name['HTML'];
            break;

            case 'POST':
            return $this->name['POST'];
            break;

            case 'ALL':
            return $this->name;
            break;
        }
    }

    /**
    * Checks if the user is in the group.

    * @param    string    $groupName    The group's name.

    * @return    bool    True if it's in the group, false if it's not.
    */
    public function isIn ($groupName)
    {
        $groupName = strtolower($groupName);

        foreach ($this->groups as $group) {
            if (strtolower($group['RAW']) == $groupName) {
                return true;
            }
        }

        return false;
    }

    /**
    * Adds the user to the group.

    * @param    string    $groupName    The group's name.
    */
    public function addTo ($groupName)
    {
        global $Database;

        try {
            $Database->_('core')->user->group->addUser($this->getName('RAW'), $groupName);
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }

        array_push($this->groups, $groupName);
    }

    /**
    * Removes the user from the group.

    * @param    string    $groupName    The group's name.
    */
    public function removeFrom ($groupName)
    {
        global $Database;

        foreach ($this->groups as $n => $group) {
            if ($group['RAW'] == $groupName) {
                unset($this->groups[$n]);

                try {
                    $Database->_('core')->user->group->removeUser(
                        $this->getName('RAW'),
                        $groupName);
                }
                catch (lulzException $e) {
                    die($e->getMessage());
                }
                break;
            }
        }
    }

    /**
    * Gets the bbcode setting.

    * @return    bool
    */
    public function getLulzCode ()
    {
        return $this->bbcode;
    }

    /**
    * Sets the bbcode setting.

    * @param    bool    $state    The bbcode status.
    */
    public function setLulzCode ($state)
    {
        global $Database;
        $Database->user->setLulzCode($this->id, $state);

        $this->bbcode = $state;
    }
}
?>
