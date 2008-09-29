<?php
/**
* @package Forum
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

include_once(SOURCES_PATH.'/database/sql/database.base.class.php');
include_once($M_SOURCES_PATH.'/database/sql/database/section/section.database.php');
include_once($M_SOURCES_PATH.'/database/sql/database/topic/topic.database.php');
include_once($M_SOURCES_PATH.'/database/sql/database/misc.database.php');

/**
* Main database class.

* @property    object    $section    The section database.
* @property    object    $topic      The topic database.
* @property    object    $misc       The misc database.

* @author cHoBi
*/
class ForumDatabase extends DatabaseBase
{
    // Various database methods
    public $section;
    public $topic;
    public $misc;

    /**
    * Create the mysql connection and selects the database from the
    * configuration file.
    
    * @exception    database_connection    On database connection failure.
    */
    public function __construct ($Database = false)
    {
        parent::__construct($Database);

        $this->section = new SectionDatabase($Database);
        $this->topic   = new TopicDatabase($Database);
        $this->misc    = new MiscDatabase($Database);
    }

    /**
    * Checks if the database already exists.
    * It just checks if the normal sections exist or not.

    * @return    bool    True if it exists, false if it doesn't.
    */
    public function exists ()
    {
        global $Config;
        $dbPrefix = $Config->get('dbPrefix');

        $tables = array(
            'section_groups',
            'sections',
            'topics',
            'topics_read',
            'topic_posts',
            'users',
            'user_groups'
        );

        $query = $this->Database->sendQuery('SHOW TABLES');

        while ($table = mysql_fetch_row($query)) {
            foreach ($tables as $n => $name) {
                if ($table[0] == "{$dbPrefix}_{$name}") {
                    unset($tables[$n]);
                }
            }
        }

        if (empty($tables)) {
            return true;
        }
        else {
            return false;
        }
    }
}
?>
