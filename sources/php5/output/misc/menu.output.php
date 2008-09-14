<?php
/**
* @package Core-PHP5
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

require_once(SOURCES_PATH.'/output/output.class.php');
require_once(SOURCES_PATH.'/template/template.class.php');

/**
* Menu class.

* @todo Admin, moderation etc menus.
* @author cHoBi
*/
class Menu extends Output
{
    /**
    * Create the menu.
    */
    public function __construct ()
    {
        parent::__construct();
        $this->__update();
    }

    /**
    * Sets the right output if the user is connected or something else.
    */
    protected function __update ()
    {
        if ($this->connected) {
            $output = $this->__user();
        }
        else {
            $output = $this->__guest();
        }
        
        $this->output = $output;
    }
    
    /**
    * The guest menu.
    * @access private
    */
    private function __guest ()
    {
        $template = new Template('menu/menu.guest.tpl');
        return $template->output();
    }

    /**
    * The normal user menu.
    * @access private
    */
    private function __user ()
    {
        $template = new Template('menu/menu.user.tpl');
        return $template->output();
    }
}
?>
