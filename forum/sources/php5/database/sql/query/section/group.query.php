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

include_once(SOURCES_PATH.'/database/sql/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class SectionGroupQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function exists($parent) {
        $parent = (int) $parent;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_section_groups.id

        FROM
            {$this->dbPrefix}_section_groups

        WHERE
            {$this->dbPrefix}_section_groups.id = {$parent}

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

    public function heaviest($parent) {
        $parent = (int) $parent;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_sections.id

        FROM
            {$this->dbPrefix}_sections

        WHERE
            {$this->dbPrefix}_sections.parent = {$parent}

        ORDER BY
            {$this->dbPrefix}_sections.weight

        DESC

        LIMIT 1

QUERY;
    }

    public function lightest($parent) {
        $parent = (int) $parent;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_sections.id

        FROM
            {$this->dbPrefix}_sections

        WHERE
            {$this->dbPrefix}_sections.parent = {$parent}

        ORDER BY
            {$this->dbPrefix}_sections.weight

        ASC

        LIMIT 1

QUERY;
    }

    public function getParent($parent) {
        $parent = (int) $parent;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_section_groups.parent

        FROM
            {$this->dbPrefix}_sections

        INNER JOIN {$this->dbPrefix}_section_groups
            ON {$this->dbPrefix}_sections.parent =
               {$this->dbPrefix}_section_groups.id

        WHERE
            {$this->dbPrefix}_sections.id = {$parent}

QUERY;
    }

    public function getName($parent) {
        $parent = (int) $parent;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_section_groups.name

        FROM
            {$this->dbPrefix}_section_groups

        WHERE
            {$this->dbPrefix}_section_groups.id = {$parent}

QUERY;
    }

    public function getSections($parent) {
        $parent = (int) $parent;

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_sections.id,
            {$this->dbPrefix}_sections.parent,
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
            {$this->dbPrefix}_sections.parent = {$parent}
            
        ORDER BY
            {$this->dbPrefix}_sections.weight

QUERY;
    }
}
?>
