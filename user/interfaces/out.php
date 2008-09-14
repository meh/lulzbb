<?php
/**
* @package Interfaces
* @category Output

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

if (isset($_GET['profile'])) {
    if (isset($_GET['show'])) {
        require_once($M_SOURCES_PATH.'/output/profile.output.php');

        $DATA['user_id'] = $_REQUEST['id'];

        $template = new UserProfile($DATA['user_id']);
        echo $template->output();
    }

    else if (isset($_GET['edit'])) {

    }
}

else if (isset($_GET['login'])) {
    require_once($M_SOURCES_PATH.'/output/login.output.php');
    $login = new Login();
    echo $login->output();
}

else if (isset($_GET['register'])) {
    require_once($M_SOURCES_PATH.'/output/registration.output.php');
    $registration = new Registration();
    echo $registration->output();
}
?>
