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

require_once(SOURCES_PATH.'/input/input.class.php');

/**
* Logout yay.

* @author cHoBi
*/
class Logout extends Input
{
    /**
    * Logout.
    */
    public function __construct ()
    {    
        $this->output = $this->__send(0);
    }

    /**
    * Delete the user data from the session, so it's a logout :D

    * @return     string    The logout screen.
    */
    protected function __send ($data)
    {
        $template = new InformativeMessage('logout_successful');
       
        destroySession();
        return $template->output();
    }
}
?>
