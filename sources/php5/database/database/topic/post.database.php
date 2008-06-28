<?php
/**
* @package PHP5
* @category Database

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

require_once(SOURCE_PATH.'/database/query/topic/post.query.php');
require_once(SOURCE_PATH.'/database/database.base.class.php');

/**
* This class is dedicated to post stuff.

* @author cHoBi
*/
class PostDatabase extends DatabaseBase
{
    /**
    * BAWWWFEST IS HERE ;_;
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct ($Database)
    {
        $query = new PostQuery();
        parent::__construct($Database, $query);
    }
   
    /**
    * Adds the post to a topic.
    
    * @param    int       $topic_id    The topic's id where to add the post.
    * @param    string    $title       The post title.
    * @param    string    $content     The content of the post.
    */
    public function add ($topic_id, $title, $content, $nick = '')
    {
        if (!$this->Database->topic->exists($topic_id)) {
            throw new lulzException('topic_not_existent');
        }
        
        $parent  = $this->Database->topic->getParent($topic_id);
        $post_id = $this->Database->topic->getLastPostId($topic_id) + 1;

        if (isset($_SESSION[SESSION]['user'])) {
            $User = $_SESSION[SESSION]['user'];

            $this->Database->sendQuery($this->Query->addLogged(
                $User->getId(),
                $topic_id,
                $post_id,
                $User->getLulzCode(),
                $title,
                $content
            ));
        }
        else {
            $this->Database->sendQuery($this->Query->addAnonymous(
                $User->getId(),
                (empty($nick) ? $Config->get('anonymousNick') : $nick),
                $topic_id,
                $post_id,
                'true',
                $title,
                $content
            ));
        }

        $this->Database->topic->updateLastInfo($topic_id);
        $this->Database->topic->increasePostsCount($topic_id);
        $this->Database->section->updateLastInfo($parent['id']);
        $this->Database->section->increasePostsCount($parent['id']);
    }
    
    /**
    * Gets the infos of the last post in a topic.
    
    * @param    int    $topic_id    The topic's id where the info are taken.
    
    * @return    array    (name, time)
    */
    public function getLastInfos ($topic_id)
    {
        $this->Database->sendQuery($this->Query->getLastInfos($topic_id));
        $infos = $this->Database->fetchArray();

        return $infos['HTML'];
    }
}
?>
