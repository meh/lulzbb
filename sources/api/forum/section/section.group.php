<?php
/**
* @package API
* @category Section

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

* @author cHoBi
*/

/**
* Adds a group of sections.

* @param    int       $parent    The section where to add the group.
* @param    int       $weight    The group's weight.
* @param    string    $name      The group's name.
*/
function add_group($parent, $weight, $name) {
    if (!isset($parent) or !isset($weight) or empty($name)) {
        die('Not enough parmeters.');
    }

    $Database->section->group->add($parent, $weight, $name);
    rm('/output/cache/sections/*');   
}
?>
