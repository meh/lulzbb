<?php
/**
* @package PHP5
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

require_once($M_SOURCES_PATH.'/template/template.class.php');

/**
* Navigator template class.

* @author cHoBi
*/
class NavigatorTemplate extends Template
{
    /**
    * Initialize the data.
    
    * @param    array    $elements    The elements.
    */
    public function __construct ($elements)
    {
        parent::__construct('forum/navigator.tpl');
        
        $this->data['elements'] = $elements;
        
        $this->__parse();
    }

    /**
    * Creates the template through parsing the data in the array with the
    * template vars.

    * @access private
    */
    private function __parse ()
    {
        $text = $this->output();
        $text = $this->__loops($text);

        $elementsText   = '';
        $elementsNumber = count($this->data['elements']);
        foreach ($this->data['elements'] as $number => $element) {
            if ($number != $elementsNumber-1) {
                $elementsText .= $this->__element('url', $element);
            }
            else {
                $elementsText .= $this->__element('last', $element);
            }
        }

        $text = preg_replace(
            '|<%LOOP-NAVIGATOR%>|i',
            $elementsText,
            $text
        );

        $this->parsed = $text;
    }

    /**
    * Get the parsed data from the template.

    * @param    string    $text    The text to parse.

    * @return    string    The parsed text.
    * @access private
    */
    private function __loops ($text)
    {
        if (preg_match_all(
                '|<URL>(.+?)?</URL>|ims',
                $text,
                $url) != 1) {
            die('The template has an error. (There MUST be 1 URL loop)');
        }
        $this->data['template']['url'] = $url[1][0];
        
        if (preg_match_all(
                '|<Last>(.+?)?</Last>|ims',
                $text,
                $last) != 1) {
            die('The template has an error. (There MUST be 1 Last loop)');
        }
        $this->data['template']['last'] = $last[1][0];

        $text = preg_replace(
            '|<URL>(.+?)?</URL>|ims',
            '<%LOOP-NAVIGATOR%>',
            $text
        );
        $text = preg_replace(
            '|<Last>(.+?)?</Last>|ims',
            '',
            $text
        );

        return $text;
    }

    /**
    * Gets an element parsed.

    * @param    string    $mode       The mode. url | last
    * @param    array     $element    The element.

    * @return    string    The element parsed.
    * @access private
    */
    private function __element ($mode, $element)
    {
        $text = $this->data['template'][$mode];

        $text = preg_replace(
            '|<%LINK-ID%>|i',
            $element['id'],
            $text
        );

        $text = preg_replace(
            '|<%LINK-TEXT%>|i',
            $element['name']['HTML'],
            $text
        );

        $text = preg_replace(
            '|<%LINK-TYPE%>|i',
            $element['type'],
            $text
        );

        $text = preg_replace(
            '|<%LINK-URL%>|i',
            "/?forum&{$element['type']}&id={$element['id']}",
            $text
        );

        return $text;
    }
}
?>
