<?php
/**
* @package PHP5
* @category Show

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
include_once($M_SOURCES_PATH.'/template/topic.template.php');
include_once($M_SOURCES_PATH.'/misc/lulzcode.class.php');

/**
* Topic show class.

* @author cHoBi
*/
class Topic extends Output
{
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
    public function __construct ($topic_id, $page, $post_id)
    {
        global $Database;
        parent::__construct();
        
        $infos = $Database->_('forum')->topic->getInfos($topic_id);

        $this->topic_id = (int) $topic_id;
        $this->post_id  = (int) $post_id;
        
        if ($page == 'first') {
            $this->page = 1;
        }
        else if ($page == 'last') {
            $this->page = $Database->_('forum')->topic->getPages($topic_id);
        }

        $this->page = (int) $this->page;
        if ($this->page < 1) {
            $this->page = 1;
        }

        $this->parent = (int) $infos['parent']['RAW'];

        $this->__update();
    }

    /**
    * Get the posts and show error in case they happen.
    */
    protected function __update ()
    {
        global $Database;

        try {
            if ($Database->_('forum')->topic->exists($this->topic_id)) {
                $posts = $Database->_('forum')->topic->getPosts($this->topic_id, $this->page);
                $Database->_('forum')->topic->increaseViewsCount($this->topic_id);
            }
            else {
                die("The topic doesn't exist.");
            }
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }
/*
        foreach ($posts as $n => $post) {
            if ($post['bbcode']) {
                $posts[$n]['content'] = lulzCode::arrayParse($post['content']);
            }
        }
*/
        $template = new TopicTemplate(
            array(
                'id'     => $this->topic_id,
                'title'  => $Database->_('forum')->topic->getTitle($this->topic_id)
#                'locked' => $Database->_('forum')->topic->isLocked($this->topic_id),
            ),
            array(
                'page'   => $this->page,
                'number' => $Database->_('forum')->topic->getPages($this->topic_id)
            ),
            array(
                'id'    => $this->post_id,
                'posts' => $posts
            )
        );

        $this->output = $template->output();
    }
}
?>
