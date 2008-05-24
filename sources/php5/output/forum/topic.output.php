<?php
/**
* @package lulzBB-PHP5
* @category Output

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/output/output.class.php');
require_once(SOURCE_PATH.'/cache/forum/topic.cache.php');
require_once(SOURCE_PATH.'/show/forum/topic.show.php');
require_once(SOURCE_PATH.'/database/database.class.php');

/**
* Topic output class.

* @author cHoBi
*/
class Topic extends Output {
    private $database;

    /**
    * Get the cache or the data from the db.

    * @param    int    $topic_id    The topic id.
    * @param    int    $post_id     The post id.
    */
    public function __construct($topic_id, $post_id) {
        if ($topic_id < 1) {
            die('LOLFAIL');
        }
    
        parent::__construct();
        global $database;
        $parent = $database->topic->getParent($topic_id);

        $cache = new TopicCache($parent['id'], $topic_id);
        if (!$cache->isCached()) {
            $topic = new TopicShow($parent['id'], $topic_id, $post_id);
            $cache->put($topic->output());
        }

        if (isCached('sections', $parent['id'])) {
            $cache->updateViews();
        }
        else {
            $database->topic->increaseViewsCount($topic_id);
        }
        
        $this->output = $this->__formPost($cache->get(), $topic_id);
    }
    
    /**
    * Get the send post form.
    * @access private
    */
    private function __formPost($output, $topic_id) {
        global $database;
        $title = $database->topic->getTitle($topic_id);

        if ($this->connected) {
            $form = new PostFormTemplate(
                $this->magic,
                $topic_id,
                $title['RAW']
            );
            $form = $form->output();
        }
        else {
            $form = '';
        }
        
        $output = preg_replace(
            '|<%SEND-POST-FORM%>|i',
            $form,
            $output
        );
        
        return $output;
    }
}
?>
