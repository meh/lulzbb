<?php
/**
* @package Core-PHP5
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

include_once(SOURCES_PATH.'/template/template.class.php');

/**
* Menu template.

* @author cHoBi
*/
class MenuTemplate extends Template
{
    private $groups;

    /**
    * Sets the file to get and parse it.

    * @param    string    $file    The file to get.
    * @param    string    $mode    The page view mode.
    */
    public function __construct ($groups)
    {
        parent::__construct('misc/menu.tpl');

        print_r($groups);
        die("LOL");

        $this->groups = $groups;

        $this->__parse();
    }

    /**
    * Puts the gotten page into the template variable.
    * @access private
    */
    private function __parse ()
    {
        $text = $this->output();

        $this->parsed = $text;
    }
}
?>
