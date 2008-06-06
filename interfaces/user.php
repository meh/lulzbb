<?php
/**
* @package Interfaces

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

* @author cHoBi
*/

if (!isset($Config)) {
    die("You can't access this directly.");
}

if (count($_REQUEST) <= 1) {
    die('No parameters eh? Are you trying to hax me? :(');
}

if (isset($_REQUEST['show'])) {
    if (isset($_REQUEST['profile'])) {
        require_once(SOURCE_PATH.'/output/user/profile.output.php');

        $DATA['user_id'] = $_REQUEST['id'];

        $template = new UserProfile($DATA['user_id']);
        echo $template->output();
    }
}

else if (isset($_REQUEST['send'])) {

}
?>