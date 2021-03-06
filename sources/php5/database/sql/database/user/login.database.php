<?php
/**
* @package Core-PHP5
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

include_once(SOURCES_PATH.'/database/sql/database.base.class.php');
include_once(SOURCES_PATH.'/database/sql/query/user/login.query.php');

/**
* This class is dedicated to login stuff.

* @author cHoBi
*/
class LoginDatabase extends DatabaseBase
{
    /**
    * Always the same, this is the way :D
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct ($Database)
    {
        parent::__construct($Database, new LoginQuery);
    }
    
    /**
    * Checks if the username and password are right.
    
    * @param    string    $username    The username.
    * @param    string    $password    The password... fail
    
    * @return    array    (id, name)
    */
    public function check ($username, $password)
    {
        $this->Database->sendQuery($this->Query->check($username, $password));
        $user = $this->Database->fetchArray();

        return $user['id']['RAW'];
    }
}
?>
