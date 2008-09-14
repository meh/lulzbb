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

require_once(SOURCES_PATH.'/database/sql/database/core.database.php');

/**
* XBAWKZ HUEG class for database communication.

* @author cHoBi
*/
class Database
{
    private $mysql;
    private $query;

    private $modules;

    /**
    * Create the mysql connection and selects the database from the
    * configuration file.
    
    * @exception    database_connection    On database connection failure.
    */
    public function __construct ()
    {
        global $Config;
        
        $this->mysql = mysql_connect(
            $Config->get('dbHost').':'.$Config->get('dbPort'),
            $Config->get('dbUsername'),
            $Config->get('dbPassword')
        );

        if (!$this->mysql) {
            die("There's an error with the MySQL database, check your configuration and the server.");
        }
        
        mysql_select_db($Config->get('dbName'), $this->mysql);

        $this->_add(new CoreDatabase($this), 'core');
    }

    public function __destruct ()
    {
        mysql_close($this->mysql);
    }

    /**
    * It sends the query and store the content in $this->query
    
    * @param    string    $query    The SQL query to send to the database.
    
    * @exception    database_query    On query failure.
    
    * @return    resource    The response from the mysql database.
    * @todo Remove the mysql_error();
    */
    public function sendQuery ($query)
    {
        $this->query = mysql_query($query) or die(nl2br($query).mysql_error());
        
        if (!$this->query) {
            throw new lulzException('database_query');
        }
        
        global $queries;
        $queries++;

        return $this->query;
    }

    final public function _add ($Database, $module)
    {
        $this->modules[$module] = $Database;
    }

    final public function _ ($module)
    {
        return $this->modules[$module];
    }

    /**
    * Fetch the data from the query, filter it and put it in separated arrays,
    * RAW is the slash stripped output, HTML is HTML filtered and POST is filtered
    * with rawurlencode.
    
    * @return    array    (RAW => stripslash, HTML => htmlentities, POST => rawurlencode)
    */
    public function fetchArray ()
    {
        global $Filter;
    
        if (!($array = mysql_fetch_array($this->query, MYSQL_ASSOC))) {
            return false;
        }
        
        foreach($array as $key => $element) {
            $result[$key]['RAW']  = $Filter->SQLclean($element);
            $result[$key]['HTML'] = $Filter->HTML($element);
            $result[$key]['POST'] = $Filter->POST($element);
        }

        return $result;
    }

    /**
    * Sets the counter @i to a certain value.

    * @param    int    $number    The value that @i will be set to.
    */
    public function setCounter ($number)
    {
        $number = (int) $number;
        $this->sendQuery("SET @i = {$number}");
    }

    public function getCounter ()
    {
        $number = mysql_fetch_row($this->sendQuery("SELECT @i"));

        return $number[0];
    }
}
?>
