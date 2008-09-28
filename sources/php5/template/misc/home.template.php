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

include_once(SOURCES_PATH.'/template/template.class.php');
include_once(SOURCES_PATH.'/output/misc/menu.output.php');

/**
* The home template.

* @author cHoBi
*/
class HomeTemplate extends Template
{
    /**
    * Initialize the home template.

    * @param    string    $content    The content to put inside the home.
    */
    public function __construct ($content)
    {
        parent::__construct('misc/home.tpl');

        $this->data['content'] = $content;

        $menu               = new Menu;
        $this->data['menu'] = $menu->output();

        $this->__parse();
    }

    /**
    * Replace the template variables with the right content.
    * @access private
    */
    private function __parse ()
    {
        $text = $this->output();

        $text = preg_replace(
            '|<%MENU%>|i',
            $this->data['menu'],
            $text
        );

        $text = preg_replace(
            '|<%CONTENT%>|i',
            $this->data['content'],
            $text
        );
    
        $text = preg_replace(
            '|<%TEMPLATE-SCRIPTS%>|i',
            $this->__scripts(),
            $text
        );
        $text = preg_replace(
            '|<%TEMPLATE-STYLES%>|i',
            $this->__styles(),
            $text
        );

        $this->parsed = $text;
    }

    private function __scripts ()
    {
        $scripts = glob(TEMPLATE_PATH.'/scripts/*');

        $text = '';
        foreach ($scripts as $script) {
            $script = preg_replace('|^.+/|i', '', $script);

            $text .= "<script src='templates/{$this->template['name']}/scripts/{$script}'";
            $text .= "type='text/javascript'></script>\n";
        }

        return $text;
    }

    private function __styles ()
    {
        $styles = glob(TEMPLATE_PATH.'/styles/*');

        $text = '';
        foreach ($styles as $style) {
            $style = preg_replace('|^.+/|i', '', $style);

            $text .= "<link href='templates/{$this->template['name']}/styles/{$style}'";
            $text .= "rel='stylesheet' type='text/css'/>\n";
        }

        return $text;
    }
}
?>
