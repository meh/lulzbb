<?php
/**
* @package API
* @category Section

* @license http://opensource.org/licenses/gpl-3.0.html

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
