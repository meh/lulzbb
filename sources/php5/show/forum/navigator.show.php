<?php
/**
* @package lulzBB-PHP5
* @category Show

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/show/show.class.php'); 
require_once(SOURCE_PATH.'/template/forum/navigator.template.php');

/**
* Navigator show class, to get the navigator through the db.
* This class is pretty expensive in SQL queries.

* @author cHoBi
*/
class NavigatorShow extends Show {
    private $elements;

    /**
    * Initialize the elements and the output.

    * @param    string    $type      The navigator type. section | topic
    * @param    int       $id        The id of the section or topic.
    * @param    int       $option    The possible option for the navigator.
    */
    public function __construct($type, $id, $option = 0) {
        parent::__construct();
        
        $this->elements = $this->__createNavigator($type, $id, $option);
        $this->__update();
    }

    /**
    * Create the template and put it as output :D
    * @access private
    */
    protected function __update() {
        $navigator = new NavigatorTemplate($this->elements);
        $this->output = $navigator->output();
    }

    /**
    * Creates the navigator data.

    * @param    string    $type      The navigator type. section | topic
    * @param    int       $id        The id of the section or topic.
    * @param    int       $option    The option for the navigator.

    * @return    array    All the parents and the actual object :D
    * @access private
    */
    private function __createNavigator($type, $id, $option) {
        global $Database;

        try {
            switch ($type) {
                case 'section':
                $parents = $Database->section->getNavigator($id, $option);
                break;

                case 'topic':
                $parents = $Database->topic->getNavigator($id, $option);
                break;
            }
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }

        return $parents;
    }

    /**
    * Gets the id of the elements in the navigator.

    * @return    array    An id in each element of the array.
    */
    public function getElementsId() {
        $elements = array();

        foreach ($this->elements as $element) {
            array_push($elements, $element['id']);
        }

        return $elements;
    }
}
?>
