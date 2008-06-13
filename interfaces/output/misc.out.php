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

if (isset($_GET['menu'])) {
    require_once(SOURCE_PATH.'/show/misc/menu.show.php');
    $menu = new Menu();
    echo $menu->output();
}

else if (isset($_GET['page'])) {
    require_once(SOURCE_PATH.'/template/misc/page.template.php');
    $page = new PageTemplate($_REQUEST['page']);
    echo $page->output();
}
?>
