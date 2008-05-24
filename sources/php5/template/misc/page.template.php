<?php
/**
* @package lulzBB-PHP5
* @category Template

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/template/template.class.php');

/**
* Page template.

* @author cHoBi
*/
class PageTemplate extends Template {
    /**
    * Sets the file to get and parse it.

    * @param    string    $file    The file to get.
    */
    public function __construct($file) {
        parent::__construct('misc/page.tpl');

        $file = preg_replace('|\.\./|', '', $file);
        @$this->data['content'] = new Template("/pages/{$file}");

        $this->__parse();
    }

    /**
    * Puts the gotten page into the template variable.
    * @access private
    */
    private function __parse() {
        $text = $this->output();
    
        $text = preg_replace(
            '|<%CONTENT%>|i',
            $this->data['content']->output(),
            $text
        );

        $this->parsed = $text;
    }
}
?>
