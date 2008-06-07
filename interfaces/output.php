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

// Start the time for the stats.
$time  = microtime();
$time  = explode(' ', $time);
$time  = $time[1] + $time[0];
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
        $DATA['id'] = $_REQUEST['id'];

        if (isset($_GET['section'])) {
            $DATA['section_id'] = $DATA['id'];
            $DATA['page']       = $_REQUEST['page'];

            $page = new Home('section', array(
                'section_id' => $DATA['section_id'],
                'page'       => $DATA['page']
            ));
            echo $page->output();
        }
        else if (isset($_GET['topic'])) {
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
    if (isset($_GET['forum'])) {
        $DATA['id'] = $_REQUEST['id'];

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
            $DATA['page']       = $_REQUEST['page'];

            $section = new Section($DATA['section_id'], $DATA['page']);
            echo $section->output();
            
            echo stats();
        }

        else if (isset($_GET['topic'])) {
            if (isset($_GET['show'])) {
                require_once(SOURCE_PATH.'/output/forum/topic.output.php');
                
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
                require_once(SOURCE_PATH.'/template/forms/send-topic.template.php');
    
                $DATA['parent'] = $_REQUEST['parent'];

                $form = new TopicFormTemplate($DATA['parent']);
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
        $page = new PageTemplate($_REQUEST['page']);
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
