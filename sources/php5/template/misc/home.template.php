<?php
/**
* @package lulzBB-PHP5
* @category Template

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/template/template.class.php');

/**
* The home template.

* @author cHoBi
*/
class HomeTemplate extends Template {
    /**
    * Initialize the home template.

    * @param    string    $content    The content to put inside the home.
    */
    public function __construct($content = '') {
        parent::__construct('misc/home.tpl');

        if (empty($content)) {
            $this->data['init'] = 'true';
        }
        else {
            $this->data['init']    = 'false';
            $this->data['content'] = $content;
        }

        $this->__parse();
    }

    /**
    * Replace the template variables with the right content.
    * @access private
    */
    private function __parse() {
        $text = $this->output();
    
        $top = new Template('misc/top.tpl');
        $text = preg_replace(
            '|<%TOP%>|i',
            $top->output(),
            $text
        );

        $bottom = new Template('misc/bottom.tpl');
        $text = preg_replace(
            '|<%BOTTOM%>|i',
            $bottom->output(),
            $text
        );

        $text = preg_replace(
            '|<%INIT-OR-NOT%>|i',
            $this->data['init'],
            $text
        );

        if ($this->data['init'] == 'false') {
            $text = preg_replace(
                '|<%CONTENT%>|i',
                $this->data['content'],
                $text
            );
        }

        $this->parsed = $text;
    }
}
?>
