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
    /**
    * Create the topic form template.

    * @param    string    $magic     The magic token. (Anti XSRF)
    * @param    int       $parent    The parent where to add the topic.
    */
    public function __construct($magic, $parent) {
        parent::__construct('forms/send-topic-form.tpl');

        global $Filter;
        $this->data['magic']   = $magic;
        $this->data['parent']  = (int) $parent;

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
            $this->data['parent'],
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
