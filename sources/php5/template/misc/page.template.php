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
* Page template.

* @author cHoBi
*/
class PageTemplate extends Template
{
    private $mode;

    /**
    * Sets the file to get and parse it.

    * @param    string    $file    The file to get.
    * @param    string    $mode    The page view mode.
    */
    public function __construct ($file, $mode = 'default')
    {
        parent::__construct('misc/page.tpl');

        $this->mode = $mode;

        $file = preg_replace('|\.+/+|', '', $file);
        $this->data['content'] = new Template("/pages/{$file}");

        $this->__parse();
    }

    /**
    * Puts the gotten page into the template variable.
    * @access private
    */
    private function __parse ()
    {
        $text = $this->output();

        switch ($this->mode) {
            case 'default':
            $text = preg_replace(
                '|<%CONTENT%>|i',
                $this->data['content']->output(),
                $text
            );
            break;

            case 'raw':
            $text = $this->data['content']->output();
            break;
        }

        $this->parsed = $text;
    }
}
?>
