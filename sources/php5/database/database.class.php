<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/exception.class.php');
require_once(SOURCE_PATH.'/database/database/user.database.php');
require_once(SOURCE_PATH.'/database/database/group.database.php');
require_once(SOURCE_PATH.'/database/database/section.database.php');
require_once(SOURCE_PATH.'/database/database/topic.database.php');
require_once(SOURCE_PATH.'/database/database/post.database.php');
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
    public $group;
    public $section;
    public $topic;
    public $post;
    public $misc;

    /**
    * Create the mysql connection and selects the database from the
    * configuration file.
    
    * @exception    database_connection    On database connection failure.
    */
    public function __construct() {
        global $config;
        
        $this->mysql = @mysql_connect(
            $config->get('dbHost'),
            $config->get('dbUsername'),
            $config->get('dbPassword')
        );

        if (!$this->mysql) {
            throw new lulzException('database_connection');
        }
        
        mysql_select_db($config->get('dbName'), $this->mysql);

        $this->user    = new UserDatabase($this);
        $this->group   = new GroupDatabase($this);
        $this->section = new SectionDatabase($this);
        $this->topic   = new TopicDatabase($this);
        $this->post    = new PostDatabase($this);
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
    */
    public function sendQuery($query) {
        $this->query = @mysql_query($query);
        
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
        global $filter;
    
        if (!($array = mysql_fetch_array($this->query, MYSQL_ASSOC))) {
            return false;
        }
        
        foreach($array as $key => $element) {
            $result[$key]['RAW']  = $filter->SQLclean($element);
            $result[$key]['HTML'] = $filter->HTML_SQLclean($element);
            $result[$key]['POST'] = $filter->POST_SQLclean($element);
        }

        return $result;
    }
}
?>
