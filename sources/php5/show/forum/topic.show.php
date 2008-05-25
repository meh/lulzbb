<?php
/**
* @package lulzBB-PHP5
* @category Show

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/show/show.class.php');
require_once(SOURCE_PATH.'/template/forum/topic.template.php');
require_once(SOURCE_PATH.'/database/database.class.php');

/**
* Topic show class.

* @author cHoBi
*/
class TopicShow extends Show {
    private $Database;

    /**
    * Get the post and show them in the template.

    * @param    int    $parent      The topic's parent.
    * @param    int    $topic_id    The topic id.
    * @param    int    $post_id     The post id.
    */
    public function __construct($parent, $topic_id, $post_id) {
        parent::__construct();
        global $Database;

        $this->data['parent']    = (int) $parent;
        $this->data['topic_id']  = (int) $topic_id;
        $this->data['post_id']   = (int) $post_id;

        $this->__update();
    }

    /**
    * Get the posts and show error in case they happen.
    * @access private
    */
    protected function __update() {
        global $Database;

        try {
            if ($Database->topic->exists($this->data['topic_id'])) {
                $posts = $Database->topic->getPosts($this->data['topic_id']);
            }
            else {
                die("The topic doesn't exist.");
            }
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }

        $template = new TopicTemplate(
            $this->magic,
            $this->data['topic_id'],
            $this->data['post_id'],
            $posts
        );

        $this->output = $template->output();
    }
}
?>
