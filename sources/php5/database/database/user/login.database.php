<?php
/**
* @package PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/query/user/login.query.php');

/**
* This class is dedicated to login stuff.

* @author cHoBi
*/
class LoginDatabase extends DatabaseBase {
    /**
    * Always the same, this is the way :D
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct($Database) {
        $query = new LoginQuery();
        parent::__construct($Database, $query);
    }
    
    /**
    * Checks if the username and password are right.
    
    * @param    string    $username    The username.
    * @param    string    $password    The password... fail
    
    * @return    array    (id, name)
    */
    public function check($username, $password) {
        $this->Database->sendQuery($this->Query->check($username, $password));
        $user = $this->Database->fetchArray();

        return $user['id']['RAW'];
    }
}
?>
