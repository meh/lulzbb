<?php
/**
* @package lulzBB-PHP5
* @category Template

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/template/template.class.php');

/**
* Post form template class.

* @author cHoBi
*/
class PostFormTemplate extends Template {
    /**
    * Initialize the data.

    * @param    string    $magic       The magic token . (Anti XSRF)
    * @param    int       $topic_id    The topic id.
    * @param    string    $title       The post title.
    */
    public function __construct($magic, $topic_id, $title) {
        parent::__construct('forms/send-post-form.tpl');
        
        global $Filter;
        $this->data['magic']    = $magic;
        $this->data['topic_id'] = (int) $topic_id;
        $this->data['title']    = $Filter->POST($title);
        
        $this->__parse();
    }

    /**
    * Usual things, regex and parsing.
    * @access private
    */
    private function __parse() {
        $text = $this->output();

        $text = preg_replace(
            '|<%POST-TOPIC-ID%>|i',
            $this->data['topic_id'],
            $text
        );
        $text = preg_replace(
            '|<%POST-POST-TITLE%>|i',
            $this->data['title'],
            $text
        );
        $text = preg_replace(
            '|<%MAGIC%>|i',
            $this->data['magic'],
            $text
        );
        
        $this->parsed = $text;
    }
}
?>
