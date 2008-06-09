<?php
/**
* @package PHP5
* @category Send

* @license AGPLv3
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
* Topic send class.

* @author cHoBi
*/
class Topic extends Send {
    /**
    * Initialize the data and send it.

    * @param    string    $magic       The magic token. (Anti XSRF)
    * @param    int       $parent      The section where to add the topic.
    * @param    int       $type        The topic type.
    * @param    string    $title       The topic title.
    * @param    string    $subtitle    The topic subtitle.
    * @param    string    $content     The topic content.
    */
    public function __construct($magic, $parent, $type, $title, $subtitle, $content) {
        parent::__construct();
        
        if ($this->connected) {
            $this->output = $this->__send(array(
                'magic'    => $magic,
                'parent'   => $parent,
                'type'     => $type,
                'title'    => $title,
                'subtitle' => $subtitle,
                'content'  => $content
            ));
        }
        else {
            $this->output = 'Why are you here? :(';
        }
    }

    /**
    * Check the magic token and data consistencty and then send it.

    * @param    array    $data    The data to send, see the constructor.
    * @access private
    */
    protected function __send($data) {
        global $Database;
        $magic    = $data['magic'];
        $parent   = $data['parent'];
        $type     = $data['type'];
        $title    = $data['title'];
        $subtitle = $data['subtitle'];
        $content  = $data['content'];

        if ($magic != $this->magic) {
            die('LOLNO');
        }

        try {
            // Check for data integrity, switch to give back errors messages.
            switch ($this->__checkData($parent, $title, $subtitle, $content)) {
                case 'parent':
                $message = new InformativeMessage(
                      'Where should i add the topic?<br/><br/>'
                    . 'Are you trying to hax me? :('
                );
                break;

                case 'title':
                $message = new InformativeMessage("The topic title isn't long enough.");
                break;

                case 'content':
                $message = new InformativeMessage("The message isn't long enough.");
                break;

                default:
                $topic_id = $Database->topic->add($parent, $type, $title, $subtitle, $content);
                $message = new InformativeMessage('topic_sent', array('topic_id' => $topic_id));
                        
                rm("/.cache/sections/*");
                rm("/.cache/misc/pages.section.{$parent}.txt");
                rm("/.cache/misc/page.topic.*.txt");
                break;
            }
        }
        catch (lulzException $e) {
            return $e->getMessage();
        }


        return $message->output();
    }

    /**
    * Check the data integrity.

    * @param    int       $parent      The parent.
    * @param    string    $title       The topic's title.
    * @param    string    $subtitle    The topic's subtitle.
    * @param    string    $content     The topic's content.

    * @return    string    parent | title | content | ok
    * @access private
    */
    private function __checkData($parent, $title, $subtitle, $content) {
        global $Config;
        $titleMinLength   = $Config->get('titleMinLength');
        $contentMinLength = $Config->get('contentMinLength');

        $title   = trim($title);
        $content = str_replace("\n", '', trim($content));

        if (empty($parent)) {
            return 'parent';
        }

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
