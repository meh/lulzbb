<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/exception.class.php');
require_once(SOURCE_PATH.'/database/database/user.database.php');
require_once(SOURCE_PATH.'/database/database/section.database.php');
require_once(SOURCE_PATH.'/database/database/topic.database.php');
require_once(SOURCE_PATH.'/database/database/misc.database.php');

/**
* XBAWKZ HUEG class for database communication.

* @property    object    $user       The user database.
* @property    object    $group      The group database.
* @property    object    $section    The section database.
* @property    object    $topic      The topic database.
* @property    object    $post       The post database.
* @property    object    $misc       The misc database.

* @author cHoBi
*/
class Database {
    protected $mysql;
    protected $Query;
    protected $query;

    // Various database methods
    public $user;
    public $section;
    public $topic;
    public $misc;

    /**
    * Create the mysql connection and selects the database from the
    * configuration file.
    
    * @exception    database_connection    On database connection failure.
    */
    public function __construct() {
        global $Config;
        
        $this->mysql = @mysql_connect(
            $Config->get('dbHost'),
            $Config->get('dbUsername'),
            $Config->get('dbPassword')
        );

        if (!$this->mysql) {
            throw new lulzException('database_connection');
        }
        
        mysql_select_db($Config->get('dbName'), $this->mysql);

        $this->user    = new UserDatabase($this);
        $this->section = new SectionDatabase($this);
        $this->topic   = new TopicDatabase($this);
        $this->misc    = new MiscDatabase($this);
    }

    public function __destruct() {
        @mysql_close($this->mysql);
    }

    /**
    * It sends the query and store the content in $this->query
    
    * @param    string    $query    The SQL query to send to the database.
    
    * @exception    database_query    On query failure.
    
    * @return    resource    The response from the mysql database.
    * @todo Remove the mysql_error();
    */
    public function sendQuery($query) {
        $this->query = @mysql_query($query) or die(nl2br($query).mysql_error());
        
        if (!$this->query) {
            throw new lulzException('database_query');
        }
        
        global $queries;
        $queries++;

        return $this->query;
    }

    /**
    * Fetch the data from the query, filter it and put it in separated arrays,
    * RAW is the slash stripped output, OUT is HTML filtered and POST is filtered
    * with rawurlencode.
    
    * @return    array    (RAW => stripslash, OUT => htmlentities, POST => rawurlencode)
    */
    public function fetchArray() {
        global $Filter;
    
        if (!($array = mysql_fetch_array($this->query, MYSQL_ASSOC))) {
            return false;
        }
        
        foreach($array as $key => $element) {
            $result[$key]['RAW']  = $Filter->SQLclean($element);
            $result[$key]['HTML'] = $Filter->HTML_SQLclean($element);
            $result[$key]['POST'] = $Filter->POST_SQLclean($element);
        }

        return $result;
    }

    /**
    *
    */
    public function exists() {
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

        $query = $this->sendQuery('SHOW TABLES');

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
