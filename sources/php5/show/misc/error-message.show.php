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

require_once(SOURCE_PATH.'/show/show.class.php');
require_once(SOURCE_PATH.'/template/misc/error-message.template.php');

/**
* Shows an error message.

* @author cHoBi
*/
class ErrorMessage extends Show {
    private $message;
    private $type;

    /**
    * Initializes the message and the output.

    * @param    string    $message    The message that will be shown.
    */
    public function __construct($message) {
        parent::__construct();

        $this->message = $message;

        $this->__update();
    }

    /**
    * Create the template to be outputted with the message in it.
    */
    protected function __update() {
        $message = $this->message;

        $template = new ErrorMessageTemplate($message);

        $this->output = $template->output();
    }
}
?>
