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

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/query/section/group.query.php');

/**
* This class is dedicated to section groups stuff.

* @author cHoBi
*/
class SectionGroupDatabase extends DatabaseBase
{
    /**
    * Jess <3
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct ($Database)
    {
        $query = new SectionGroupQuery();
        parent::__construct($Database, $query);
    }

    /**
    * Says if the group exists or not.

    * @param    int    $group_id    The section id.

    * @return    bool    True if it exists, false if not.
    */
    public function exists ($group_id)
    {
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
    * Adds a group to a parent.

    * @param    int       $parent     The group where to add the group.
    * @param    int       $weight     The group's weight.
    * @parma    string    $name       The group's name.
    */
    public function add ($parent, $weight, $name)
    {
        $this->Database->sendQuery($this->Query->add($parent, $weight, $name));
    }

    /**
    * Gets the heaviest section in the group.

    * @param    int    $group_id    The group id.

    * @return    int    The heaviest section in the group.
    */
    public function heaviest ($group_id)
    {
        $this->database->sendQuery($this->Query->heaviest($group_id));
        $section = $this->database->fetchArray();

        return $section['id']['RAW'];
    }

    /**
    * Gets the lightest section in the group.

    * @param    int    $group_id    The group id.

    * @return    int    The lightest section in the group.
    */
    public function lightest ($group_id)
    {
        $this->database->sendQuery($this->Query->lightest($group_id));
        $section = $this->database->fetchArray();

        return $section['id']['RAW'];
    }

    /**
    * Gets the group's parent id.

    * @param    int    $group_id    The group id.

    * @return    int    The parent id.
    */
    public function getParent ($group_id)
    {
        $this->Database->sendQuery($this->Query->getParent($group_id));
        $parent = $this->Database->fetchArray();

        return $parent['parent']['RAW'];
    }
    
    /**
    * Gets the section's title.
    
    * @param    int    $group_id    The section id.
    
    * @return    string    The section title. (RAW, HTML, POST)
    */
    public function getName ($group_id)
    {
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
    public function getSections ($group_id)
    {
        $this->Database->sendQuery($this->Query->getSections($group_id));

        $sections = array();
        while ($section = $this->Database->fetchArray()) {
            array_push($sections, $section);
        }

        return $sections;
    }
}
?>
