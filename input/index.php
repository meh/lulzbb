<?php
/**
* @package lulzBB
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

$queries = 0;

define('VERSION', (float) phpversion());
if ((int) VERSION == 4) {
    die("PHP 4 isn't supported yet");
}
if ((int) VERSION == 6) {
    die('LOLNO');
}

// Paths
define('ROOT_PATH', realpath('../'));
define('SOURCE_PATH', ROOT_PATH.'/sources/php'.((int) VERSION));
define('MISC_PATH', ROOT_PATH.'/sources/misc');

// Misc sources.
require_once(MISC_PATH.'/session.php');
require_once(MISC_PATH.'/filesystem.php');

// Session creation.
require_once(SOURCE_PATH.'/config.class.php');
require_once(SOURCE_PATH.'/filter.class.php');
require_once(SOURCE_PATH.'/user.class.php');
require_once(SOURCE_PATH.'/database/database.class.php');
startSession('../');

if (!isset($_SESSION[SESSION])) {
    die('The session died or something went wrong, refresh to the index please');
}

$Config   = $_SESSION[SESSION]['config'];
$Filter   = $_SESSION[SESSION]['filter'];
$Database = new Database;
$User     = @$_SESSION[SESSION]['user'];

// User
if (isset($_GET['login'])) {
    require_once(SOURCE_PATH.'/send/user/login.send.php');

    $DATA['username'] = @$_POST['username'];
    $DATA['password'] = @$_POST['password'];

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
            $DATA['username'] = @$_POST['username'];

            $registration = new Registration(
                'check_username',
                array('username' => $DATA['username'])
            );
        }

        if (isset($_GET['email'])) {
            $DATA['email1'] = @$_POST['email1'];
            $DATA['email2'] = @$_POST['email2'];

            $registration = new Registration(
                'check_email',
                array(
                    'email1' => $DATA['email1'],
                    'email2' => $DATA['email2']
                )
            );
        }

        if (isset($_GET['password'])) {
            $DATA['password1'] = @$_POST['password1'];
            $DATA['password2'] = @$_POST['password2'];

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
        $DATA['username'] = @$_POST['username'];
        $DATA['email']    = @$_POST['email'];
        $DATA['password'] = @$_POST['password'];

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

        $DATA['parent']   = @$_POST['parent'];
        $DATA['type']     = @$_POST['type'];
        $DATA['title']    = @$_POST['title'];
        $DATA['subtitle'] = @$_POST['subtitle'];
        $DATA['content']  = @$_POST['content'];
        $DATA['magic']    = @$_POST['magic'];

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

        $DATA['magic']    = @$_POST['magic'];
        $DATA['topic_id'] = @$_POST['topic_id'];
        $DATA['title']    = @$_POST['title'];
        $DATA['content']  = @$_POST['content'];

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
