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
**/

include_once(SOURCES_PATH.'/database/sql/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class GroupQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function exists ($group) {
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
   
    public function get ($user_id) {
        $user_id = (int) $user_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_user_groups.id,
            {$this->dbPrefix}_user_groups.name,
            {$this->dbPrefix}_user_groups.level

            
        FROM
            {$this->dbPrefix}_user_groups_users

        INNER JOIN {$this->dbPrefix}_user_groups
            ON {$this->dbPrefix}_user_groups.id =
               {$this->dbPrefix}_user_groups_users.group_id

        WHERE
            {$this->dbPrefix}_user_groups_users.user_id = {$user_id}

QUERY;
    }

    public function getId ($group)
    {
        global $Filter;
        $group = $Filter->SQL($group);

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_user_groups.id

        FROM
            {$this->dbPrefix}_user_groups

        WHERE
            {$this->dbPrefix}_user_groups.name = "{$group}"

QUERY;
    }

    public function add ($name, $description, $level) {
        global $Filter;
        $name        = $Filter->SQL($name);
        $description = $Filter->SQL($description);
        $level       = (int) $level;

        return <<<QUERY

        INSERT IGNORE
            INTO {$this->dbPrefix}_user_groups(
                {$this->dbPrefix}_user_groups.name,
                {$this->dbPrefix}_user_groups.description,
                {$this->dbPrefix}_user_groups.level
            )

            VALUES(
                "{$name}",
                "{$description}"
                $level
            )

QUERY;
    }

    public function addUser($user_id, $group_id, $level) {
        $user_id  = (int) $user_id;
        $group_id = (int) $group_id;
        $level    = (int) $level;

        return <<<QUERY

        INSERT IGNORE
            INTO {$this->dbPrefix}_user_groups_users
            (
                {$this->dbPrefix}_user_groups_users.group_id,
                {$this->dbPrefix}_user_groups_users.user_id,
                {$this->dbPrefix}_user_groups_users.level
            )

            VALUES
            (
                $group_id,
                $user_id,
                $level
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
