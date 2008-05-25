<?php
/**
* @package lulzBB-PHP5
* @category Query

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class SectionQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function exists($section_id) {
        $section_id = (int) $section_id;

        return <<<QUERY

        SELECT
            {$this->dbPrefix}_sections.type

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
            {$this->dbPrefix}_sections.parent

        FROM
            {$this->dbPrefix}_sections

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

    public function getSections($section_id) {
        $section_id = (int) $section_id;

        return <<<QUERY
        
        SELECT
            {$this->dbPrefix}_sections.id,
            {$this->dbPrefix}_sections.type,
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
            {$this->dbPrefix}_sections.parent = {$section_id}
            
        ORDER BY
            {$this->dbPrefix}_sections.weight

QUERY;
    }

    public function getTopics($section_id) {
        $section_id = (int) $section_id;

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
