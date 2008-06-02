<?php
/**
* @package PHP5
* @category Query

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class SectionGroupQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function exists($group_id) {
        $group_id = (int) $group_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_section_groups.id

        FROM
            {$this->dbPrefix}_section_groups

        WHERE
            {$this->dbPrefix}_section_groups.id = {$group_id}

QUERY;
    }

    public function add($parent, $weight, $name) {
        global $Filter;
        $parent = (int) $parent;
        $weight = (int) $weight;
        $name   = $Filter->SQL($name);

        return <<<QUERY

        INSERT
            INTO {$this->dbPrefix}_section_groups(
                {$this->dbPrefix}_section_groups.parent,
                {$this->dbPrefix}_section_groups.weight,
                {$this->dbPrefix}_section_groups.name
            )

            VALUES(
                {$parent},
                {$weight},
                "{$name}"
            )

QUERY;
    }

    public function heaviest($group_id) {
        $group_id = (int) $group_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_sections.id

        FROM
            {$this->dbPrefix}_sections

        WHERE
            {$this->dbPrefix}_sections.group_id = {$group_id}

        ORDER BY
            {$this->dbPrefix}_sections.weight

        DESC

        LIMIT 1

QUERY;
    }

    public function lightest($group_id) {
        $group_id = (int) $group_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_sections.id

        FROM
            {$this->dbPrefix}_sections

        WHERE
            {$this->dbPrefix}_sections.group_id = {$group_id}

        ORDER BY
            {$this->dbPrefix}_sections.weight

        ASC

        LIMIT 1

QUERY;
    }

    public function getParent($group_id) {
        $group_id = (int) $group_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_section_groups.parent

        FROM
            {$this->dbPrefix}_sections

        INNER JOIN {$this->dbPrefix}_section_groups
            ON {$this->dbPrefix}_sections.group_id =
               {$this->dbPrefix}_section_groups.id

        WHERE
            {$this->dbPrefix}_sections.id = {$group_id}

QUERY;
    }

    public function getName($group_id) {
        $group_id = (int) $group_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_section_groups.name

        FROM
            {$this->dbPrefix}_section_groups

        WHERE
            {$this->dbPrefix}_section_groups.id = {$group_id}

QUERY;
    }

    public function getSections($group_id) {
        $group_id = (int) $group_id;

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_sections.id,
            {$this->dbPrefix}_sections.group_id,
            {$this->dbPrefix}_sections.title,
            {$this->dbPrefix}_sections.subtitle,

            {$this->dbPrefix}_sections.count_topics,
            {$this->dbPrefix}_sections.count_posts,

            {$this->dbPrefix}_sections.last_topic_id,
            {$this->dbPrefix}_sections.last_topic_title,
            {$this->dbPrefix}_sections.last_post_id,
            {$this->dbPrefix}_sections.last_post_time,
            {$this->dbPrefix}_sections.last_user_id,
            {$this->dbPrefix}_sections.last_user_name
            
        FROM
            {$this->dbPrefix}_sections

        WHERE
            {$this->dbPrefix}_sections.group_id = {$group_id}
            
        ORDER BY
            {$this->dbPrefix}_sections.weight

QUERY;
    }
}
?>
