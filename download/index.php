<?php
/**
* @package Download

* @license AGPLv3
* Just a simple module for lulzBB.
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

* @author cHoBi
*/

define('MODULE_PATH', MODULES_PATH.'/'.MODULE_NAME);
$Config->parseFile(MODULE_PATH.'/config/configuration.php');

if (isset($_GET['download'])) {
    require(MODULE_PATH.'/interfaces/output/download.out.php');
    exit();
}

?>
