<?php
/**
* @package Core-PHP5
* @category Query

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

include_once(SOURCES_PATH.'/database/sql/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class RegistrationQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function exec($username, $password, $email) {
        global $Filter;
        $username = $Filter->SQL($username);
        $password = $Filter->crypt($password);
        $email    = $Filter->SQL($email);

        return <<<QUERY
        
        INSERT
            INTO {$this->dbPrefix}_users(
                name,
                password,
                email,
                registration_date
            )
            
            VALUES(
                "{$username}",
                "{$password}",
                "{$email}",
                NOW()
            )

QUERY;
    }
}
?>
