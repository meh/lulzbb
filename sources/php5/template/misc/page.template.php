<?php
/**
* @package PHP5
* @category Template

* @license AGPLv3
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
class PageTemplate extends Template {
    /**
    * Sets the file to get and parse it.

    * @param    string    $file    The file to get.
    */
    public function __construct($file) {
        parent::__construct('misc/page.tpl');

        $file = preg_replace('|\.+/+|', '', $file);
        $this->data['content'] = new Template("/pages/{$file}");

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
