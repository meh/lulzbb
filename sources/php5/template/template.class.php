<?php
/**
* @package PHP5
* @category Template

* @license http://opensource.org/licenses/gpl-3.0.html
*/

/**
* Template base class.

* @author cHoBi
*/
class Template {
    protected $data;
    protected $template;
    protected $plain_text;
    protected $parsed;
    protected $magic;
    protected $connected;

    /**
    * Initialize the template by reading the content of the template file
    * and doing basic parsing.

    * @param    string    $file    The relative path of the template.
    */
    public function __construct($file) {
        global $Config;
        
        $this->template['language']   = $Config->get('language');
        $this->template['forumName']  = $Config->get('forumName');
        $this->template['forumTitle'] = $Config->get('forumTitle');
        $this->template['name']       = $Config->get('template');
        
        $this->magic = $_SESSION[SESSION]['magic'];
        
        if (isset($_SESSION[SESSION]['user'])) {
            $this->connected = true;
        }
        else {
            $this->connected = false;
        }
        
        if ($file[0] == '/' || ereg('^\.+/', $file)) {
            $file = preg_replace('#\.+/+|^/#', '', $file);
            $this->plain_text = file_get_contents(ROOT_PATH."/{$file}");
        }
        else {
            $this->plain_text = file_get_contents(ROOT_PATH."/templates/{$this->template['name']}/$file");
        }
        
        $this->___parse();
    }

    /**
    * Parsing of the basic template variables.
    * @access private
    */
    protected function ___parse() {
        global $User;

        $text = $this->plain_text;

        $text = preg_replace(
            '|<%FORUM-TITLE%>|i',
            $this->template['forumTitle'],
            $text
        );

        $text = preg_replace(
            '|<%FORUM-NAME%>|i',
            $this->template['forumName'],
            $text
        );

        $text = preg_replace(
            '|<%TEMPLATE_NAME%>|i',
            $this->template['name'],
            $text
        );

        $text = preg_replace(
            '|<%LANG%>|i',
            $this->template['language'],
            $text
        );

        if ($this->connected) {
            $text = preg_replace(
                '|<%USERNAME%>|i',
                $User->getName('HTML'),
                $text
            );
        }
        else {
            $text = preg_replace(
                '|<%USERNAME%>|i',
                'Anonymous',
                $text
            );
        }
  
        $this->parsed = $text;
    }
    
    /**
    * Get a template part.

    * @param    string    $part    The name of the part.

    * @return     mixed    string: The template part if it exists.
    *                      bool: false if the template part doesn't exist.
    */
    public function getTemplatePart($part) {
        if (isset($this->data['template'][$part])) {
            return $this->data['template'][$part];
        }
        else {
            return false;
        }
    }

    /**
    * Return the output.

    * @return    string    The output.
    */
    public function output() {
        return $this->parsed;
    }
}
?>
