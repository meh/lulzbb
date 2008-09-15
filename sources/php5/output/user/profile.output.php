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

include_once(SOURCES_PATH.'/output/output.class.php');
include_once(SOURCESPATH.'/template/user/profile.template.php');

/**
* User's profile show class.

* @author cHoBi
*/
class UserProfileShow extends Output
{
    /**
    * Gets the data from the user.
    */
    public function __construct ($user_id)
    {
        $this->id = $user_id;

        $this->__update();
    }
    
    /**
    * Show the profile.
    */
    protected function __update ()
    {
        global $Database;
        $template = new UserProfileTemplate($Database['user']->getInfos($this->id));
        
        $this->output = $template->output();
    }
}
?>
