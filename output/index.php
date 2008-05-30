<?php
/**
* @package lulzBB
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

$queries = 0;

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

/**
* Gives the time and queries used by the page.

* @return    string    Time and queries used for the generation of the page.
*/
function stats() {
    global $queries;
    global $start;

    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start), 4);

    return <<<HTML
    <div id="stats">
        Page generated in <span class="time">{$total_time}</span> seconds with
        <span class="queries">{$queries}</span> queries.
    </div>
HTML;
}

if (isset($_GET['home'])) {
    if (!@isset($_SESSION[SESSION])) {
        die('The session died or something went wrong, refresh to the index please');
    }

    require_once(SOURCE_PATH.'/show/misc/home.show.php');

    if (isset($_GET['forum'])) {
        $DATA['level'] = @$_GET['level'];
        $DATA['title'] = @$_GET['title'];
        $DATA['id']    = @$_GET['id'];

        if (isset($_GET['section'])) {
            $DATA['section_id'] = $DATA['id'];

            $page = new Home('section', array(
                'section_id' => $DATA['section_id']
            ));
            echo $page->output();
        }
        else if (isset($_GET['topic'])) {
            if (isset($_GET['show'])) {
                $DATA['topic_id'] = $DATA['id'];
                $DATA['post_id']  = @$_GET['post_id'];

                $page = new Home('topic', array(
                    'topic_id' => $DATA['topic_id'],
                    'post_id'  => $DATA['post_id']
                ));
                echo $page->output();
            }

            else if (isset($_GET['send'])) {
                $DATA['parent']  = @$_GET['parent'];
                $DATA['s_title'] = @$_GET['s_title'];
                $DATA['magic']   = $_SESSION[SESSION]['magic'];

                $form = new TopicFormTemplate(
                    array(
                        'parent'  => $DATA['parent'],
                        's_title' => $DATA['s_title'],
                        'level'   => $DATA['level'],
                        'magic'   => $DATA['magic']
                    )
                );
                echo $form->output();
            }
        }
    }
    else {
        $page = new Home($_GET['page']);
        echo $page->output();
    }
}

else {
    if (isset($_GET['PHPSESSID']) or isset($_POST['PHPSESSID'])) {
        die("You can't set a php session id, sorry.");
    }

    define('ROOT_PATH', realpath('../'));

    define('MISC_PATH', ROOT_PATH.'/sources/misc');
    require_once(MISC_PATH.'/session.php');
    require_once(MISC_PATH.'/filesystem.php');

    if (((int) phpversion()) == 4) {
        die("PHP 4 isn't supported yet");
    }
    if (((int) phpversion()) == 5) {
        $sourcePath = ROOT_PATH.'/sources/php5';
    }
    if (((int) phpversion()) == 6) {
        die('LOLNO');
    }
    define('SOURCE_PATH', realpath($sourcePath));
    
    // Get the session name.
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

    if (isset($_GET['page'])) {
        require_once(SOURCE_PATH.'/template/misc/page.template.php');
        $page = new PageTemplate($_GET['page']);
        echo $page->output();
    }

    else if (isset($_GET['menu'])) {
        require_once(SOURCE_PATH.'/show/misc/menu.show.php');
        $menu = new Menu();
        echo $menu->output();
    }

    else if (isset($_GET['forum'])) {
        $DATA['level']  = @$_POST['level'];
        $DATA['title']  = @$_POST['title'];
        $DATA['parent'] = @$_POST['parent'];
        $DATA['id']     = @$_POST['id'];

        // Navigator
        require_once(SOURCE_PATH.'/output/forum/navigator.output.php');

        if (isset($_GET['section'])) {
            $navigator = new Navigator('section', $DATA['id']);
        }
        else if (isset($_GET['topic'])) {
            if (isset($_GET['send'])) {
                $navigator = new Navigator('section', $DATA['parent'], $DATA['id']);
            }
            else {
                $navigator = new Navigator('topic', $DATA['id']);
            }
        }
        echo $navigator->output();

        if (isset($_GET['section'])) {
            require_once(SOURCE_PATH.'/output/forum/section.output.php');
            $DATA['section_id'] = $DATA['id'];

            $section = new Section($DATA['section_id']);
            echo $section->output();
            
            echo stats();
        }

        else if (isset($_GET['topic'])) {
            if (isset($_GET['show'])) {
                require_once(SOURCE_PATH.'/output/forum/topic.output.php');
                
                $DATA['topic_id'] = @$_POST['id'];
                $DATA['post_id']  = @$_POST['post'];
                
                $topic = new Topic(
                    $DATA['topic_id'],
                    $DATA['post_id']
                );
                echo $topic->output();

                echo stats();
            }

            else if (isset($_GET['send'])) {
                require_once(SOURCE_PATH.'/template/forms/send-topic.template.php');
    
                $DATA['parent']  = @$_POST['parent'];
                $DATA['magic']   = $_SESSION[SESSION]['magic'];

                $form = new TopicFormTemplate($DATA['magic'], $DATA['parent']);
                echo $form->output();
            }
        }
            
        else if (isset($_GET['post'])) {
            if (isset($_GET['send'])) {

            }
        }
    }

    else if (isset($_GET['login'])) {
        require_once(SOURCE_PATH.'/show/user/login.show.php');
        $login = new Login();
        echo $login->output();
    }

    else if (isset($_GET['register'])) {
        require_once(SOURCE_PATH.'/show/user/registration.show.php');
        $registration = new Registration();
        echo $registration->output();
    }
}


?>
