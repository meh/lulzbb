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
* Post form template class.

* @author cHoBi
*/
class PostFormTemplate extends Template {
    /**
    * Initialize the data.

    * @param    string    $magic       The magic token . (Anti XSRF)
    * @param    int       $topic_id    The topic id.
    * @param    string    $title       The post title.
    */
    public function __construct($magic, $topic_id, $title) {
        parent::__construct('forms/send-post-form.tpl');
        
        global $Filter;
        $this->data['magic']    = $magic;
        $this->data['topic_id'] = (int) $topic_id;
        $this->data['title']    = $Filter->POST($title);
        
        $this->__parse();
    }

    /**
    * Usual things, regex and parsing.
    * @access private
    */
    private function __parse() {
        $text = $this->output();

        $text = preg_replace(
            '|<%POST-TOPIC-ID%>|i',
            $this->data['topic_id'],
            $text
        );
        $text = preg_replace(
            '|<%POST-POST-TITLE%>|i',
            $this->data['title'],
            $text
        );
        $text = preg_replace(
            '|<%MAGIC%>|i',
            $this->data['magic'],
            $text
        );
        
        $this->parsed = $text;
    }
}
?>
