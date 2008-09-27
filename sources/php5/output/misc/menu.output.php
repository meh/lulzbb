<?php
/**
* @package Core-PHP5
* @category Output

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

include_once(SOURCES_PATH.'/output/output.class.php');
include_once(SOURCES_PATH.'/template/misc/menu.template.php');

/**
* Menu class.

* @author cHoBi
*/
class Menu extends Output
{
    /**
    * Create the menu.
    */
    public function __construct ()
    {
        parent::__construct();
        $this->__update();
    }

    /**
    * Sets the right output if the user is connected or something else.
    */
    protected function __update ()
    {
        if ($this->connected) {
            global $User;

            $group    = $User->highestGroup();
            $menus    = $this->__parseMenu($group['name']);
            $template = new MenuTemplate($menus);
        }
        else {
            $menus    = $this->__parseMenu('guest');
            $template = new MenuTemplate($menus);
        }
        
        $this->output = $template->output();
    }

    private function __parseMenu($group)
    {
        $group = strtolower($group);

        $text = read_file(CONTENT_PATH."/menu/{$group}.php");
        $dom  = dom_import_simplexml(simplexml_load_string($text))->ownerDocument;

        $menus    = array();
        $menusDom = $dom->firstChild;

        for ($i = 0; $i < $menusDom->childNodes->length; $i++) {
            $element = $menusDom->childNodes->item($i);

            if ($element->nodeType == XML_ELEMENT_NODE) {
                $name = $element->nodeName;
                
                for ($h = 0; $h < $element->childNodes->length; $h++) {
                    $menuDom = $element->childNodes->item($h);

                    if ($menuDom->nodeType == XML_ELEMENT_NODE) {
                        $level   = $menuDom->getAttribute('level');
                        $menus[$name][$level]['url']   = $this->__getLink($menuDom);
                        $menus[$name][$level]['name'] = $menuDom->getAttribute('name');
                    }
                }
            }
        }

        return $menus;
    }

    private function __getLink ($element)
    {
        $attrsList = array(
            'href', 'style', 'id', 'class', 'title', 'target',
            'onclick', 'onmouseover'
        );

        $link = '<a';
        foreach ($attrsList as $attrName) {
            $attr = $element->getAttribute($attrName);
            if (!empty($attr)) {
                $link .= " {$attrName}=\"{$attr}\"";
            }
        }
        $link .= ">{$element->nodeValue}</a>";

        return $link;
    }
}
?>
