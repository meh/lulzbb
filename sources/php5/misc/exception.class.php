<?php
/**
* @package PHP5

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

require_once(SOURCE_PATH.'/show/misc/error-message.show.php');

/**
* Exception handling for Misc.

* @author cHoBi
*/
class lulzException extends Exception
{
    /**
    * Creates an exception and initialize the message with the type.

    * @param    string    $type    The exception type.
    */
    public function __construct ($type)
    {
        switch ($type) {
            case 'database_connection':
            $message  = 'The database connection failed :(<br/><br/>';
            $message .= 'Did you configure the board correctly?';
            $code     = 31;
            break;
            
            case 'database_query':
            $message  = 'Something went wrong with a query, check the database integrity.';
            $code     = 32;
            break;

            case 'section_not_existent':
            $message = "The section doesn't exist.";
            $code    = 41;
            break;

            case 'topic_not_existent':
            $message = "The topic doesn't exist.";
            $code    = 51;
            break;
        }
                    
        $message = new ErrorMessage ($message);
        parent::__construct($message->output(), $code);
    }
}
?>
