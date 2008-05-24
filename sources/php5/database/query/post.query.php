<?php
/**
* @package lulzBB-PHP5
* @author  cHoBi
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class PostQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function add($topic_id, $post_id, $title, $content) {
        global $filter;
        $topic_id = (int) $topic_id;
        $post_id  = (int) $post_id;
        $user_id  = (int) $_SESSION[SESSION]['user']['id'];
        $title    = $filter->SQL($title);
        $content  = $filter->SQL($content);

        return <<<QUERY
        
        INSERT
            INTO
                {$this->dbPrefix}_posts
                           
            VALUES(
                {$topic_id},
                {$post_id},
                {$user_id},
                NOW(),
                '{$title}',
                '{$content}'
            )

QUERY;
    }
    
    public function getLast($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY

            SELECT
                MAX({$this->dbPrefix}_posts.post_id)
    
            FROM
                {$this->dbPrefix}_posts
    
            WHERE
                {$this->dbPrefix}_posts.topic_id = {$topic_id}
            
QUERY;
    }
    
    public function getLastInfos($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY
        
            SELECT
                {$this->dbPrefix}_users.name,
                {$this->dbPrefix}_posts.time
            
            FROM
                {$this->dbPrefix}_posts
                
            INNER JOIN {$this->dbPrefix}_users
                ON {$this->dbPrefix}_posts.user_id =
                   {$this->dbPrefix}_users.id
                
            ORDER BY
                {$this->dbPrefix}_posts.time
                
            DESC
            
            LIMIT 1

QUERY;
    }
}
?>
