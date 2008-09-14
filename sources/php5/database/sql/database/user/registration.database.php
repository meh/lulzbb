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
require_once(SOURCES_PATH.'/database/sql/query/user/registration.query.php');

/**
* Registration database class.

* @author cHoBi
*/
class RegistrationDatabase extends DatabaseBase
{
    /**
    * Girls are evil, remember it.
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct ($Database)
    {
        $query = new RegistrationQuery();
        parent::__construct($Database, $query);
    }
    
    /**
    * Register an account.

    * @param    string    $username    The username.
    * @param    string    $password    The password.
    * @param    string    $email       The email address.
    */
    public function exec ($username, $password, $email)
    {
        $this->Database->sendQuery($this->Query->exec($username, $password, $email));
        $this->Database->user->group->addUser($username, 'Unconfirmed');
    } 
}
?>
