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
class PostQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function add($user_id, $topic_id, $post_id, $lulzcode, $title, $content) {
        global $Filter;
        $user_id  = (int) $user_id;
        $topic_id = (int) $topic_id;
        $post_id  = (int) $post_id;
        $lulzcode = ($lulzcode) ? 'TRUE' : 'FALSE';
        $title    = $Filter->SQL($title);
        $content  = $Filter->SQL($content);

        return <<<QUERY
        
        INSERT
            INTO
                {$this->dbPrefix}_topic_posts
                           
            VALUES(
                {$topic_id},
                {$post_id},
                {$user_id},
                {$lulzcode},
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
                MAX({$this->dbPrefix}_topic_posts.post_id)
    
            FROM
                {$this->dbPrefix}_topic_posts
    
            WHERE
                {$this->dbPrefix}_topic_posts.topic_id = {$topic_id}
            
QUERY;
    }
    
    public function getLastInfos($topic_id) {
        $topic_id = (int) $topic_id;

        return <<<QUERY
        
            SELECT
                {$this->dbPrefix}_users.name,
                {$this->dbPrefix}_topic_posts.time
            
            FROM
                {$this->dbPrefix}_topic_posts
                
            INNER JOIN {$this->dbPrefix}_users
                ON {$this->dbPrefix}_topic_posts.user_id =
                   {$this->dbPrefix}_users.id
                
            ORDER BY
                {$this->dbPrefix}_topic_posts.time
                
            DESC
            
            LIMIT 1

QUERY;
    }
}
?>
