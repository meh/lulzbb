<?php
/**
* @package API
* @category Section

* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

/**
* Adds a section.

* @param    int       $group_id    The group where to add the section.
* @param    int       $weight      The section's weight.
* @param    string    $title       The section's title.
* @param    string    $subtitle    The section's subtitle.
*/
function add_section($group_id, $weight, $title, $subtitle = '') {
    if (!isset($group_id) or !isset($weight) or empty($title)) {
        die('Not enough parameters.');
    }

    $Database->section->add($group_id, $weight, $title, $subtitle);
    rm('/output/cache/sections/*');
}

?>
