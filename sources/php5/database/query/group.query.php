<?php
/**
* @package lulzBB-PHP5
* @license http://opensource.org/licenses/gpl-3.0.html
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

    public function add($group, $description) {
        global $filter;
        $group       = $filter->SQL($group);
        $description = $filter->SQL($description);

        return <<<QUERY

        INSERT IGNORE
            INTO {$this->dbPrefix}_groups(
                {$this->dbPrefix}_groups.name,
                {$this->dbPrefix}_groups.description
            )

            VALUES(
                "{$group}",
                "{$description}"
            )

QUERY;
    }

    public function addUser($username, $group) {
        global $filter;
        $username = $filter->SQL($username);
        $group    = $filter->SQL($group);

        return <<<QUERY

        INSERT IGNORE
            INTO {$this->dbPrefix}_groups(
                {$this->dbPrefix}_groups.username,
                {$this->dbPrefix}_groups.name
            )

            VALUES(
                "{$username}",
                "{$group}"
            )

QUERY;
    }

    public function remove($group) {
        global $filter;
        $group = $filter->SQL($group);

        return <<<QUERY

        DELETE
            FROM {$this->dbPrefix}_groups

            WHERE
                {$this->dbPrefix}_groups.name = "$group"

QUERY;
    }

    public function removeUser($username, $group) {
        global $filter;
        $username = $filter->SQL($username);
        $group    = $filter->SQL($group);

        return <<<QUERY

        DELETE
            FROM {$this->dbPrefix}_groups

        WHERE
            {$this->dbPrefix}_groups.name = "{$group}"
          AND
            {$this->dbPrefix}_groups.username = "{$username}"

QUERY;
    }

    public function checkUser($username, $group) {
        global $filter;
        $username = $filter->SQL($username);
        $group    = $filter->SQL($group);
    }
}
?>
