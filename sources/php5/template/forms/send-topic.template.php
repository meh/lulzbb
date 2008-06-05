<?php
/**
* @package PHP5
* @category Template

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/template/template.class.php');

/**
* Topic form template class.

* @author cHoBi
*/
class TopicFormTemplate extends Template {
    private $parent;

    /**
    * Create the topic form template.

    * @param    string    $magic     The magic token. (Anti XSRF)
    * @param    int       $parent    The parent where to add the topic.
    */
    public function __construct($parent) {
        parent::__construct('forms/send-topic-form.tpl');

        $this->parent = (int) $parent;

        $this->__parse();
    }

    /**
    * Parse the template.
    * @access private
    */
    private function __parse() {
        $text = $this->output();

        $text = preg_replace(
            '|<%POST-PARENT%>|i',
            $this->parent,
            $text
        );
        $text = preg_replace(
            '|<%MAGIC%>|i',
            $this->magic,
            $text
        );

        $this->parsed = $text;
    }
}
?>
