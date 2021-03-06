<?php
/**
* @package Core-PHP5
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

/**
* Informative message template.

* @author cHoBi
*/
class InformativeMessageTemplate extends Template
{
    private $type;
    private $message;

    /**
    * Initialize the message and the data.

    * @param    string    $type       The message type if there's a default message.
    * @param    array     $data       The data if it's needed by the message.
    * @param    string    $message    The message that's being showed.
    */
    public function __construct ($type, $data, $message)
    {
        parent::__construct('misc/informative-message.tpl');

        $this->type    = $type;
        $this->data    = $data;
        $this->message = $message;

        $this->__parse();
    }

    /**
    * Usual initalizations, but switching through message types to fill the data.
    * @access private
    */
    private function __parse ()
    {
        global $Filter;

        $text = $this->output();

        switch ($this->type) {
            case 'registration_successful':
            $username = $Filter->POST_SQLclean($this->data['username']);
            $password = $Filter->POST_SQLclean($this->data['password']);

            $this->message = preg_replace(
                '|<%REGISTRATION-USERNAME%>|i',
                $username,
                $this->message
            );
            $this->message = preg_replace(
                '|<%REGISTRATION-PASSWORD%>|i',
                $password,
                $this->message
            );
            break;

            case 'topic_sent':
            $topic_id = (int) $this->data['topic_id'];

            $this->message = preg_replace(
                '|<%TOPIC-ID%>|i',
                $topic_id,
                $this->message
            );
            break;

            case 'post_sent':
            $topic_id = (int) $this->data['topic_id'];
            $post_id  = (int) $this->data['post_id'];

            $this->message = preg_replace(
                '|<%TOPIC-ID%>|i',
                $topic_id,
                $this->message
            );
            $this->message = preg_replace(
                '|<%POST-ID%>|i',
                $post_id,
                $this->message
            );
            break;
        }
        

        $text = preg_replace(
            '|<%INFORMATIVE-MESSAGE%>|i',
            $this->message,
            $text
        );

        $this->parsed = $text;
    }
}
?>
