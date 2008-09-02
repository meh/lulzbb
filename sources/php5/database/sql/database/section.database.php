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

require_once(SOURCE_PATH.'/database/sql/database.base.class.php');
require_once(SOURCE_PATH.'/database/sql/database/section/group.database.php');
require_once(SOURCE_PATH.'/database/sql/query/section.query.php');

/**
* This class is dedicated to section stuff.

* @author cHoBi
*/
class SectionDatabase extends DatabaseBase
{
    public $group;

    /**
    * Dolphins, they're never related.
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct ($Database)
    {
        $query = new SectionQuery();
        parent::__construct($Database, $query);

        $this->group = new SectionGroupDatabase($Database);
    }

    /**
    * Adds a section to a group.

    * @param    int       $group       The group id where to put the section.
    * @param    int       $weight      The section's weight.
    * @param    string    $title       The section's title.
    * @param    string    $subtitle    The section's subtitle.
    */
    public function add ($group_id, $weight, $title, $subtitle)
    {
        if (empty($weight)) {
            $weight = $this->group->heaviest($group_id)+1;
        }

        $this->Database->sendQuery($this->Query->add($group_id, $weight, $title, $subtitle));
    }

    /**
    * Says if the section exists or not.

    * @param    int    $section_id    The section id.

    * @return    bool    True if it exists, false if not.
    */
    public function exists ($section_id)
    {
        if ($section_id == 0) {
            return true;
        }

        $this->Database->sendQuery($this->Query->exists($section_id));
        $section = $this->Database->fetchArray();

        if (empty($section)) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
    * Gets the section's number of pages.

    * @param    int    $section_id    The section id.

    * @return    int    The number of pages.
    */
    public function getPages ($section_id)
    {
        $query = $this->Database->sendQuery($this->Query->getPages($section_id));
        $pages = mysql_fetch_row($query);

        if ($pages[0] < 1) {
            $pages = 1;
        }
        else {
            $pages = $pages[0];
        }

        return $pages;
    }

    /**
    * Gets id and title of a section.

    * @param    int    $section_id    The section id.

    * @return    array    (id, name)
    */
    public function getInfo ($section_id)
    {
        $name = $this->getTitle($section_id);

        return array('id' => (int) $section_id, 'name' => $name);
    }

    /**
    * Gets the section's parent id.

    * @param    int    $section_id    The section id.

    * @return    int    The parent id.
    */
    public function getParent ($section_id)
    {
        $this->Database->sendQuery($this->Query->getParent($section_id));
        $parent = $this->Database->fetchArray();

        return $parent['parent']['RAW'];
    }

    /**
    * Gets the section's parent info.
    
    * @param    int    $section_id    The section id.
    
    * @return    int    The section's parent.
    */
    public function getParentInfo ($section_id)
    {
        $parent = $this->getParent($section_id);

        return array(
            'id'   => $parent,
            'name' => $this->getTitle($parent)
        );
    }

    /**
    * Gets the section's parents.

    * @param    int    $section_id    The section id.

    * @return    array    The parents' id.
    */
    public function getParents ($section_id)
    {
        $parents = array();

        $parent = $this->getParent($section_id);

        while (true) {
            array_push($parents, $parent);

            if ($parent == 0) {
                break;
            }

            $parent = $this->getParent($parent);
        }

        return $parents;
    }

    /**
    * Get the navigator, actual section and all the parents
    
    * @param    int    $section_id    The section id.
    * @param    int    $option        The option in case of New topic and such.

    * @return    array    A parent in each element.
    */
    public function getNavigator ($section_id, $option)
    {
        global $Config;
        global $Filter;

        $parents = array();
        
        // This variable is used if an option is enabled to check
        // if the root is already added (eg. child of the root)
        $offset = 0;
        switch ($option) {
            case -10:
            array_push($parents, array('id' => -10, 'name' => array('HTML' => 'New Topic')));
            $offset = 1;
            break;
        }

        array_push($parents, $this->getInfo($section_id));
        if ($section_id == 0) {
            return $parents;
        }
        
        $parent = $parents[$offset];
        while (true) {
            $parent = $this->getParentInfo($parent['id']);
            array_push($parents, $parent);

            if ($parent['id'] == 0) {
                break;
            }
        }

        return array_reverse($parents);
    }

    /**
    * Gets the section's title.
    
    * @param    int    $section_id    The section id.
    
    * @return    string    The section title. (RAW, HTML, POST)
    */
    public function getTitle ($section_id)
    {
        global $Config;
        global $Filter;

        if ($section_id == 0) {
            $forumName = $Config->get('forumName');

            $result['title']['RAW']  = $forumName;
            $result['title']['HTML'] = $Filter->HTML($forumName);
            $result['title']['POST'] = $Filter->POST($forumName);
        }
        else {
            $this->Database->sendQuery($this->Query->getTitle($section_id));
            $result = $this->Database->fetchArray();

            if (!$result) {
                throw new lulzException('section_not_existent');
            }
        }

        return $result['title'];
    }
    
    /**
    * Gets the subsections in a section.
    
    * @param    int    $section_id    The section id, also, cocks.
    
    * @return    array    A section in each element :D
    */
    public function getGroups ($section_id)
    {
        $this->Database->sendQuery($this->Query->getGroups($section_id));

        $groups = array();
        while ($group = $this->Database->fetchArray()) {
            array_push($groups, $group);
        }

        return $groups;
    }

    /**
    * Gets the topics of a section.
    
    * @param    int    $section_id    The section id, and suddenly FLYING DICKS EVERYWHERE.
    
    * @return    array    A topic in each element >:3
    */
    public function getTopics ($section_id, $page)
    {
        $this->Database->sendQuery($this->Query->getTopics($section_id, $page));

        $topics = array();
        while ($topic = $this->Database->fetchArray()) {
            array_push($topics, $topic);
        }

        return $topics;
    }

    /**
    * Increases the topics count of the section.
    
    * @param    int    $section_id    The section id.
    */
    public function increaseTopicsCount ($section_id)
    {
        $this->Database->sendQuery($this->Query->increaseTopicsCount($section_id));

        foreach ($this->getParents($section_id) as $section) {
            if ($section == 0) {
                break;
            }

            $this->Database->sendQuery($this->Query->increaseTopicsCount($section));
        }
    }

    /**
    * Increases the posts count of the section.
    
    * @param    int    $section_id    The section id.
    */
    public function increasePostsCount ($section_id)
    {
        $this->Database->sendQuery($this->Query->increasePostsCount($section_id));

        foreach ($this->getParents($section_id) as $section) {
            if ($section == 0) {
                break;
            }

            $this->Database->sendQuery($this->Query->increasePostsCount($section));
        }
    }
    
    /**
    * Gets the last topic of a section.
    
    * @param    int    $section_id    The section id.
    
    * @return    array    (topic_id, topic_title, user_id, user_name)
    */
    public function getLastTopic ($section_id)
    {
        $this->Database->sendQuery($this->Query->getLastTopic($section_id));
        $last = $this->Database->fetchArray();

        return array(
            'topic_id'    => $last['id']['RAW'],
            'topic_title' => $last['title']['RAW'],
            'user_id'     => $last['user_id']['RAW'],
            'user_name'   => $last['name']['RAW']
        );
    }

    /**
    * Gets last post of a section.
    
    * @param    int    $section_id    The section id.
    
    * @return    array    (post_id, post_time, user_id, user_name)
    */
    public function getLastPost ($section_id)
    {
        $topic = $this->getLastTopic($section_id);
        $post  = $this->Database->topic->getLastPost($topic['topic_id']);

        return $post;
    }
    
    /**
    * Gets the last topic and post.
    
    * @param    int     $section_id    The section id.
    
    * @param    array    (topic_id, topic_title, post_id, post_time, user_id, user_name)
    */
    public function getLast ($section_id)
    {
        $lastTopic = $this->getLastTopic($section_id);
        $lastPost  = $this->getLastPost($section_id);

        return array(
            'topic_id'    => $lastTopic['topic_id'],
            'topic_title' => $lastTopic['topic_title'],
            'post_id'     => $lastPost['post_id'],
            'post_time'   => $lastPost['post_time'],
            'user_id'     => $lastPost['user_id'],
            'user_name'   => $lastPost['user_name']
        );
    }

    /**
    * Updates the last post informations of a section
    
    * @param    int    $section_id    The section id.
    */
    public function updateLastInfo ($section_id)
    {
        $last = $this->getLast($section_id);

        $this->Database->sendQuery($this->Query->updateLastInfo(
            $section_id,
            $last['topic_id'],
            $last['topic_title'],
            $last['post_id'],
            $last['post_time'],
            $last['user_id'],
            $last['user_name']
        ));

        foreach ($this->getParents($section_id) as $section) {
            if ($section == 0) {
                break;
            }

            $this->Database->sendQuery($this->Query->updateLastInfo(
                $section,
                $last['topic_id'],
                $last['topic_title'],
                $last['post_id'],
                $last['post_time'],
                $last['user_id'],
                $last['user_name']
            ));
        }
    }
}
?>
