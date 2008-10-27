<?php
/**
* @package Forum
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

// Navigator
include_once($M_SOURCES_PATH.'/output/navigator.output.php');

if (isset($_GET['section'])) {
    $navigator = new Navigator('section', $DATA['id']);
}
else if (isset($_GET['topic'])) {
    if (isset($_GET['send'])) {
        $DATA['parent'] = $_REQUEST['parent'];
        $navigator = new Navigator('section', $DATA['parent'], $DATA['id']);
    }
    else {
        $navigator = new Navigator('topic', $DATA['id']);
    }
}
else {
    $navigator = new Navigator('section', 0, 0);
}
$content .= $navigator->output();

if (isset($_GET['section'])) {
    include_once($M_SOURCES_PATH.'/output/section.output.php');
    $DATA['section_id'] = $DATA['id'];
    $DATA['page']       = $_REQUEST['page'];

    $section = new Section($DATA['section_id'], $DATA['page']);
    $content .= $section->output();
    $content .= stats();
}

else if (isset($_GET['topic'])) {
    if (isset($_GET['send'])) {

    }
    else {
        include_once($M_SOURCES_PATH.'/output/topic.output.php');
        
        $DATA['topic_id'] = $DATA['id'];
        $DATA['page']     = $_REQUEST['page'];
        $DATA['post_id']  = $_REQUEST['post'];

        $topic = new Topic(
            $DATA['topic_id'],
            $DATA['page'],
            $DATA['post_id']
        );
        $content .= $topic->output();
        $content .= stats();
    }
}

else {
    include_once($M_SOURCES_PATH.'/output/section.output.php');

    $section = new Section(0, 1);
    $content .= $section->output();
    $content .= stats();
}

include_once(SOURCES_PATH.'/output/misc/home.output.php');
$home = new Home($content);
echo $home->output();

?>
