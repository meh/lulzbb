<?php
/**
* @package News
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

if (!defined('LULZ')) {
    die("You can't access this directly.");
}

$content = '';

$DATA['id'] = $_REQUEST['id'];

include_once(SOURCES_PATH.'/output/misc/home.output.php');
$home = new Home($content);
echo $home->output();

?>