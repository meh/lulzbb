<?php
/**
* @package PHP5
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

require_once(SOURCE_PATH.'/database/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class LoginQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function check($username, $password) {
        global $Filter;
        $username = $Filter->SQL(trim($username));
        $password = $Filter->crypt($password);

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_users.id
       
        FROM
            {$this->dbPrefix}_users
            
        WHERE
            {$this->dbPrefix}_users.name = "{$username}"
          AND
            {$this->dbPrefix}_users.password = "{$password}"

QUERY;
    }
}
?>
