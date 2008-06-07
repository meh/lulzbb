<?php
/**
* @package PHP5
* @category Show

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

require_once(SOURCE_PATH.'/show/show.class.php');
require_once(SOURCE_PATH.'/template/forum/topic.template.php');
require_once(SOURCE_PATH.'/database/database.class.php');

/**
* Topic show class.

* @author cHoBi
*/
class TopicShow extends Show {
    private $parent;
    private $topic_id;
    private $post_id;
    private $page;

    /**
    * Get the post and show them in the template.

    * @param    int    $parent      The topic's parent.
    * @param    int    $topic_id    The topic id.
    * @param    int    $post_id     The post id.
    */
    public function __construct($parent, $topic_id, $page, $post_id) {
        parent::__construct();

        $this->parent    = (int) $parent;
        $this->topic_id  = (int) $topic_id;
        $this->page      = (int) $page;
        $this->post_id   = (int) $post_id;

        $this->__update();
    }

    /**
    * Get the posts and show error in case they happen.
    * @access private
    */
    protected function __update() {
        global $Database;

        try {
            if ($Database->topic->exists($this->topic_id)) {
                $posts = $Database->topic->getPosts($this->topic_id, $this->page);
            }
            else {
                die("The topic doesn't exist.");
            }
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }

        $template = new TopicTemplate(
            $this->topic_id,
            $this->page,
            $this->post_id,
            $posts
        );

        $this->output = $template->output();
    }
}
?>
