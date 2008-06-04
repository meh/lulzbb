<?php
/**
* @package PHP5
* @category Show

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/show/show.class.php');
require_once(SOURCE_PATH.'/output/forum/navigator.output.php');
require_once(SOURCE_PATH.'/template/misc/home.template.php');
require_once(SOURCE_PATH.'/template/misc/page.template.php');
require_once(SOURCE_PATH.'/output/forum/section.output.php');
require_once(SOURCE_PATH.'/output/forum/topic.output.php');
require_once(SOURCE_PATH.'/template/forms/send-topic.template.php');

/**
* Shows the home with data inside.

* @todo Display the menu directly through the home and not with ajax.

* @author cHoBi
*/
class Home extends Show {
    private $file;

    /**
    * Initialize the file and the data.

    * @param    string    $file    The file to load inside the home.
    * @param    array     $data    The data that the page needs.
    */
    public function __construct($file, $data = array()) {
        parent::__construct();

        $this->data = $data;
        $this->file = $file;

        $this->__update();
    }

    /**
    * Initialize the section or the topic when needed.
    * @access private
    */
    protected function __update() {
        switch ($this->file) {
            case 'section':
            $section_id = (int) $this->data['section_id'];
            $page       = (int) $this->data['page'];
            
            $navigator = new Navigator('section', $section_id);
            $content   = new Section($section_id, $page);
            $template  = new HomeTemplate($navigator->output().$content->output().stats());
            break;

            case 'topic':
            $topic_id = (int) $this->data['topic_id'];
            $post_id  = (int) $this->data['post_id'];
            $page     = (int) $this->data['page'];

            $navigator = new Navigator('topic', $topic_id);
            $content   = new Topic($topic_id, $post_id, $page);
            $template  = new HomeTemplate($navigator->output().$content->output().stats());
            break;

            default:
            if (empty($this->file)) {
                $template = new HomeTemplate();
            }
            else {
                $content  = new PageTemplate($this->file);
                $template = new HomeTemplate($content->output());
            }
            break;
        }

        $this->output = $template->output();
    }
}
?>
