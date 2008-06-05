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
class TopicQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function exists($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_topics.id

        FROM
            {$this->dbPrefix}_topics

        WHERE
            {$this->dbPrefix}_topics.id = {$topic_id}

QUERY;
    }

    public function getInfos($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY

        SELECT
            *

        FROM
            {$this->dbPrefix}_topics

        WHERE
            {$this->dbPrefix}_topics.id = {$topic_id}

QUERY;
    }

    public function getPosition($section_id, $last_post_time) {
        global $Filter;
        $section_id     = (int) $section_id;
        $last_post_time = $Filter->SQL($last_post_time);

        return <<<QUERY

        SELECT
            COUNT({$this->dbPrefix}_topics.id)

        FROM
            {$this->dbPrefix}_topics

        WHERE
            {$this->dbPrefix}_topics.parent = {$section_id}
          AND
            {$this->dbPrefix}_topics.last_post_time >= TIMESTAMP("{$last_post_time}")

        ORDER BY
            {$this->dbPrefix}_topics.last_post_time

        DESC

QUERY;
    }

    public function getLastPostTime($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_topics.last_post_time

        FROM
            {$this->dbPrefix}_topics

        WHERE
            {$this->dbPrefix}_topics.id = {$topic_id}

QUERY;
    }

    public function getPosts($topic_id) {
        $topic_id = (int) $topic_id;
        
        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_topic_posts.user_id,
            {$this->dbPrefix}_topic_posts.post_id,
            {$this->dbPrefix}_topic_posts.time,
            {$this->dbPrefix}_topic_posts.title,
            {$this->dbPrefix}_topic_posts.content,
            {$this->dbPrefix}_users.name,
            {$this->dbPrefix}_users.signature

        FROM
            {$this->dbPrefix}_topic_posts
        
        INNER JOIN {$this->dbPrefix}_users
            ON
                {$this->dbPrefix}_topic_posts.topic_id =
                {$topic_id}
              AND
                {$this->dbPrefix}_topic_posts.user_id =
                {$this->dbPrefix}_users.id
            
        ORDER BY
            {$this->dbPrefix}_topic_posts.post_id

QUERY;
    }

    public function add($user_id, $user_name, $topic_type, $parent, $title, $subtitle) {
        global $Filter;
        $user_id    = (int) $user_id;
        $topic_type = (int) $topic_type;
        $parent     = (int) $parent;
        $title      = $Filter->SQL($title);
        $subtitle   = '"'.$Filter->SQL_HTMLclean($subtitle).'"';

        $last_post_id   = (int) 1;
        $last_user_id   = (int) $user_id;
        $last_user_name = $Filter->SQL($user_name);

        if (preg_match('/^""$/', $subtitle)) {
            $subtitle = 'NULL';
        }
        
        return <<<QUERY
        
        INSERT
            INTO {$this->dbPrefix}_topics
            
            VALUES(
                NULL,
                {$topic_type},
                {$parent},
                {$user_id},
                "{$title}",
                {$subtitle},

                0,
                0,

                {$last_post_id},
                NOW(),
                {$last_user_id},
                "{$last_user_name}"
            )

QUERY;
    }
    
    public function getParent($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_topics.parent,
            {$this->dbPrefix}_sections.title

        FROM
            {$this->dbPrefix}_topics

        INNER JOIN {$this->dbPrefix}_sections
            ON {$this->dbPrefix}_sections.id =
               {$this->dbPrefix}_topics.parent

        WHERE
            {$this->dbPrefix}_topics.id = {$topic_id}

QUERY;
    }
    
    public function getTopicTitle($topic_id) {
        global $Filter;
        $topic_id = (int) $topic_id;

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_topics.title

        FROM
            {$this->dbPrefix}_topics

        WHERE
            {$this->dbPrefix}_topics.id = {$topic_id}

QUERY;
    }

    public function getLastPost($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_topic_posts.post_id,
            {$this->dbPrefix}_topic_posts.time,
            {$this->dbPrefix}_users.id,
            {$this->dbPrefix}_users.name

        FROM
            {$this->dbPrefix}_topic_posts

        INNER JOIN {$this->dbPrefix}_users
            ON {$this->dbPrefix}_topic_posts.user_id =
               {$this->dbPrefix}_users.id 

        WHERE
            {$this->dbPrefix}_topic_posts.topic_id = {$topic_id}

        ORDER BY
            {$this->dbPrefix}_topic_posts.post_id

        DESC

        LIMIT 1

QUERY;
    }
    
    public function updateLastInfo($topic_id, $post_id, $post_time, $user_id, $user_name) {
        global $Filter;
        $topic_id  = (int) $topic_id;
        $post_id   = (int) $post_id;
        $post_time = $Filter->SQL($post_time);
        $user_id   = (int) $user_id;
        $user_name = $Filter->SQL($user_name);

        return <<<QUERY

        UPDATE
            {$this->dbPrefix}_topics

        SET
            {$this->dbPrefix}_topics.last_post_id   = {$post_id},
            {$this->dbPrefix}_topics.last_post_time = TIMESTAMP("{$post_time}"),
            {$this->dbPrefix}_topics.last_user_id   = {$user_id},
            {$this->dbPrefix}_topics.last_user_name = "{$user_name}"

        WHERE
            {$this->dbPrefix}_topics.id = {$topic_id}

QUERY;
    }

    public function increasePostsCount($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY

        UPDATE
            {$this->dbPrefix}_topics

        SET
            {$this->dbPrefix}_topics.count_posts
                = {$this->dbPrefix}_topics.count_posts + 1

        WHERE
            {$this->dbPrefix}_topics.id = {$topic_id}

QUERY;
    }

    public function increaseViewsCount($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY

        UPDATE
            {$this->dbPrefix}_topics

        SET
            {$this->dbPrefix}_topics.count_views
                = {$this->dbPrefix}_topics.count_views + 1

        WHERE
            {$this->dbPrefix}_topics.id = {$topic_id}

QUERY;
    }
}
?>
