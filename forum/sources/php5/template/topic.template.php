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

include_once(SOURCES_PATH.'/template/template.class.php');
include_once($M_SOURCES_PATH.'/template/forms/send-post.template.php');
include_once($M_SOURCES_PATH.'/template/pager.template.php');

/**
* Topic template class.

* @author cHoBi
*/
class TopicTemplate extends Template
{
    private $topic;
    private $page;
    private $post;

    /**
    * Create the topic template.

    * @param    string    $magic       The magic token. (Anti XSRF)
    * @param    int       $topic       The topic id.
    * @param    int       $post_id     The post id.
    * @param    array     $posts       The posts data.
    */
    public function __construct ($topic, $page, $post)
    {
        parent::__construct('forum/topic.tpl');
        global $Database;

        $this->topic = $topic;
        $this->page  = $page;
        $this->post  = $post;

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
        foreach ($this->post['posts'] as $n => $post) {
            if ($n == count($this->post['posts'])-1) {
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
            $this->post['id'],
            $text
        );

        $pager = new PagerTemplate('topic', $this->page['page'], $this->page['number']);
        $text = preg_replace(
            '|<%PAGER%>|i',
            $pager->output(),
            $text
        );

        $text = preg_replace(
            '|<%TOPIC-URL%>|i',
            "?forum&topic&id={$this->topic['id']}",
            $text
        );

        $text = $this->__formPost($text);

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
            $this->topic['id'],
            $text
        );

        return $text;
    }
    
    private function __formPost ($text)
    {
        global $Config;

        if ($this->connected || $Config->get('anonymousPosting')) {
            $form = new PostFormTemplate(
                $this->magic,
                $this->topic['id'],
                $this->topic['title']['RAW']
            );
            $form = $form->output();
        }
        else {
            $form = '';
        }
        
        $text = preg_replace(
            '|<%SEND-POST-FORM%>|i',
            $form,
            $text
        );
        
        return $text;
    }
}
?>
