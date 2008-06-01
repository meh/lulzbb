<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/query/topic/post.query.php');
require_once(SOURCE_PATH.'/database/database.base.class.php');

/**
* This class is dedicated to post stuff.

* @author cHoBi
*/
class PostDatabase extends DatabaseBase {
    /**
    * BAWWWFEST IS HERE ;_;
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct($Database) {
        $query = new PostQuery();
        parent::__construct($Database, $query);
    }
   
    /**
    * Adds the post to a topic.
    
    * @param    int       $topic_id    The topic's id where to add the post.
    * @param    string    $title       The post title.
    * @param    string    $content     The content of the post.
    */
    public function add($topic_id, $title, $content) {
        global $User;

        if (!$this->Database->topic->exists($topic_id)) {
            throw new lulzException('topic_not_existent');
        }
        
        $parent  = $this->Database->topic->getParent($topic_id);
        $post_id = $this->Database->topic->getLastPostId($topic_id) + 1;

        $this->Database->sendQuery($this->Query->add(
            $User->getId(),
            $topic_id,
            $post_id,
            $title,
            $content
        ));

        $this->Database->topic->updateLastInfo($topic_id);
        $this->Database->topic->increasePostsCount($topic_id);
        $this->Database->section->updateLastInfo($parent['id']);
        $this->Database->section->increasePostsCount($parent['id']);
    }
    
    /**
    * Gets the infos of the last post in a topic.
    
    * @param    int    $topic_id    The topic's id where the info are taken.
    
    * @return    array    (name, time)
    */
    public function getLastInfos($topic_id) {
        $this->Database->sendQuery($this->Query->getLastInfos($topic_id));
        $infos = $this->Database->fetchArray();

        return $infos['HTML'];
    }
}
?>
