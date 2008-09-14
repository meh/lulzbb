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

require_once(SOURCES_PATH.'/database/sql/database.base.class.php');
require_once(SOURCES_PATH.'/database/sql/query/misc.query.php');

/**
* This class is dedicated to misc stuff.

* @author cHoBi
*/
class MiscDatabase extends DatabaseBase
{
    /**
    * Oh noes, still the same >:3 GET IN THE CAR!
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct ($Database)
    {
        $query = new MiscQuery();
        parent::__construct($Database, $query); 
    }
    
    /**
    * Returns the last topic id.
    
    * @return    int    Last topic id.
    */
    public function getLastTopic ()
    {
        $query = $this->Database->sendQuery($this->Query->getLastTopic());

        $last_topic_id = mysql_fetch_row($query);
        return $last_topic_id[0];
    }
}
?>
