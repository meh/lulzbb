<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/query/section.query.php');

/**
* This class is dedicated to section stuff.

* @author cHoBi
*/
class SectionDatabase extends DatabaseBase {
    /**
    * Dolphins, they're never related.
    
    * @param    object    $database   The Database object, recursive object is recursive.
    */
    public function __construct($database) {
        $query = new SectionQuery();
        parent::__construct($database, $query);
    }

    /**
    * Says if the section exists or not.

    * @param    int    $section_id    The section id.

    * @return    bool    True if it exists, false if not.
    */
    public function exists($section_id) {
        if ($section_id == 0) {
            return true;
        }

        $this->database->sendQuery($this->Query->exists($section_id));
        $section = $this->database->fetchArray();

        if (empty($section) || $section['type']['RAW'] != 0) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
    * Gets id and title of a section.

    * @param    int    $section_id    The section id.

    * @return    array    (id, name)
    */
    public function getInfo($section_id) {
        $name = $this->getTitle($section_id);

        return array('id' => $section_id, 'name' => $name);
    }

    /**
    * Gets the section's parent id.

    * @param    int    $section_id    The section id.

    * @return    int    The parent id.
    */
    public function getParent($section_id) {
        $this->database->sendQuery($this->Query->getParent($section_id));
        $parent = $this->database->fetchArray();

        return $parent['parent']['RAW'];
    }

    /**
    * Gets the section's parent info.
    
    * @param    int    $section_id    The section id.
    
    * @return    int    The section's parent.
    */
    public function getParentInfo($section_id) {
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
    public function getParents($section_id) {
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
    public function getNavigator($section_id, $option) {
        global $config;
        global $filter;

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
    public function getTitle($section_id) {
        global $config;
        global $filter;

        if ($section_id == 0) {
            $forumName = $config->get('forumName');

            $result['title']['RAW']  = $forumName;
            $result['title']['HTML'] = $filter->HTML($forumName);
            $result['title']['POST'] = $filter->POST($forumName);
        }
        else {
            $this->database->sendQuery($this->Query->getTitle($section_id));
            $result = $this->database->fetchArray();

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
    public function getSections($section_id) {
        global $filter;
        $this->database->sendQuery($this->Query->getSections($section_id));

        $sections = array();
        while ($section = $this->database->fetchArray()) {
            foreach ($section as $key => $element) {
                $section[$key]['HTML'] = $filter->spaces($section[$key]['HTML']);
            }

            array_push($sections, $section);
        }

        return $sections;
    }

    /**
    * Gets the topics of a section.
    
    * @param    int    $section_id    The section id, and suddenly FLYING DICKS EVERYWHERE.
    
    * @return    array    A topic in each element >:3
    */
    public function getTopics($section_id) {
        global $filter;
        $this->database->sendQuery($this->Query->getTopics($section_id));

        $topics = array();
        while ($topic = $this->database->fetchArray()) {
            foreach ($topic as $key => $element) {
                $topic[$key]['HTML'] = $filter->spaces($topic[$key]['HTML']);
            }

            array_push($topics, $topic);
        }

        return $topics;
    }

    /**
    * Increases the topics count of the section.
    
    * @param    int    $section_id    The section id.
    */
    public function increaseTopicsCount($section_id) {
        $this->database->sendQuery($this->Query->increaseTopicsCount($section_id));

        foreach ($this->getParents($section_id) as $section) {
            if ($section == 0) {
                break;
            }

            $this->database->sendQuery($this->Query->increaseTopicsCount($section));
        }
    }

    /**
    * Increases the posts count of the section.
    
    * @param    int    $section_id    The section id.
    */
    public function increasePostsCount($section_id) {
        $this->database->sendQuery($this->Query->increasePostsCount($section_id));

        foreach ($this->getParents($section_id) as $section) {
            if ($section == 0) {
                break;
            }

            $this->database->sendQuery($this->Query->increasePostsCount($section));
        }
    }
    
    /**
    * Gets the last topic of a section.
    
    * @param    int    $section_id    The section id.
    
    * @return    array    (topic_id, topic_title, user_id, user_name)
    */
    public function getLastTopic($section_id) {
        $this->database->sendQuery($this->Query->getLastTopic($section_id));
        $last = $this->database->fetchArray();

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
    public function getLastPost($section_id) {
        $topic = $this->getLastTopic($section_id);
        $post  = $this->database->topic->getLastPost($topic['topic_id']);

        return $post;
    }
    
    /**
    * Gets the last topic and post.
    
    * @param    int     $section_id    The section id.
    
    * @param    array    (topic_id, topic_title, post_id, post_time, user_id, user_name)
    */
    public function getLast($section_id) {
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
    
    * @todo    The parent sections don't get the last post updated
    *          you still have to do a recursive update to get everything
    *          right :3
    */
    public function updateLastInfo($section_id) {
        $last = $this->getLast($section_id);

        $this->database->sendQuery($this->Query->updateLastInfo(
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

            $this->database->sendQuery($this->Query->updateLastInfo(
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
