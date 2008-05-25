<?php
/**
* @package lulzBB-PHP5
* @category Database

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/database.base.class.php');
require_once(SOURCE_PATH.'/database/query/misc.query.php');

/**
* This class is dedicated to misc stuff.

* @author cHoBi
*/
class MiscDatabase extends DatabaseBase {
    /**
    * Oh noes, still the same >:3 GET IN THE CAR!
    
    * @param    object    $Database   The Database object, recursive object is recursive.
    */
    public function __construct($Database) {
        $query = new MiscQuery();
        parent::__construct($Database, $query); 
    }
    
    /**
    * Returns the last topic id.
    
    * @return    int    Last topic id.
    */
    public function getLastTopic() {
        $query = $this->Database->sendQuery($this->Query->getLastTopic());

        $last_topic_id = mysql_fetch_row($query);
        return $last_topic_id[0];
    }
}
?>
