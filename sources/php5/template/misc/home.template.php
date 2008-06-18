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

require_once(SOURCE_PATH.'/template/template.class.php');

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
    public function __construct ($content = '')
    {
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
    private function __parse ()
    {
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
