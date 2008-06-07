<?php
/**
* @package Interfaces

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

// User
if (isset($_GET['login'])) {
    require_once(SOURCE_PATH.'/send/user/login.send.php');

    $DATA['username'] = $_REQUEST['username'];
    $DATA['password'] = $_REQUEST['password'];

    $login = new Login($DATA['username'], $DATA['password']);
    echo $login->output();
}

if (isset($_GET['logout'])) {
    require_once(SOURCE_PATH.'/send/user/logout.send.php');

    $logout = new Logout();
    echo $logout->output();
}

if (isset($_GET['register'])) {
    require_once(SOURCE_PATH.'/send/user/registration.send.php');

    if (isset($_GET['check'])) {
        if (isset($_GET['username'])) {
            $DATA['username'] = $_REQUEST['username'];

            $registration = new Registration(
                'check_username',
                array('username' => $DATA['username'])
            );
        }

        if (isset($_GET['email'])) {
            $DATA['email1'] = $_REQUEST['email1'];
            $DATA['email2'] = $_REQUEST['email2'];

            $registration = new Registration(
                'check_email',
                array(
                    'email1' => $DATA['email1'],
                    'email2' => $DATA['email2']
                )
            );
        }

        if (isset($_GET['password'])) {
            $DATA['password1'] = $_REQUEST['password1'];
            $DATA['password2'] = $_REQUEST['password2'];

            $registration = new Registration(
                'check_password',
                array(
                    'password1' => $DATA['password1'],
                    'password2' => $DATA['password2']
                )
            );
        }
    }

    if (isset($_GET['send'])) {
        $DATA['username'] = $_REQUEST['username'];
        $DATA['email']    = $_REQUEST['email'];
        $DATA['password'] = $_REQUEST['password'];

        $registration = new Registration(
            'send',
            array(
                'username' => $DATA['username'],
                'email'    => $DATA['email'],
                'password' => $DATA['password']
            )
        );
    }
    echo $registration->output();
}

// Forum
if (isset($_GET['topic'])) {
    if (isset($_GET['send'])) {
        require_once(SOURCE_PATH.'/send/forum/topic.send.php');

        $DATA['magic']    = $_REQUEST['magic'];
        $DATA['parent']   = $_REQUEST['parent'];
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
            $DATA['content']
        );
        echo $topic->output();
    }
}

if (isset($_GET['post'])) {
    if (isset($_GET['send'])) {
        require_once(SOURCE_PATH.'/send/forum/post.send.php');

        $DATA['magic']    = $_REQUEST['magic'];
        $DATA['topic_id'] = $_REQUEST['topic_id'];
        $DATA['title']    = $_REQUEST['title'];
        $DATA['content']  = $_REQUEST['content'];

        $post = new Post(
            $DATA['magic'],
            $DATA['topic_id'],
            $DATA['title'],
            $DATA['content']
        );
        echo $post->output();
    }
}
?>
