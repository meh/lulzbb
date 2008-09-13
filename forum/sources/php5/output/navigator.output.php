<?php
/**
* @package PHP5
* @category Show

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

require_once(M_SOURCES_PATH.'/show/show.class.php'); 
require_once(M_SOURCES_PATH.'/template/forum/navigator.template.php');

/**
* Navigator show class, to get the navigator through the db.
* This class is pretty expensive in SQL queries.

* @author cHoBi
*/
class NavigatorShow extends Show
{
    private $elements;

    /**
    * Initialize the elements and the output.

    * @param    string    $type      The navigator type. section | topic
    * @param    int       $id        The id of the section or topic.
    * @param    int       $option    The possible option for the navigator.
    */
    public function __construct ($type, $id, $option = 0)
    {
        parent::__construct();
        
        $this->elements = $this->__createNavigator($type, $id, $option);
        $this->__update();
    }

    /**
    * Create the template and put it as output :D
    */
    protected function __update ()
    {
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
    private function __createNavigator ($type, $id, $option)
    {
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

            foreach ($parents as $key => $element) {
                $parents[$key]['type'] = 'section';
            }

            if ($type == 'topic') {
                $parents[count($parents)-1]['type'] = 'topic';
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
    public function getElementsId ()
    {
        $elements = array();

        foreach ($this->elements as $element) {
            array_push($elements, $element['id']);
        }

        return $elements;
    }
}
?>
