<?php
/**
* @package lulzBB-PHP5
* @category Template

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/template/template.class.php');
require_once(SOURCE_PATH.'/template/forms/send-post.template.php');

/**
* Topic template class.

* @author cHoBi
*/
class TopicTemplate extends Template {
    /**
    * Create the topic template.

    * @param    string    $magic       The magic token. (Anti XSRF)
    * @param    int       $topic_id    The topic id.
    * @param    int       $post_id     The post id.
    * @param    array     $posts       The posts data.
    */
    public function __construct($magic, $topic_id, $post_id, $posts) {
        parent::__construct('forum/topic.tpl');
        global $Database;
    
        $this->data['posts']     = $posts;
        $this->data['magic']     = $magic;
        $this->data['title']     = $Database->topic->getTitle($topic_id);
        $this->data['topic_id']  = (int) $topic_id;
        $this->data['post_id']   = isset($post_id) ? $post_id : '';

        $this->__parse();
    }

    /**
    * Parse the template and get loops.
    * @access private
    */
    private function __parse() {
        $text = $this->output();
        $text = $this->__loops($text);

        $posts = '';
        foreach ($this->data['posts'] as $n => $post) {
            if ($n == count($this->data['posts'])-1) {
                $posts .= $this->__post($post, 'last');
            }
            else {
                $posts .= $this->__post($post);
            }
        }

        $text = preg_replace(
            '|<%LOOP-POST%>|i',
            $posts,
            $text
        );

        $text = preg_replace(
            '|<%POST-ID%>|i',
            $this->data['post_id'],
            $text
        );

        $this->parsed = $text;
    }

    /**
    * Create the loops.
    * @access private
    */
    private function __loops($text) {
        if (preg_match_all(
                '|<Post>(.+?)?</Post>|ims',
                $text,
                $post) != 1) {
            die('The template has an error. (There MUST be 1 Post loop)');
        }
        $this->data['template']['post'] = $post[1][0];

        if (preg_match_all(
                '|<Post>.+?<Signature>(.+?)?</Signature>.+?</Post>|ims',
                $text,
                $signature) != 1) {
            die('The template has an error. (There MUST be 1 Signature loop)');
        }
        $this->data['template']['signature'] = $signature[1][0];

        $this->data['template']['post']
            = preg_replace(
                '|<Signature>(.+?)?</Signature>|ims',
                '<%USER-SIGNATURE%>',
                $this->data['template']['post']
        );
        $text
            = preg_replace(
                '|<Post>(.+?)?</Post>|ims',
                '<%LOOP-POST%>',
                $text
        );

        return $text;
    }

    /**
    * Parse the post
    * @access private
    */
    private function __post($post, $type = '') {
        $text = $this->data['template']['post'];
        
        if ($type == 'last') {
            $text = preg_replace(
                '|<%POST-ID%>|i',
                'last',
                $text
            );
        }
        else {
            $text = preg_replace(
                '|<%POST-ID%>|i',
                $post['post_id']['HTML'],
                $text
            ); 
        }

        $text = preg_replace(
            '|<%USER-NICK%>|i',
            $post['name']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%POST-TIME%>|i',
            $post['time']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%POST-TITLE%>|i',
            $post['title']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%POST-CONTENT%>|i',
            nl2br($post['content']['HTML']),
            $text
        );

        $text = preg_replace(
            '|<%USER-SIGNATURE%>|i',
            $this->__signature($post['signature']['HTML']),
            $text
        );

        return $text;
    }

    /**
    * Parse the signature.
    * @access private
    */
    private function __signature($signature) {
        $text = $this->data['template']['signature'];
        
        if (empty($signature)) {
            $text = '';
        }
        else {
            $text = preg_replace(
                '|<%USER-SIGNATURE%>|i',
                $signature,
                $text
            );
        }

        return $text;
    }
}
?>
