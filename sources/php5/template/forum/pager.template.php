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
* Pager template class.

* @author cHoBi
*/
class PagerTemplate extends Template {
    private $currentPage;
    private $pagesNumber;

    /**
    * Sets the type the current page and the max pages.

    * @param    string    $type           The page type, section or topic.
    * @param    int       $currentPage    The current page.
    * @param    int       $pagesNumber    The pages number.
    */
    public function __construct($type, $currentPage, $pagesNumber) {
        parent::__construct("forum/pager/{$type}-pager.tpl");

        $this->currentPage = $currentPage;
        $this->pagesNumber = $pagesNumber;

        $this->__parse();
    }

    /**
    * @access private
    */
    private function __parse() {
        $text = $this->output();
        $text = $this->__loops($text);

        $text = preg_replace(
            '|<%PAGER-FIRST%>|i',
            $this->__first(),
            $text
        );
        $text = preg_replace(
            '|<%PAGER-PREVIOUS%>|i',
            $this->__previous(),
            $text
        );
        $text = preg_replace(
            '|<%PAGER-PAGES%>|i',
            $this->__pages(),
            $text
        );
        $text = preg_replace(
            '|<%PAGER-NEXT%>|i',
            $this->__next(),
            $text
        );
        $text = preg_replace(
            '|<%PAGER-LAST%>|i',
            $this->__last(),
            $text
        );

        $this->parsed = $text;
    }

    /**
    * @access private
    */
    private function __loops($text) {
        // Pages
        preg_match(
            '|<Pages>(.*?)</Pages>|ims',
            $text,
            $pages
        );
        $this->template['pages'] = $pages[1];

        preg_match(
            '|<Page>(.*?)</Page>|ims',
            $this->template['pages'],
            $page
        );
        $this->template['page'] = $page[1];

        preg_match(
            '|<Current-Page>(.*?)</Current-Page>|ims',
            $this->template['pages'],
            $current_page
        );
        $this->template['current_page'] = $current_page[1];

        $this->template['pages'] = preg_replace(
            '|<Page>.*?</Page>|ims',
            '<%LOOP-PAGE%>',
            $this->template['pages']
        );
        $this->template['pages'] = preg_replace(
            '|<Current-Page>.*?</Current-Page>|ims',
            '',
            $this->template['pages']
        );
        $text = preg_replace(
            '|<Pages>.*?</Pages>|ims',
            '<%PAGER-PAGES%>',
            $text
        );

        // First
        preg_match(
            '|<First>(.*?)</First>|ims',
            $text,
            $first
        );
        $this->template['first'] = $first[1];

        preg_match(
            '|<Yes>(.*?)</Yes>|ims',
            $this->template['first'],
            $first
        );
        $this->template['first_yes'] = $first[1];

        preg_match(
            '|<No>(.*?)</No>|ims',
            $this->template['first'],
            $first
        );
        $this->template['first_no'] = $first[1];

        $this->template['first'] = preg_replace(
            '|<Yes>.*?</Yes>|ims',
            '<%FIRST%>',
            $this->template['first']
        );
        $this->template['first'] = preg_replace(
            '|<No>.*?</No>|ims',
            '',
            $this->template['first']
        );

        // Previous
        preg_match(
            '|<Previous>(.*?)</Previous>|ims',
            $text,
            $previous
        );
        $this->template['previous'] = $previous[1];

        preg_match(
            '|<Yes>(.*?)</Yes>|ims',
            $this->template['previous'],
            $previous
        );
        $this->template['previous_yes'] = $previous[1];

        preg_match(
            '|<No>(.*?)</No>|ims',
            $this->template['previous'],
            $previous
        );
        $this->template['previous_no'] = $previous[1];

        $this->template['previous'] = preg_replace(
            '|<Yes>.*?</Yes>|ims',
            '<%PREVIOUS%>',
            $this->template['previous']
        );
        $this->template['previous'] = preg_replace(
            '|<No>.*?</No>|ims',
            '',
            $this->template['previous']
        );

        // Next
        preg_match(
            '|<Next>(.*?)</Next>|ims',
            $text,
            $next
        );
        $this->template['next'] = $next[1];

        preg_match(
            '|<Yes>(.*?)</Yes>|ims',
            $this->template['next'],
            $next
        );
        $this->template['next_yes'] = $next[1];

        preg_match(
            '|<No>(.*?)</No>|ims',
            $this->template['next'],
            $next
        );
        $this->template['next_no'] = $next[1];

        $this->template['next'] = preg_replace(
            '|<Yes>.*?</Yes>|ims',
            '<%NEXT%>',
            $this->template['next']
        );
        $this->template['next'] = preg_replace(
            '|<No>.*?</No>|ims',
            '',
            $this->template['next']
        );

        // Last
        preg_match(
            '|<Last>(.*?)</Last>|ims',
            $text,
            $last
        );
        $this->template['last'] = $last[1];

        preg_match(
            '|<Yes>(.*?)</Yes>|ims',
            $this->template['last'],
            $last
        );
        $this->template['last_yes'] = $last[1];

        preg_match(
            '|<No>(.*?)</No>|ims',
            $this->template['last'],
            $last
        );
        $this->template['last_no'] = $last[1];

        $this->template['last'] = preg_replace(
            '|<Yes>.*?</Yes>|ims',
            '<%LAST%>',
            $this->template['last']
        );
        $this->template['last'] = preg_replace(
            '|<No>.*?</No>|ims',
            '',
            $this->template['last']
        );

        $text = preg_replace(
            '|<First>.*?</First>|ims',
            '<%PAGER-FIRST%>',
            $text
        );
        $text = preg_replace(
            '|<Previous>.*?</Previous>|ims',
            '<%PAGER-PREVIOUS%>',
            $text
        );
        $text = preg_replace(
            '|<Next>.*?</Next>|ims',
            '<%PAGER-NEXT%>',
            $text
        );
        $text = preg_replace(
            '|<Last>.*?</Last>|ims',
            '<%PAGER-LAST%>',
            $text
        );

        return $text;
    }

    /**
    * @access private
    */
    private function __pager($text) {
        $pager = $text;



        $text = preg_replace(
            '|<%PAGER%>|i',
            $pager,
            $text
        );

        return $text;
    }

    /**
    * @access private
    */
    private function __first() {
        $text = $this->template['first'];

        if ($this->currentPage == 1) {
            $first = $this->template['first_no'];
        }
        else {
            $first = $this->template['first_yes'];
        }
        $first = preg_replace(
            '|<%FIRST-PAGE%>|i',
            1,
            $first
        );
        
        $text = preg_replace(
            '|<%FIRST%>|i',
            $first,
            $text
        );

        return $text;
    }
    
    /**
    * @access private
    */
    private function __previous() {
        $text = $this->template['previous'];

        if ($this->currentPage == 1) {
            $previous = $this->template['previous_no'];
        }
        else {
            $previous = $this->template['previous_yes'];
        }
        $previous = preg_replace(
            '|<%PREVIOUS-PAGE%>|i',
            $this->currentPage - 1,
            $previous
        );

        $text = preg_replace(
            '|<%PREVIOUS%>|i',
            $previous,
            $text
        );

        return $text;
    }

    /**
    * @access private
    */
    private function __pages() {
        $text = $this->template['pages'];

        $pages = '';
        for ($page = 1; $page <= $this->pagesNumber; $page++) {
            $pages .= $this->__page($page);
        }

        $text = preg_replace(
            '|<%LOOP-PAGE%>|i',
            $pages,
            $text
        );

        return $text;
    }

    /**
    * @access private
    */
    private function __page($page) {
        if ($page == $this->currentPage) {
            $text = $this->template['current_page'];
        }
        else {
            $text = $this->template['page'];
        }

        $text = preg_replace(
            '|<%PAGE%>|ims',
            $page,
            $text
        );

        return $text;
    }

    /**
    * @access private
    */
    private function __next() {
        $text = $this->template['next'];

        if ($this->currentPage == $this->pagesNumber) {
            $next = $this->template['next_no'];
        }
        else {
            $next = $this->template['next_yes'];
        }
        $next = preg_replace(
            '|<%NEXT-PAGE%>|i',
            $this->currentPage + 1,
            $next
        );

        $text = preg_replace(
            '|<%NEXT%>|i',
            $next,
            $text
        );

        return $text;
    }

    /**
    * @access private
    */
    private function __last() {
        $text = $this->template['last'];

        if ($this->currentPage == $this->pagesNumber) {
            $last = $this->template['last_no'];
        }
        else {
            $last = $this->template['last_yes'];
        }
        $last = preg_replace(
            '|<%LAST-PAGE%>|i',
            $this->pagesNumber,
            $last
        );

        $text = preg_replace(
            '|<%LAST%>|i',
            $last,
            $text
        );

        return $text;
    }

}
