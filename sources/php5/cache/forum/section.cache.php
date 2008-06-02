<?php
/**
* @package PHP5
* @category Cache

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/cache/cache.class.php');
require_once(SOURCE_PATH.'/template/template.class.php');

/**
* Section cache class.

* @author cHoBi
*/
class SectionCache extends Cache {
    private $section_id;

    /**
    * Initialize the cache and the file.

    * @param    int    $section_id    The section id.
    * @param    int    $page          The page to show.
    */
    public function __construct($section_id, $page) {
        $this->section_id = $section_id;

        $file = "sections/{$section_id}-{$page}.html";
        parent::__construct($file);

        if ($this->cached) {
            $this->__newTopic();
        }
    }

    /**
    * Parse the new topic button.
    * @access private
    */
    private function __newTopic() {
        $text = $this->cache;
        $text = preg_replace(
            '|<%NEW-TOPIC%>|i',
            $this->__newTopicTemplate(),
            $text
        );

        $this->cache = $text;
    }

    /**
    * Get the new topic template.

    * @return    string    The parsed template.
    */
    private function __newTopicTemplate() {
        if ($this->connected && $this->section_id != 0) {
            $template = new SectionTemplate(0,0,0);
            $template = $template->getTemplatePart('new_topic');
            $template = preg_replace(
                '|<%POST-SECTION-ID%>|i',
                $this->section_id,
                $template
            );
        }
        else {
            $template = '';
        }

        return $template;
    }

    /**
    * Ovveride of the parent::put, sets the new topic in addition to the base method.

    * @param    string    $content    The content to put in the cache.
    */
    public function put($content) {
        parent::put($content);
        $this->__newTopic();
    }
}
?>
