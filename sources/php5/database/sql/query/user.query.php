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
class UserQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function getName($id) {
        $id = (int) $id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_users.name

        FROM
            {$this->dbPrefix}_users

        WHERE
            {$this->dbPrefix}_users.id = {$id}

QUERY;
    }

    public function getSessionFromId($user_id) {
        $user_id = (int) $user_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_users.session

        FROM
            {$this->dbPrefix}_users

        WHERE
            {$this->dbPrefix}_users.id = {$user_id}

QUERY;
    }

    public function getSessionFromName($user_name) {
        global $Filter;
        $user_name = $Filter->SQL($user_name);

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_users.session

        FROM
            {$this->dbPrefix}_users

        WHERE
            {$this->dbPrefix}_users.name = "{$user_name}"

QUERY;
    }

    public function exists($username) {
        global $Filter;
        $username = $Filter->SQL(trim($username));

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_users.name
        
        FROM
            {$this->dbPrefix}_users
            
        WHERE
            {$this->dbPrefix}_users.name = "{$username}"

QUERY;
    }
    
    public function emailExists($email) {
        global $Filter;
        $email = $Filter->SQL($email);

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_users.email
            
        FROM
            {$this->dbPrefix}_users
            
        WHERE
            {$this->dbPrefix}_users.email = "{$email}"

QUERY;
    }

    public function updateSession($user_id, $session) {
        global $Filter;
        $user_id = (int) $user_id;
        $session = $Filter->SQL($session);

        return <<<QUERY
        
        UPDATE
            {$this->dbPrefix}_users

        SET
            {$this->dbPrefix}_users.session = "{$session}"

        WHERE
            {$this->dbPrefix}_users.id = {$user_id}

QUERY;
    }

    public function getLulzCode($user_id) {
        $user_id = (int) $user_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_users.option_lulzcode

        FROM
            {$this->dbPrefix}_users

        WHERE
            {$this->dbPrefix}_users.id = {$user_id}

QUERY;
    }

    public function setLulzCode($user_id, $state) {
        $user_id = (int) $user_id;
        $state   = ($state) ? 'TRUE' : 'FALSE';

        return <<<QUERY

        UPDATE
            {$this->dbPrefix}_users

        SET
            {$this->dbPrefix}_users.option_lulzcode = {$state}

        WHERE
            {$this->dbPrefix}_users.id = {$user_id}

QUERY;
    }

    public function getInfos($user_id) {
        $user_id = (int) $user_id;

        return <<<QUERY

        SELECT
            *

        FROM
            {$this->dbPrefix}_users

        WHERE
            {$this->dbPrefix}_users.id = {$user_id}

QUERY;
    }
}
?>
