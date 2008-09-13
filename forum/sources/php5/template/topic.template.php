<?php
/**
* @package PHP5
* @category Template

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

require_once(M_SOURCES_PATH.'/template/template.class.php');
require_once(M_SOURCES_PATH.'/template/forms/send-post.template.php');

/**
* Topic template class.

* @author cHoBi
*/
class TopicTemplate extends Template
{
    private $topic_id;
    private $post_id;
    private $page;
    private $pagesNumber;
    private $posts;
    private $title;

    /**
    * Create the topic template.

    * @param    string    $magic       The magic token. (Anti XSRF)
    * @param    int       $topic_id    The topic id.
    * @param    int       $post_id     The post id.
    * @param    array     $posts       The posts data.
    */
    public function __construct ($topic_id, $page, $post_id, $posts)
    {
        parent::__construct('forum/topic.tpl');
        global $Database;

        $this->topic_id    = (int) $topic_id;
        $this->page        = (int) $page;
        $this->pagesNumber = $Database->topic->getPages($topic_id);
        $this->post_id     = isset($post_id) ? $post_id : '';
        $this->posts       = $posts;
        $this->title       = $Database->topic->getTitle($topic_id);

        $this->__parse();
    }

    /**
    * Parse the template and get loops.
    * @access private
    */
    private function __parse ()
    {
        $text = $this->output();
        $text = $this->__loops($text);

        $posts = '';
        foreach ($this->posts as $n => $post) {
            if ($n == count($this->posts)-1) {
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
            $this->post_id,
            $text
        );

        $pager = new PagerTemplate('topic', $this->page, $this->pagesNumber);
        $text = preg_replace(
            '|<%PAGER%>|i',
            $pager->output(),
            $text
        );

        $text = preg_replace(
            '|<%TOPIC-URL%>|i',
            "?forum&topic&id={$this->topic_id}",
            $text
        );

        $this->parsed = $this->__common($text);
    }

    /**
    * Create the loops.
    * @access private
    */
    private function __loops ($text)
    {
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
    private function __post ($post, $type = '')
    {
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
    private function __signature ($signature)
    {
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

    /**
    * Common tags.
    * @access private
    */
    private function __common ($text)
    {
        $text = preg_replace(
            '|<%TOPIC-ID%>|i',
            $this->topic_id,
            $text
        );

        return $text;
    }
}
?>
