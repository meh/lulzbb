<?php
/**
* @package lulzBB-PHP5
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
        $Database->user->login->updateSession($id);

        $this->id      = $id;
        $this->name    = $Database->user->getName($id);
        $this->groups  = $Database->user->getGroups($this->getName('RAW'));
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

    public function isIn($groupName) {
        foreach ($this->groups as $group) {
            if ($group['RAW'] == $groupName) {
                return true;
            }
        }

        return false;
    }

    public function removeFrom($groupName) {
        foreach ($this->groups as $n => $group) {
            if ($group['RAW'] == $groupName) {
                unset($this->groups[$n]);
            }
        }
    }
}
?>
