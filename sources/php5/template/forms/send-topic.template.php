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
* Topic form template class.

* @author cHoBi
*/
class TopicFormTemplate extends Template {
    private $parent;

    /**
    * Create the topic form template.

    * @param    string    $magic     The magic token. (Anti XSRF)
    * @param    int       $parent    The parent where to add the topic.
    */
    public function __construct($parent) {
        parent::__construct('forms/send-topic-form.tpl');

        $this->parent = (int) $parent;

        $this->__parse();
    }

    /**
    * Parse the template.
    * @access private
    */
    private function __parse() {
        $text = $this->output();

        $text = preg_replace(
            '|<%POST-PARENT%>|i',
            $this->parent,
            $text
        );
        $text = preg_replace(
            '|<%MAGIC%>|i',
            $this->magic,
            $text
        );

        $this->parsed = $text;
    }
}
?>
