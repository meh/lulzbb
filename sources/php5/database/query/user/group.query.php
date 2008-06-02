<?php
/**
* @package PHP5
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
