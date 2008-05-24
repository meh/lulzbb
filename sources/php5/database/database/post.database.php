<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/query/post.query.php');
require_once(SOURCE_PATH.'/database/database.base.class.php');

/**
* This class is dedicated to post stuff.

* @author cHoBi
*/
class PostDatabase extends DatabaseBase {
    /**
    * BAWWWFEST IS HERE ;_;
    
    * @param    object    $database   The Database object, recursive object is recursive.
    */
    public function __construct($database) {
        $query = new PostQuery();
        parent::__construct($database, $query);
    }
   
    /**
    * Adds the post to a topic.
    
    * @param    int       $topic_id    The topic's id where to add the post.
    * @param    string    $title       The post title.
    * @param    string    $content     The content of the post.
    */
    public function add($topic_id, $title, $content) {
        if (!$this->database->topic->exists($topic_id)) {
            throw new lulzException('topic_not_existent');
        }
        
        $parent  = $this->database->topic->getParent($topic_id);
        $post_id = $this->database->topic->getLastPostId($topic_id) + 1;

        $this->database->sendQuery($this->Query->add(
            $topic_id,
            $post_id,
            $title,
            $content
        ));

        $this->database->topic->updateLastInfo($topic_id);
        $this->database->topic->increasePostsCount($topic_id);
        $this->database->section->updateLastInfo($parent['id']);
        $this->database->section->increasePostsCount($parent['id']);
    }
    
    /**
    * Gets the infos of the last post in a topic.
    
    * @param    int    $topic_id    The topic's id where the info are taken.
    
    * @return    array    (name, time)
    */
    public function getLastInfos($topic_id) {
        $this->database->sendQuery($this->Query->getLastInfos($topic_id));
        $infos = $this->database->fetchArray();

        return $infos['HTML'];
    }
}
?>
