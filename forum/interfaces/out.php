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

if (!isset($Config)) {
    die("You can't access this directly.");
}

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
echo $navigator->output();

if (isset($_GET['section'])) {
    include_once($M_SOURCES_PATH.'/output/section.output.php');
    $DATA['section_id'] = $DATA['id'];
    $DATA['page']       = $_REQUEST['page'];

    $section = new Section($DATA['section_id'], $DATA['page']);
    echo $section->output();
    
    echo stats();
}

else if (isset($_GET['topic'])) {
    if (isset($_GET['show'])) {
        include_once($M_SOURCES_PATH.'/output/topic.output.php');
        
        $DATA['topic_id'] = $DATA['id'];
        $DATA['page']     = $_REQUEST['page'];
        $DATA['post_id']  = $_REQUEST['post'];

        $topic = new Topic(
            $DATA['topic_id'],
            $DATA['page'],
            $DATA['post_id']
        );
        echo $topic->output();

        echo stats();
    }

    else if (isset($_GET['send'])) {
        include_once($M_SOURCES_PATH.'/template/forms/send-topic.template.php');

        $DATA['parent'] = $_REQUEST['parent'];

        $form = new TopicFormTemplate($DATA['parent']);
        echo $form->output();
    }
}
    
else if (isset($_GET['post'])) {
    if (isset($_GET['send'])) {

    }
}
?>
