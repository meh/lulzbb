<?php
/**
* @package PHP5
* @category Send

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/send/send.class.php');

/**
* Post send class.

* @author cHoBi
*/
class Post extends Send {
    /**
    * Initialize the data and send it.

    * @param    string    $magic       The magic token. (Anti XSRF)
    * @param    int       $topic_id    The topic id where to add the post.
    * @param    string    $title       The post title.
    * @param    string    $content     The post content.
    */
    public function __construct($magic, $topic_id, $title, $content) {
        parent::__construct();

        if ($this->connected) {
            $this->output = $this->__send(array(
                'magic'    => $magic,
                'topic_id' => $topic_id,
                'title'    => $title,
                'content'  => $content
            ));
        }
        else {
            $this->output = 'Why are you here? :(';
        }
    }

    /**
    * Check the magic token and data consistency and then send it.

    * @param    array    $data    The data to send, see the constructor.
    * @access private
    */
    protected function __send($data) {
        global $Database;

        $parent   = $Database->topic->getParent($data['topic_id']);
        $topic_id = $data['topic_id'];
        $title    = $data['title'];
        $content  = $data['content'];

        if ($data['magic'] != $_SESSION[SESSION]['magic']) {
            die('LOLNO');
        }
        
        try {
            // Check for data integrity, switch to give back errors messages.
            switch ($this->__checkData($title, $content)) {
                case 'content':
                $message = new InformativeMessage("The message isn't long enough.");
                break;

                default:
                $Database->topic->post->add($topic_id, $title, $content);

                $message = new InformativeMessage(
                    'post_sent',
                    array(
                        'topic_id' => $topic_id,
                        'title'    => $title,
                        'post_id'  => 'last'
                    )
                );
        
                rm("/output/cache/sections/{$parent}-*.html");
                rm("/output/cache/topics/{$topic_id}-*.html");
                break;
            }
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }

        return $message->output();
    }

    /**
    * Check the data integrity.

    * @param    string    $title      The post's title.
    * @param    string    $content    The post's content.

    * @return    string    title | content | ok
    * @access private
    */
    private function __checkData($title, $content) {
        global $Config;
        
        $titleMinLength   = $Config->get('titleMinLength');
        $contentMinLength = $Config->get('contentMinLength');

        $scontent = trim(str_replace("\n", '', $content));

        if (strlen($title) < $titleMinLength) {
            return 'title';
        }

        if (strlen($content) < $contentMinLength) {
            return 'content';
        }

        return 'ok';
    }
}
?>
