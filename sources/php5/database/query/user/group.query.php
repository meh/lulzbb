<?php
/**
* @package PHP5
* @category Query

* @license AGPLv3
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
**/

require_once(SOURCE_PATH.'/database/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class GroupQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function exists($group) {
        global $Filter;
        $group = $Filter->SQL($group);

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_user_groups.name

        FROM
            {$this->dbPrefix}_user_groups

        WHERE
            {$this->dbPrefix}_user_groups.name = "{$group}"

QUERY;
    }
   
    public function get($username) {
        global $Filter;
        $username = $Filter->SQL($username);

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_user_groups.name
            
        FROM
            {$this->dbPrefix}_user_groups

        WHERE
            {$this->dbPrefix}_user_groups.username = "{$username}"

QUERY;
    }

    public function add($group, $description) {
        global $Filter;
        $group       = $Filter->SQL($group);
        $description = $Filter->SQL($description);

        return <<<QUERY

        INSERT IGNORE
            INTO {$this->dbPrefix}_user_groups(
                {$this->dbPrefix}_user_groups.name,
                {$this->dbPrefix}_user_groups.description
            )

            VALUES(
                "{$group}",
                "{$description}"
            )

QUERY;
    }

    public function addUser($username, $group) {
        global $Filter;
        $username = $Filter->SQL($username);
        $group    = $Filter->SQL($group);

        return <<<QUERY

        INSERT IGNORE
            INTO {$this->dbPrefix}_user_groups
            (
                {$this->dbPrefix}_user_groups.username,
                {$this->dbPrefix}_user_groups.name
            )

            VALUES
            (
                "{$username}",
                "{$group}"
            )

QUERY;
    }

    public function remove($group) {
        global $Filter;
        $group = $Filter->SQL($group);

        return <<<QUERY

        DELETE
            FROM {$this->dbPrefix}_user_groups

            WHERE
                {$this->dbPrefix}_user_groups.name = "$group"

QUERY;
    }

    public function removeUser($username, $group) {
        global $Filter;
        $username = $Filter->SQL($username);
        $group    = $Filter->SQL($group);

        return <<<QUERY

        DELETE
            FROM {$this->dbPrefix}_user_groups

        WHERE
            {$this->dbPrefix}_user_groups.name = "{$group}"
          AND
            {$this->dbPrefix}_user_groups.username = "{$username}"

QUERY;
    }

    public function checkUser($username, $group) {
        global $Filter;
        $username = $Filter->SQL($username);
        $group    = $Filter->SQL($group);
    }
}
?>
