<?php
/**
* @package PHP5
* @category Send

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

require_once(SOURCE_PATH.'/send/send.class.php');

/**
* Post send class.

* @author cHoBi
*/
class Post extends Send
{
    /**
    * Initialize the data and send it.

    * @param    string    $magic       The magic token. (Anti XSRF)
    * @param    int       $topic_id    The topic id where to add the post.
    * @param    string    $title       The post title.
    * @param    string    $content     The post content.
    */
    public function __construct ($magic, $topic_id, $title, $content)
    {
        global $Config;
        parent::__construct();

        if ($this->connected || $Config->get('anonymousPosting')) {
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

    * @param    array    $data    (magic, topic_id, title, content)
    */
    protected function __send ($data)
    {
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
        
                rm("/.cache/sections/*");
                rm("/.cache/topics/{$topic_id}-*.php");
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
    private function __checkData ($title, $content)
    {
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
