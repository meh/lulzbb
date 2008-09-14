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

require_once(SOURCES_PATH.'/database/sql/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class SectionQuery extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function isLocked ($section_id)
    {
        $section_id = (int) $section_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_sections.locked

        FROM
            {$this->dbPrefix}_sections

        WHERE
            {$this->dbPrefix}_sections.id = {$section_id}

QUERY;
    }

    public function isContainer ($section_id)
    {
        $section_id = (int) $section_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_sections.container

        FROM
            {$this->dbPrefix}_sections

        WHERE
            {$this->dbPrefix}_sections.id = {$section_id}

QUERY;
    }

    public function add($parent, $weight, $title, $subtitle, $locked, $container)
    {
        global $Filter;
        $parent    = (int) $parent;
        $weight    = (int) $weight;
        $title     = $Filter->SQL($title);
        $subtitle  = $Filter->SQL($subtitle);
        $locked    = ($locked)    ? 'TRUE' : 'FALSE';
        $container = ($container) ? 'TRUE' : 'FALSE';

        return <<<QUERY

        INSERT
            INTO {$this->dbPrefix}_sections(
                {$this->dbPrefix}_sections.parent,
                {$this->dbPrefix}_sections.weight,
                {$this->dbPrefix}_sections.title,
                {$this->dbPrefix}_sections.subtitle,

                {$this->dbPrefix}_sections.locked,
                {$this->dbPrefix}_sections.container
            )

            VALUES(
                {$parent},
                {$weight},
                "{$title}",
                "{$subtitle}",

                {$locked},
                {$container}
            )

QUERY;
    }

    public function getPages($section_id) {
        global $Config;
        $section_id    = (int) $section_id;
        $topicsPerPage = (int) $Config->get('topicsPerPage', 'forum');

        return <<<QUERY

        SELECT
            CEIL(COUNT(id)/{$topicsPerPage})

        FROM
            {$this->dbPrefix}_topics

        WHERE
            {$this->dbPrefix}_topics.parent = {$section_id}

QUERY;
    }

    public function exists($section_id) {
        $section_id = (int) $section_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_sections.id

        FROM
            {$this->dbPrefix}_sections

        WHERE
            {$this->dbPrefix}_sections.id = {$section_id}

QUERY;
    }

    public function getParent($section_id) {
        $section_id = (int) $section_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_section_groups.parent

        FROM
            {$this->dbPrefix}_sections

        INNER JOIN {$this->dbPrefix}_section_groups
            ON {$this->dbPrefix}_sections.parent =
               {$this->dbPrefix}_section_groups.id

        WHERE
            {$this->dbPrefix}_sections.id = {$section_id}

QUERY;
    }

    public function getTitle($section_id) {
        $section_id = (int) $section_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_sections.title

        FROM
            {$this->dbPrefix}_sections

        WHERE
            {$this->dbPrefix}_sections.id = {$section_id}

QUERY;
    }

    public function getGroups($section_id) {
        $section_id = (int) $section_id;

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_section_groups.id,
            {$this->dbPrefix}_section_groups.name,
            {$this->dbPrefix}_section_groups.weight,
            {$this->dbPrefix}_section_groups.parent

        FROM
            {$this->dbPrefix}_section_groups

        WHERE
            {$this->dbPrefix}_section_groups.parent = {$section_id}
            
        ORDER BY
            {$this->dbPrefix}_section_groups.weight

QUERY;
    }

    public function getTopics($section_id, $page) {
        global $Config;
        $section_id = (int) $section_id;

        $topicsPerPage = $Config->get('topicsPerPage', 'forum');
        $offset        = ($topicsPerPage * $page) - $topicsPerPage;

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_topics.id,
            {$this->dbPrefix}_topics.type,
            {$this->dbPrefix}_topics.user_id,
            {$this->dbPrefix}_topics.title,
            {$this->dbPrefix}_topics.subtitle,

            {$this->dbPrefix}_topics.count_posts,
            {$this->dbPrefix}_topics.count_views,

            {$this->dbPrefix}_topics.last_post_id,
            {$this->dbPrefix}_topics.last_post_time,
            {$this->dbPrefix}_topics.last_user_id,
            {$this->dbPrefix}_topics.last_user_name,
            {$this->dbPrefix}_users.name

        FROM
            {$this->dbPrefix}_topics

        INNER JOIN {$this->dbPrefix}_users
            ON {$this->dbPrefix}_topics.user_id =
               {$this->dbPrefix}_users.id
        
        WHERE
            {$this->dbPrefix}_topics.parent = {$section_id}

        ORDER BY
            {$this->dbPrefix}_topics.last_post_time

        DESC

        LIMIT {$offset}, {$topicsPerPage}
        
QUERY;
    }
    
    public function increaseTopicsCount($section_id) {
        $section_id = (int) $section_id;

        return <<<QUERY

        UPDATE
            {$this->dbPrefix}_sections

        SET
            {$this->dbPrefix}_sections.count_topics =
            {$this->dbPrefix}_sections.count_topics + 1

        WHERE
            {$this->dbPrefix}_sections.id = {$section_id}

QUERY;
    }

    public function increasePostsCount($section_id) {
        $section_id = (int) $section_id;

        return <<<QUERY

        UPDATE
            {$this->dbPrefix}_sections

        SET
            {$this->dbPrefix}_sections.count_posts =
            {$this->dbPrefix}_sections.count_posts + 1

        WHERE
            {$this->dbPrefix}_sections.id = {$section_id}

QUERY;
    }

    public function getLastTopic($section_id) {
        $section_id = (int) $section_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_topics.id,
            {$this->dbPrefix}_topics.title,
            {$this->dbPrefix}_topics.user_id,
            {$this->dbPrefix}_users.name

        FROM
            {$this->dbPrefix}_topics

        INNER JOIN {$this->dbPrefix}_users
            ON {$this->dbPrefix}_users.id =
               {$this->dbPrefix}_topics.user_id

        WHERE
            {$this->dbPrefix}_topics.parent = {$section_id}

        ORDER BY
            {$this->dbPrefix}_topics.last_post_time

        DESC

        LIMIT 1

QUERY;
    }
    
    public function updateLastInfo(
            $section_id,
            $topic_id, $topic_title,
            $post_id, $post_time,
            $user_id, $user_name) {
        global $Filter;
        $section_id  = (int) $section_id;
        $topic_id    = (int) $topic_id;
        $topic_title = $Filter->SQL($topic_title);
        $post_id     = (int) $post_id;
        $post_time   = $Filter->SQL($post_time);
        $user_id     = (int) $user_id;
        $user_name   = $Filter->SQL($user_name);

        return <<<QUERY

        UPDATE
            {$this->dbPrefix}_sections

        SET
            {$this->dbPrefix}_sections.last_topic_id    = {$topic_id},
            {$this->dbPrefix}_sections.last_topic_title = "{$topic_title}",
            {$this->dbPrefix}_sections.last_post_id     = {$post_id},
            {$this->dbPrefix}_sections.last_post_time   = TIMESTAMP("{$post_time}"),
            {$this->dbPrefix}_sections.last_user_id     = {$user_id},
            {$this->dbPrefix}_sections.last_user_name   = "{$user_name}"

        WHERE
            {$this->dbPrefix}_sections.id = {$section_id}

QUERY;
    }
}
?>
