<?php
/**
* @package Misc
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

if (count($_GET) == 0 && count($_POST) == 0) {
    die();
}

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
    require_once(SOURCE_PATH.'/show/misc/home.show.php');

    if (isset($_GET['forum'])) {
        $DATA['id']    = $_GET['id'];

        if (isset($_GET['section'])) {
            $DATA['section_id'] = $DATA['id'];
            $DATA['page']       = $_GET['page'];

            $page = new Home('section', array(
                'section_id' => $DATA['section_id'],
                'page'       => $DATA['page']
            ));
            echo $page->output();
        }
        else if (isset($_GET['topic'])) {
            if (isset($_GET['show'])) {
                $DATA['topic_id'] = $DATA['id'];
                $DATA['post_id']  = $_GET['post_id'];

                $page = new Home('topic', array(
                    'topic_id' => $DATA['topic_id'],
                    'post_id'  => $DATA['post_id']
                ));
                echo $page->output();
            }

            else if (isset($_GET['send'])) {
                $DATA['parent']  = $_GET['parent'];
                $DATA['s_title'] = $_GET['s_title'];
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
    if (isset($_GET['forum'])) {
        $DATA['id'] = $_POST['id'];

        // Navigator
        require_once(SOURCE_PATH.'/output/forum/navigator.output.php');

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
            require_once(SOURCE_PATH.'/output/forum/section.output.php');
            $DATA['section_id'] = $DATA['id'];
            $DATA['page']       = $_POST['page'];

            $section = new Section($DATA['section_id'], $DATA['page']);
            echo $section->output();
            
            echo stats();
        }

        else if (isset($_GET['topic'])) {
            if (isset($_GET['show'])) {
                require_once(SOURCE_PATH.'/output/forum/topic.output.php');
                
                $DATA['topic_id'] = $_POST['id'];
                $DATA['post_id']  = $_POST['post'];
                $DATA['page']     = $_POST['page'];
                
                $topic = new Topic(
                    $DATA['topic_id'],
                    $DATA['post_id'],
                    $DATA['page']
                );
                echo $topic->output();

                echo stats();
            }

            else if (isset($_GET['send'])) {
                require_once(SOURCE_PATH.'/template/forms/send-topic.template.php');
    
                $DATA['parent']  = $_POST['parent'];
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

    else if (isset($_GET['menu'])) {
        require_once(SOURCE_PATH.'/show/misc/menu.show.php');
        $menu = new Menu();
        echo $menu->output();
    }

    else if (isset($_GET['page'])) {
        require_once(SOURCE_PATH.'/template/misc/page.template.php');
        $page = new PageTemplate($_GET['page']);
        echo $page->output();
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
