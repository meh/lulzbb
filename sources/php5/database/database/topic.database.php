<?php
/**
* @package PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/database/topic/post.database.php');
require_once(SOURCE_PATH.'/database/query/topic.query.php');

/**
* This class is dedicated to topic stuff.

* @author cHoBi
*/
class TopicDatabase extends DatabaseBase {
    public $post;

    /**
    * In the beginning there was a mother, a father and a child. (BAWWWW, I'm bored)
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct($Database) {
        $query = new TopicQuery();
        parent::__construct($Database, $query);

        $this->post = new PostDatabase($Database);
    }
    
    /**
    * Says if the topics exists or not.

    * @param    int    $topic_id    The topic id.

    * @return    bool    True if it exists, false if not.
    */
    public function exists($topic_id) {
        $this->Database->sendQuery($this->Query->exists($topic_id));

        if ($this->Database->fetchArray()) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
    * Gets the page where the topic is.

    * @param    int    $section_id    The section's id.
    * @param    int    $topic_id      The topic's id.

    * @return    int    The page's number.
    */
    public function getPage($topic_id, $section_id = 0) {
        global $Config;

        $section_id = (int) (!empty($section_id)
                                ? $section_id
                                : $this->getParent($topic_id));
                        
        $query = $this->Database->sendQuery($this->Query->getPosition(
            $section_id, 
            $this->getLastPostTime($topic_id))
        );
        $position = mysql_fetch_row($query);
        
        return ceil($position[0]/$Config->get('elementsPerPage'));
    }

    /**
    * Gets the last post time of a topic.

    * @param    int    $topic_id    The topic's id.

    * @return    string    The last post timestamp.
    */
    public function getLastPostTime($topic_id) {
        $query = $this->Database->sendQuery($this->Query->getLastPostTime($topic_id));
        $time = mysql_fetch_row($query);

        return $time[0];
    }

    /**
    * Inserts a topic.
    
    * @param    int       $topic_type    The type of the topic.
    * @param    int       $parent        The parent where the topic is added.
    * @param    string    $title         The title of the topic.
    * @param    string    $subtitle      The subtitle of the topic.
    * @param    string    $content       The content of the topic.
    
    * @return    int    The id of the added topic.
    */
    public function add($parent, $topic_type, $title, $subtitle, $content) {
        global $User;

        if (!$this->Database->section->exists($parent)) {
            throw new lulzException('section_not_existent');
        }

        $this->Database->sendQuery($this->Query->add(
            $User->getId(),
            $User->getName('RAW'),
            $topic_type,
            $parent,
            $title,
            $subtitle
        ));
        
        $topic_id = $this->Database->misc->getLastTopic();
        $this->Database->topic->post->add($topic_id, $title, $content);
        
        $this->Database->section->increaseTopicsCount($parent);

        return $topic_id;
    }

    /**
    * Gets id and title of a topic.
    
    * @param    int    $topic_id    The topic id.

    * @return    array    (id, name)
    */
    public function getInfo($topic_id) {
        $name = $this->getTitle($topic_id);

        return array('id' => $topic_id, 'name' => $name);
    }

    /**
    * Gets the posts in a topic.
    
    * @param    int    $topic_id    The topic id.
    
    * @return    array    A post in each element.
    */
    public function getPosts($topic_id) {
        $query = $this->Database->sendQuery($this->Query->getPosts($topic_id));

        $posts = array();
        while ($post = $this->Database->fetchArray()) {
            array_push($posts, $post);
        }

        return $posts;
    }

    /**
    * Gets the topic's parent id and name.

    * @param    int    $topic_id    The topic id.
    
    * @return    int    The parent id.
    */
    public function getParent($topic_id) {
        $this->Database->sendQuery($this->Query->getParent($topic_id));
        $parent = $this->Database->fetchArray();

        return array(
            'id'   => $parent['parent']['RAW'],
            'name' => $parent['title']
        );
    }

    /**
    * Gets the navigator elements of a topic.
    
    * @param    int    $topic_id    The topic id.

    * @return    array    A parent in each element.
    */
    public function getNavigator($topic_id, $option) {
        global $Config;
        global $Filter;

        $parents = array();
        array_push($parents, $this->getInfo($topic_id));
        array_push($parents, $this->getParent($topic_id));

        $parent = $parents[1];
        while (true) {
            $parent = $this->Database->section->getParentInfo($parent['id']);
            array_push($parents, $parent);

            if ($parent['id'] == 0) {
                break;
            }
        }
        
        return array_reverse($parents);
    }

    /**
    * Gets the topic title.
    
    * @param    int    $topic_id    The id of the topic.
    
    * @return    string    The title of the topic. (RAW, HTML, POST)
    */
    public function getTitle($topic_id) {
        $this->Database->sendQuery($this->Query->getTopicTitle($topic_id));
        $result = $this->Database->fetchArray();

        if (!$result) {
            throw new lulzException('topic_not_existent');
        }

        return $result['title'];
    }

    /**
    * Gets the last post.
    
    * @param    int    $topic_id    The id of the topic.
     
    * @return    array    (post_id, post_time, user_id, user_name)
    */
    public function getLastPost($topic_id) {
        $this->Database->sendQuery($this->Query->getLastPost($topic_id));
        $last = $this->Database->fetchArray();

        return array(
            'post_id'   => $last['post_id']['RAW'],
            'post_time' => $last['time']['RAW'],
            'user_id'   => $last['id']['RAW'],
            'user_name' => $last['name']['RAW']
        );
    }
    
    /**
    * Gets the id of the last post in a topic.
    
    * @param    int    $topic_id    The topic's id where the post is taken.
    
    * @return    int    The last post id.
    */
    public function getLastPostId($topic_id) {
        $last = $this->getLastPost($topic_id);

        return $last['post_id'];
    }
    
    /**
    * Updates the last post infos of a topic.
    
    * @param    int    $topic_id    The id of the topic.
    */
    public function updateLastInfo($topic_id) {
        $last = $this->getLastPost($topic_id);

        $this->Database->sendQuery($this->Query->updateLastInfo(
             $topic_id,
             $last['post_id'],
             $last['post_time'],
             $last['user_id'],
             $last['user_name']
        ));
    }

    /**
    * Increases the posts count of a topic.

    * @param    int    $topic_id    The topic id.
    */
    public function increasePostsCount($topic_id) {
        $query = $this->Query->increasePostsCount($topic_id);
        $this->Database->sendQuery($query);
    }

    /**
    * Increases the views count of a topic.

    * @param    int    $topic_id    The topic id.
    */
    public function increaseViewsCount($topic_id) {
        $query = $this->Query->increaseViewsCount($topic_id);
        $this->Database->sendQuery($query);
    }
}
?>
