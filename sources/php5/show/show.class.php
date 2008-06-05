<?php
/**
* @package PHP5
* @category Show

* @license AGPLv3
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

require_once(SOURCE_PATH.'/exception.class.php');
require_once(SOURCE_PATH.'/show/misc/informative-message.show.php');

/**
* Show base class

* @author cHoBi
*/
abstract class Show {
    protected $id;
    protected $data;
    protected $output;
    protected $connected;
    protected $magic;

    /**
    * Initialize the connection and get the magic token.
    */
    public function __construct() {
        if (isset($_SESSION[SESSION]['user'])) {
            $this->connected = true;
        }
        else {
            $this->connected = false;
        }

        $this->magic = $_SESSION[SESSION]['magic'];
    }

    /**
    * Used to update the content being showed.
    * @access private
    */
    protected abstract function __update();

    /**
    * Returns the output.

    * @return    string    The output.
    */
    public function output() {
        return $this->output;
    }
}
?>
