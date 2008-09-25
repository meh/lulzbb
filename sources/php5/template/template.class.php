<?php
/**
* @package Core-PHP5
* @category Template

* @license AGPLv3
* lulzBB is a CMS for the lulz but it's also serious business.
* Copyright (C) 2008 lulzGroup
*
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
*/

/**
* Template base class.

* @author cHoBi
*/
class Template
{
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
    public function __construct ($file)
    {
        global $Config;
        
        $this->template['language']  = $Config->get('language');
        $this->template['siteName']  = $Config->get('siteName');
        $this->template['siteTitle'] = $Config->get('siteTitle');
        $this->template['name']      = $Config->get('template');

        if (!is_dir(ROOT_PATH."/templates/{$this->template['name']}")) {
            die("The template doesn't exist.");
        }
        
        $this->magic = $_SESSION[SESSION]['magic'];
        
        if (isset($_SESSION[SESSION]['user'])) {
            $this->connected = true;
        }
        else {
            $this->connected = false;
        }
        
        if (ereg('^/pages/', $file)) {
            $file = preg_replace('|\.+/+|', '', $file);
            $file = preg_replace('|^/pages/|', '', $file);

            if (ini_get('allow_url_fopen')) {
                $this->plain_text = file_get_contents("http://{$_SERVER['HTTP_HOST']}".WEB_PATH."/content/pages/{$file}");
            }
            else {
                $this->plain_text = file_get_contents(CONTENT_PATH."/pages/{$file}");
            }
        }
        else {
            $this->plain_text = file_get_contents(ROOT_PATH."/templates/{$this->template['name']}/{$file}");
        }
        
        $this->__parse();
    }

    /**
    * Parsing of the basic template variables.
    * You should call this in the constructor of the extended class.
    * @access private
    */
    private function __parse ()
    {
        global $User;

        $text = $this->plain_text;

        $text = preg_replace(
            '|<%HOST%>|i',
            $_SERVER['HTTP_HOST'],
            $text
        );

        $text = preg_replace(
            '|<%FORUM-TITLE%>|i',
            $this->template['siteTitle'],
            $text
        );

        $text = preg_replace(
            '|<%FORUM-NAME%>|i',
            $this->template['siteName'],
            $text
        );

        $text = preg_replace(
            '|<%TEMPLATE-PATH%>|i',
            "templates/{$this->template['name']}",
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
    *                      bool:   False if the template part doesn't exist.
    */
    public function getTemplatePart ($part)
    {
        if (isset($this->template[$part])) {
            return $this->template[$part];
        }
        else {
            return false;
        }
    }

    /**
    * Return the output.

    * @return    string
    */
    public function output ()
    {
        return $this->parsed;
    }
}
?>
