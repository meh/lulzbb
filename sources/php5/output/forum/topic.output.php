<?php
/**
* @package PHP5
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
    /**
    * Get the cache or the data from the db.

    * @param    int    $topic_id    The topic id.
    * @param    int    $post_id     The post id.
    */
    public function __construct($topic_id, $topic_page, $post_id) {
        if ($topic_id < 1) {
            die('LOLFAIL');
        }
        parent::__construct();
        global $Database;

        if (!isset($topic_page)) {
            $topic_page = 1;
        }

        try {
            $topic_id   = (int) $topic_id;
            $topic_page = (int) $topic_page;
            $post_id    = (int) $post_id;

            $infos = $Database->topic->getInfos($topic_id);

            $cache = new TopicCache($infos['parent']['RAW'], $topic_id, $topic_page);
            if (!$cache->isCached()) {
                $topic = new TopicShow($infos['parent']['RAW'], $topic_id, $post_id);
                $cache->put($topic->output());
            }
            else {
                $cache->updateViews();
            }
        
            $this->output = $this->__formPost($cache->get(), $topic_id, $infos['title']);
        }
        catch (lulzExceptions $e) {
            die($e->getMessage());
        }
    }
    
    /**
    * Get the send post form.
    * @access private
    */
    private function __formPost($output, $topic_id, $topic_title) {
        if ($this->connected) {
            $form = new PostFormTemplate(
                $this->magic,
                $topic_id,
                $topic_title['RAW']
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
