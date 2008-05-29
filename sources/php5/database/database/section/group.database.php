<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/query/section/group.query.php');

/**
* This class is dedicated to section groups stuff.

* @author cHoBi
*/
class SectionGroupDatabase extends DatabaseBase {
    /**
    * Jess <3
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct($Database) {
        $query = new SectionGroupQuery();
        parent::__construct($Database, $query);
    }

    /**
    * Says if the group exists or not.

    * @param    int    $group_id    The section id.

    * @return    bool    True if it exists, false if not.
    */
    public function exists($group_id) {
        $this->Database->sendQuery($this->Query->exists($group_id));
        $section = $this->Database->fetchArray();

        if (empty($section)) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
    * Gets the heaviest section in the group.

    * @param    int    $group_id    The group id.

    * @return    int    The heaviest section in the group.
    */
    public function heaviest($group_id) {
        $this->database->sendQuery($this->Query->heaviest($group_id));
        $section = $this->database->fetchArray();

        return $section['id']['RAW'];
    }

    /**
    * Gets the lightest section in the group.

    * @param    int    $group_id    The group id.

    * @return    int    The lightest section in the group.
    */
    public function lightest($group_id) {
        $this->database->sendQuery($this->Query->lightest($group_id));
        $section = $this->database->fetchArray();

        return $section['id']['RAW'];
    }

    /**
    * Gets the group's parent id.

    * @param    int    $group_id    The group id.

    * @return    int    The parent id.
    */
    public function getParent($group_id) {
        $this->Database->sendQuery($this->Query->getParent($group_id));
        $parent = $this->Database->fetchArray();

        return $parent['parent']['RAW'];
    }
    
    /**
    * Gets the section's title.
    
    * @param    int    $group_id    The section id.
    
    * @return    string    The section title. (RAW, HTML, POST)
    */
    public function getName($group_id) {
        $this->Database->sendQuery($this->Query->getName($group_id));
        $result = $this->Database->fetchArray();

        if (!$result) {
            throw new lulzException('section_not_existent');
        }

        return $result['name'];
    }
    
    /**
    * Gets the sections in a group.
    
    * @param    int    $group_id    The group id, also, cocks.
    
    * @return    array    A section in each element :D
    */
    public function getSections($group_id) {
        $this->Database->sendQuery($this->Query->getSections($group_id));

        $sections = array();
        while ($section = $this->Database->fetchArray()) {
            array_push($sections, $section);
        }

        return $sections;
    }
}
?>
