<?php
/**
* @package PHP5
* @category Template

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

require_once(SOURCE_PATH.'/template/template.class.php');

/**
* Creates the template for an error message.

* @author cHoBi
*/
class ErrorMessageTemplate extends Template
{
    private $type;
    private $message;

    /**
    * Initialize the parent template and insert the message.

    * @param    string    $message    The message to insert in the template.
    */
    public function __construct ($message)
    {
        parent::__construct('misc/error-message.tpl');

        $this->message = $message;

        $this->__parse();
    }

    /**
    * Add the message to the template.
    * @access private
    */
    private function __parse ()
    {
        $text = $this->output();

        $text = preg_replace(
            '|<%ERROR-MESSAGE%>|i',
            $this->message,
            $text
        );

        $this->parsed = $text;
    }
}
?>
