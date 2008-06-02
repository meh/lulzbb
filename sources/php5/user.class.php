<?php
/**
* @package PHP5
* @category User

* @license http://opensource.org/licenses/gpl-3.0.html
*/

/**
* The user class, i'm not good with descriptions :S

* @author cHoBi
*/
class User {
    private $id;
    private $name;
    private $groups;
    private $session;

    /**
    * Initialize important data, like the name and the groups.

    * @param    int    $id    The user id.
    */
    public function __construct($id) {
        global $Database;
        $Database->user->updateSession($id);

        $this->id      = $id;
        $this->name    = $Database->user->getName($id);
        $this->groups  = $Database->user->group->get($this->getName('RAW'));
        $this->session = session_id();
    }

    /**
    * Gives the user id.

    * @return    int    The user id.
    */
    public function getId() {
        return $this->id;
    }

    /**
    * Gives the user name.

    * @param    string    $type    The filtering type.

    * @return    mixed    The user name.
    */
    public function getName($type) {
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
    public function isIn($groupName) {
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
    public function addTo($groupName) {
        global $Database;

        try {
            $Database->user->group->addUser($this->getName('RAW'), $groupName);
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
    public function removeFrom($groupName) {
        global $Database;

        foreach ($this->groups as $n => $group) {
            if ($group['RAW'] == $groupName) {
                unset($this->groups[$n]);

                try {
                    $Database->user->group->removeUser(
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
}
?>
