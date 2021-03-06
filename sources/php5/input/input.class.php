<?php
/**
* @package Core-PHP5
* @category Send

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

require_once(SOURCES_PATH.'/output/misc/informative-message.output.php');

/**
* Send base class.

* @author cHoBi
*/
abstract class Input
{
    protected $data;
    protected $output;
    protected $connected;
    protected $magic;

    /**
    * Check connection and get the magic token.
    */
    public function __construct ()
    {
        if (isset($_SESSION[SESSION]['user'])) {
            $this->connected = true;
        }
        else {
            $this->connected = false;
        }
        
        $this->magic = $_SESSION[SESSION]['magic'];
    }
    
    /**
    * Update the magic token.
    */
    public function __destruct ()
    {
        $_SESSION[SESSION]['magic'] = md5(rand().rand().time());
    }

    /**
    * Used to send the data.
    * You MUST redeclare this.

    * @param    array    $data    Associative array with the needed data.
    */
    protected abstract function __send ($data);

    /**
    * Returns the output.

    * @return    string
    */
    public function output ()
    {
        return $this->output;
    }
}
?>
