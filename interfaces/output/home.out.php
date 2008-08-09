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

// Start the time for the stats.
$time  = microtime();
$time  = explode(' ', $time);
$time  = $time[1] + $time[0];
$start = $time;

require_once(SOURCE_PATH.'/show/misc/home.show.php');

if (isset($_GET['forum'])) {
    $DATA['id']   = $_REQUEST['id'];
    $DATA['page'] = $_REQUEST['page'];

    if (isset($_GET['section'])) {
        $DATA['section_id'] = $DATA['id'];

        $page = new Home('section', array(
            'section_id' => $DATA['section_id'],
            'page'       => $DATA['page']
        ));
        echo $page->output();
    }
    else if (isset($_GET['topic'])) {
        $DATA['topic_id'] = $DATA['id'];
        $DATA['post_id']  = $_REQUEST['post_id'];

        $page = new Home('topic', array(
            'topic_id' => $DATA['topic_id'],
            'page'     => $DATA['page'],
            'post_id'  => $DATA['post_id']
        ));
        echo $page->output();
    }
}

else if (isset($_GET['user'])) {
    $DATA['id'] = $_REQUEST['id'];
    
    $page = new Home('user', array('user_id' => $DATA['id']));
    echo $page->output();
}

else {
    if (isset($_GET['raw'])) {
        $page = new Home($_REQUEST['page'], 'raw');
    }
    else if (isset($_GET['highlight'])) {
        $page = new Home($_REQUEST['page'], 'highlight');
    }
    else {
        $page = new Home($_REQUEST['page']);
    }

    echo $page->output();
}
?>
