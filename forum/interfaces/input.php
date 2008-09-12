<?php
/**
* @package Interfaces
* @category Input

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

if (isset($_GET['topic'])) {
    if (isset($_GET['send'])) {
        require_once(SOURCE_PATH.'/send/forum/topic.send.php');

        $DATA['magic']    = $_REQUEST['magic'];
        $DATA['parent']   = $_REQUEST['parent'];
        $DATA['nick']     = $_REQUEST['nick'];
        $DATA['type']     = $_REQUEST['type'];
        $DATA['title']    = $_REQUEST['title'];
        $DATA['subtitle'] = $_REQUEST['subtitle'];
        $DATA['content']  = $_REQUEST['content'];

        $topic = new Topic(
            $DATA['magic'],
            $DATA['parent'],
            $DATA['type'],
            $DATA['title'],
            $DATA['subtitle'],
            $DATA['content'],
            $DATA['nick']
        );
        echo $topic->output();
    }
}

if (isset($_GET['post'])) {
    if (isset($_GET['send'])) {
        require_once(SOURCE_PATH.'/send/forum/post.send.php');

        $DATA['magic']    = $_REQUEST['magic'];
        $DATA['topic_id'] = $_REQUEST['topic_id'];
        $DATA['nick']     = $_REQUEST['nick'];
        $DATA['title']    = $_REQUEST['title'];
        $DATA['content']  = $_REQUEST['content'];

        $post = new Post(
            $DATA['magic'],
            $DATA['topic_id'],
            $DATA['title'],
            $DATA['content'],
            $DATA['nick']
        );
        echo $post->output();
    }
}
?>
