<?php
/**
* @package PHP5
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/database/query.class.php');

/**
* @ignore
*
* @author cHoBi
*/
class MiscQuery extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function getLastTopic() {
        return <<<QUERY

            SELECT
                MAX({$this->dbPrefix}_topics.id)
    
            FROM
                {$this->dbPrefix}_topics
            
QUERY;
    }
}
?>
