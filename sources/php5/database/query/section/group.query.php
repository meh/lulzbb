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
