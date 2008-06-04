<?php
/**
* @package Interfaces
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

// Start the time for the stats.
$time  = microtime();
$time  = explode(' ', $time);
$time  = $time[1] + $time[0];
$start = $time;

if (count($_REQUEST) <= 1) {
    die('No parameters eh? Are you trying to hax me? :(');
}

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

if (isset($_REQUEST['home'])) {
    require_once(SOURCE_PATH.'/show/misc/home.show.php');

    if (isset($_REQUEST['forum'])) {
        $DATA['id'] = $_REQUEST['id'];

        if (isset($_REQUEST['section'])) {
            $DATA['section_id'] = $DATA['id'];
            $DATA['page']       = $_REQUEST['page'];

            $page = new Home('section', array(
                'section_id' => $DATA['section_id'],
                'page'       => $DATA['page']
            ));
            echo $page->output();
        }
        else if (isset($_REQUEST['topic'])) {
            if (isset($_REQUEST['show'])) {
                $DATA['topic_id'] = $DATA['id'];
                $DATA['post_id']  = $_REQUEST['post_id'];

                $page = new Home('topic', array(
                    'topic_id' => $DATA['topic_id'],
                    'post_id'  => $DATA['post_id']
                ));
                echo $page->output();
            }
        }
    }
    else {
        $page = new Home($_REQUEST['page']);
        echo $page->output();
    }
}

else {
    if (isset($_REQUEST['forum'])) {
        $DATA['id'] = $_REQUEST['id'];

        // Navigator
        require_once(SOURCE_PATH.'/output/forum/navigator.output.php');

        if (isset($_REQUEST['section'])) {
            $navigator = new Navigator('section', $DATA['id']);
        }
        else if (isset($_REQUEST['topic'])) {
            if (isset($_REQUEST['send'])) {
                $DATA['parent'] = $_REQUEST['parent'];
                $navigator = new Navigator('section', $DATA['parent'], $DATA['id']);
            }
            else {
                $navigator = new Navigator('topic', $DATA['id']);
            }
        }
        echo $navigator->output();

        if (isset($_REQUEST['section'])) {
            require_once(SOURCE_PATH.'/output/forum/section.output.php');
            $DATA['section_id'] = $DATA['id'];
            $DATA['page']       = $_REQUEST['page'];

            $section = new Section($DATA['section_id'], $DATA['page']);
            echo $section->output();
            
            echo stats();
        }

        else if (isset($_REQUEST['topic'])) {
            if (isset($_REQUEST['show'])) {
                require_once(SOURCE_PATH.'/output/forum/topic.output.php');
                
                $DATA['topic_id']   = $DATA['id'];
                $DATA['topic_page'] = $_REQUEST['page'];
                $DATA['post_id']    = $_REQUEST['post'];
                
                $topic = new Topic(
                    $DATA['topic_id'],
                    $DATA['topic_page'],
                    $DATA['post_id']
                );
                echo $topic->output();

                echo stats();
            }

            else if (isset($_REQUEST['send'])) {
                require_once(SOURCE_PATH.'/template/forms/send-topic.template.php');
    
                $DATA['parent']  = $_REQUEST['parent'];
                $DATA['magic']   = $_REQUEST['magic'];

                $form = new TopicFormTemplate($DATA['magic'], $DATA['parent']);
                echo $form->output();
            }
        }
            
        else if (isset($_REQUEST['post'])) {
            if (isset($_REQUEST['send'])) {

            }
        }
    }

    else if (isset($_REQUEST['menu'])) {
        require_once(SOURCE_PATH.'/show/misc/menu.show.php');
        $menu = new Menu();
        echo $menu->output();
    }

    else if (isset($_REQUEST['page'])) {
        require_once(SOURCE_PATH.'/template/misc/page.template.php');
        $page = new PageTemplate($_REQUEST['page']);
        echo $page->output();
    }

    else if (isset($_REQUEST['login'])) {
        require_once(SOURCE_PATH.'/show/user/login.show.php');
        $login = new Login();
        echo $login->output();
    }

    else if (isset($_REQUEST['register'])) {
        require_once(SOURCE_PATH.'/show/user/registration.show.php');
        $registration = new Registration();
        echo $registration->output();
    }
}
?>
