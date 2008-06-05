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
* Section template class.

* @author cHoBi
*/
class SectionTemplate extends Template {
    private $section_id;
    private $page;
    private $groups;
    private $topics;

    /**
    * Create the template of a section.

    * @param    int      $section_id    The section id.
    * @param    array    $sections      The sections data.
    * @param    array    $topics        The topics data.
    */
    public function __construct($section_id, $page, $groups, $topics) {
        parent::__construct('forum/section.tpl');
        global $Database;
        
        $this->section_id  = $section_id;
        $this->page        = $page;
        $this->pagesNumber = !empty($page) ? $Database->section->getPages($this->section_id) : 0;
        $this->groups      = $groups;
        $this->topics      = $topics;

        $this->data['title'] = $Database->section->getTitle($section_id);

        $this->__parse();
    }

    /**
    * Parse the template and fill the template variables.
    * @access private
    */
    private function __parse() {
        $text = $this->output();
        $text = $this->__loops($text);

        $sections = '';
        if (!empty($this->groups)) {
            $sectionGroups = $this->__sectionsGroup($this->groups);
            foreach ($sectionGroups as $group) {
                $sections .= $this->__sections($group);
            }
        }

        $text = preg_replace(
            '|<%LOOP-SECTIONS%>|i',
            $sections,
            $text
        );

        if ($this->section_id != 0) {
            $topics = $this->__topics($this->topics);
        }
        else {
            $topics = '';
        }

        $text = preg_replace(
            '|<%LOOP-TOPICS%>|i',
            $topics,
            $text
        );

        $text = $this->__pager($text);

        $this->parsed = $this->__common($text);
    }

    /**
    * Get the various loops withing the template.
    * @access private
    */
    private function __loops($text) {
        /**
        * The idea is to use tags like Splinder does, so you have
        * that faggot tag that opens then i get the content with some regex magic
        * and then i use that output in a cycle to get the data and put it where it goes.
        *  
        * The idea is pretty ok and i think it's the best atm, i'll think about other possible
        * implementation later. Thanks to Juliet because thinking about her made me think
        * about the last summer so about Splinder and its template system ·D
        *
        * WORKED :D Leave it here for GREAT JUSTICE
        */

        global $Filter;

        // Pager
        preg_match(
            '|<Pager>(.*?)</Pager>|ims',
            $text,
            $pager
        );
        $this->template['pager'] = $pager[1];

        // Pages
        preg_match(
            '|<Pages>(.*?)</Pages>|ims',
            $this->template['pager'],
            $pages
        );
        $this->template['pager_pages'] = $pages[1];

        preg_match(
            '|<Page>(.*?)</Page>|ims',
            $this->template['pager_pages'],
            $page
        );
        $this->template['pager_page'] = $page[1];

        preg_match(
            '|<Current-Page>(.*?)</Current-Page>|ims',
            $this->template['pager_pages'],
            $current_page
        );
        $this->template['pager_current_page'] = $current_page[1];

        $this->template['pager_pages'] = preg_replace(
            '|<Page>.*?</Page>|ims',
            '<%LOOP-PAGE%>',
            $this->template['pager_pages']
        );
        $this->template['pager_pages'] = preg_replace(
            '|<Current-Page>.*?</Current-Page>|ims',
            '',
            $this->template['pager_pages']
        );
        $this->template['pager'] = preg_replace(
            '|<Pages>.*?</Pages>|ims',
            '<%PAGER-PAGES%>',
            $this->template['pager']
        );

        // First
        preg_match(
            '|<First>(.*?)</First>|ims',
            $this->template['pager'],
            $first
        );
        $this->template['pager_first'] = $first[1];

        preg_match(
            '|<Yes>(.*?)</Yes>|ims',
            $this->template['pager_first'],
            $first
        );
        $this->template['pager_first_yes'] = $first[1];

        preg_match(
            '|<No>(.*?)</No>|ims',
            $this->template['pager_first'],
            $first
        );
        $this->template['pager_first_no'] = $first[1];

        $this->template['pager_first'] = preg_replace(
            '|<Yes>.*?</Yes>|ims',
            '<%FIRST%>',
            $this->template['pager_first']
        );
        $this->template['pager_first'] = preg_replace(
            '|<No>.*?</No>|ims',
            '',
            $this->template['pager_first']
        );

        // Previous
        preg_match(
            '|<Previous>(.*?)</Previous>|ims',
            $this->template['pager'],
            $previous
        );
        $this->template['pager_previous'] = $previous[1];

        preg_match(
            '|<Yes>(.*?)</Yes>|ims',
            $this->template['pager_previous'],
            $previous
        );
        $this->template['pager_previous_yes'] = $previous[1];

        preg_match(
            '|<No>(.*?)</No>|ims',
            $this->template['pager_previous'],
            $previous
        );
        $this->template['pager_previous_no'] = $previous[1];

        $this->template['pager_previous'] = preg_replace(
            '|<Yes>.*?</Yes>|ims',
            '<%PREVIOUS%>',
            $this->template['pager_previous']
        );
        $this->template['pager_previous'] = preg_replace(
            '|<No>.*?</No>|ims',
            '',
            $this->template['pager_previous']
        );

        // Next
        preg_match(
            '|<Next>(.*?)</Next>|ims',
            $this->template['pager'],
            $next
        );
        $this->template['pager_next'] = $next[1];

        preg_match(
            '|<Yes>(.*?)</Yes>|ims',
            $this->template['pager_next'],
            $next
        );
        $this->template['pager_next_yes'] = $next[1];

        preg_match(
            '|<No>(.*?)</No>|ims',
            $this->template['pager_next'],
            $next
        );
        $this->template['pager_next_no'] = $next[1];

        $this->template['pager_next'] = preg_replace(
            '|<Yes>.*?</Yes>|ims',
            '<%NEXT%>',
            $this->template['pager_next']
        );
        $this->template['pager_next'] = preg_replace(
            '|<No>.*?</No>|ims',
            '',
            $this->template['pager_next']
        );

        // Last
        preg_match(
            '|<Last>(.*?)</Last>|ims',
            $this->template['pager'],
            $last
        );
        $this->template['pager_last'] = $last[1];

        preg_match(
            '|<Yes>(.*?)</Yes>|ims',
            $this->template['pager_last'],
            $last
        );
        $this->template['pager_last_yes'] = $last[1];

        preg_match(
            '|<No>(.*?)</No>|ims',
            $this->template['pager_last'],
            $last
        );
        $this->template['pager_last_no'] = $last[1];

        $this->template['pager_last'] = preg_replace(
            '|<Yes>.*?</Yes>|ims',
            '<%LAST%>',
            $this->template['pager_last']
        );
        $this->template['pager_last'] = preg_replace(
            '|<No>.*?</No>|ims',
            '',
            $this->template['pager_last']
        );

        $this->template['pager'] = preg_replace(
            '|<First>.*?</First>|ims',
            '<%PAGER-FIRST%>',
            $this->template['pager']
        );
        $this->template['pager'] = preg_replace(
            '|<Previous>.*?</Previous>|ims',
            '<%PAGER-PREVIOUS%>',
            $this->template['pager']
        );
        $this->template['pager'] = preg_replace(
            '|<Next>.*?</Next>|ims',
            '<%PAGER-NEXT%>',
            $this->template['pager']
        );
        $this->template['pager'] = preg_replace(
            '|<Last>.*?</Last>|ims',
            '<%PAGER-LAST%>',
            $this->template['pager']
        );

        $text = preg_replace(
            '|<Pager>.+?</Pager>|ims',
            '<%PAGER%>',
            $text
        );
        
        // Sections
        if (preg_match_all(
                '|<Sections>(.*?)</Sections>|ims',
                $text,
                $sections) != 1) {
            die('The template has an error. (There MUST be 1 Sections loop)');
        }
        $this->template['sections'] = $sections[1][0];

        // Sections group
        if (preg_match_all(
                '|<Sections-Group>(.*?)</Sections-Group>|ims',
                $this->template['sections'],
                $sections_group) != 1) {
            die('The template has an error. (There MUST be 1 Sections-Group loop)');
        }
        $this->template['sections_group'] = $sections_group[1][0];

        // Section group header
        if (preg_match_all(
                '|<Group-Header>(.*?)</Group-Header>|ims',
                $this->template['sections_group'],
                $group_header) != 1) {
            die('The template has an error. (There MUST be 1 Group-Header loop)');
        }
        $this->template['group_header'] = $group_header[1][0];

        // Section group content
        if (preg_match_all(
                '|<Section-Content>(.*?)</Section-Content>|ims',
                $this->template['sections_group'],
                $section_content) != 1) {
            die('The template has an error. (There MUST be 1 Section-Content loop)');
        }
        $this->template['section_content'] = $section_content[1][0];

        if (preg_match_all(
                '|<Last-Info>(.*?)</Last-Info>|ims',
                $this->template['section_content'],
                $last_info) != 1) {
            die('The template has an error. (There MUST be 1 Last-Info)');
        }
        $this->template['last_info'] = $last_info[1][0];

        if (preg_match_all(
                '|<No-Info>(.*?)</No-Info>|ims',
                $this->template['section_content'],
                $no_info) != 1) {
            die('The template has an error. (There MUST be 1 No-Info)');
        }
        $this->template['no_info'] = $no_info[1][0];

        $this->template['section_content']
            = preg_replace(
                '|<Last-Info>.*?</Last-Info>|ims',
                '<%LAST-INFO%>',
                $this->template['section_content']
        );
        $this->template['section_content']
            = preg_replace(
                '|<No-Info>.*?</No-Info>|ims',
                '',
                $this->template['section_content']
        );

        // Section group footer
        if (preg_match_all(
                '|<Group-Footer>(.*?)</Group-Footer>|ims',
                $this->template['sections_group'],
                $group_footer) != 1) {
            die('The template has an error. (There MUST be 1 Group-Footer loop)');
        }
        $this->template['group_footer'] = $group_footer[1][0];

        $this->template['sections_group']
            = preg_replace(
                '|<Group-Header>.+?</Group-Header>|ims',
                '<%LOOP-GROUP-HEADER%>',
                $this->template['sections_group']
        );
        $this->template['sections_group']
            = preg_replace(
                '|<Section-Content>.*?</Section-Content>|ims',
                '<%LOOP-GROUP-CONTENT%>',
                $this->template['sections_group']
        );
        $this->template['sections_group']
            = preg_replace(
                '|<Group-Footer>.*?</Group-Footer>|ims',
                '<%LOOP-GROUP-FOOTER%>',
                $this->template['sections_group']
        );
        $this->template['sections']
            = preg_replace(
                '|<Sections-Group>.*?</Sections-Group>|ims',
                '<%LOOP-SECTIONS-GROUP%>',
                $this->template['sections']
        );

        // Topics
        if (preg_match_all(
                '|<Topics>(.*?)</Topics>|ims',
                $text,
                $topics) != 1) {
            die('The template has an error. (There MUST be 1 Topics loop)');
        }
        $this->template['topics'] = $topics[1][0];

        // Topic
        if (preg_match_all(
                '|<Topic>(.*?)</Topic>|ims', 
                $this->template['topics'],
                $topic) != 1) {
            die('The template has an error. (There MUST be 1 Topic loop)');
        }
        $this->template['topic'] = $topic[1][0];

        // No topics
        if (preg_match_all(
                '|<No-Topic>(.*?)</No-Topic>|ims',
                $this->template['topics'],
                $no_topics) != 1) {
            die('The template has an error. (There MUST be 1 No-Topic loop)');
        }
        $this->template['no_topics'] = $no_topics[1][0];
        
        $this->template['topics']
            = preg_replace(
                '|<Topic>(.*?)</Topic>|ims',
                '<%LOOP-TOPIC%>',
                $this->template['topics']
        );
        $this->template['topics']
            = preg_replace(
                '|<No-Topic>(.*?)</No-Topic>|ims',
                '',
                $this->template['topics']
        );
        
        $text = preg_replace(
                '|<Sections>.*?</Sections>|ims',
                '<%LOOP-SECTIONS%>',
                $text
        );
        $text = preg_replace(
                '|<Topics>.*?</Topics>|ims',
                '<%LOOP-TOPICS%>',
                $text
        );

        if (preg_match_all(
                '|<New-Topic>(.*?)</New-Topic>|ims',
                $text,
                $new_topic) != 1) {
            die('The template has an error. (There MUST be 1 New-Topic)');
        }
        $this->template['new_topic'] = $new_topic[1][0];

        $text = preg_replace(
                '|<New-Topic>.*?</New-Topic>|i',
                '<%NEW-TOPIC%>',
                $text
        );

        return $text;
    }

    /**
    * Parse the group header.
    * @access private
    */
    private function __groupHeader($group) {
        $text = $this->template['group_header'];
        
        $text = preg_replace(
            '|<%GROUP-TITLE%>|i',
            $group['name']['HTML'],
            $text
        );

        return $text;
    }

    /**
    * Parse the section content.
    * @access private
    */
    private function __sectionContent($section) {
        $text = $this->template['section_content'];

        $text = preg_replace(
            '|<%SECTION-TITLE%>|i',
            $section['title']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%SECTION-SUBTITLE%>|i',
            (empty($section['subtitle']['HTML']) ? '&nbsp;' : $section['subtitle']['HTML']),
            $text
        );
        $text = preg_replace(
            '|<%SECTION-TOPICS-COUNT%>|i',
            $section['count_topics']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%SECTION-POSTS-COUNT%>|i',
            $section['count_posts']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%LAST-INFO%>|i',
            $this->__lastInfo($section),
            $text
        );

        $text = preg_replace(
            '|<%POST-SECTION-ID%>|i',
            $section['id']['POST'],
            $text
        );
        
        return $text;
    }

    /**
    * Parse the last info.
    * @access private
    */
    private function __lastInfo($section) {
        if (!empty($section['last_user_name']['HTML'])) {
            $text = $this->template['last_info'];

            $text = preg_replace(
                '|<%SECTION-LAST-TOPIC-TITLE%>|i',
                $section['last_topic_title']['HTML'],
                $text
            );
            $text = preg_replace(
                '|<%SECTION-LAST-USER-NICK%>|i',
                $section['last_user_name']['HTML'],
                $text
            );
            $text = preg_replace(
                '|<%SECTION-LAST-POST-TIME%>|i',
                $section['last_post_time']['HTML'],
                $text
            );
            $text = preg_replace(
                '|<%POST-SECTION-LAST-TOPIC-ID%>|i',
                $section['last_topic_id']['POST'],
                $text
            );
            $text = preg_replace(
                '|<%POST-SECTION-LAST-POST-ID%>|i',
                $section['last_post_id']['POST'],
                $text
            );
        }
        else {
            $text = $this->template['no_info'];
        }

        return $text;
    }

    /**
    * Parse the group footer.
    * @access private
    */
    private function __groupFooter($section) {
        $text = $this->template['group_footer'];
        return $text;
    }

    /**
    * Parse the sections group.
    * @access private
    */
    private function __sectionsGroup($groups) {
        $rGroups = array();

        if (empty($groups)) {
            return '';
        }

        foreach ($groups as $group) {
            $groupHeader = $this->__groupHeader($group);
            $sectionContent = '';
            foreach ($group['data'] as $section) {
                $sectionContent .= $this->__sectionContent($section);
            }
            $groupFooter = $this->__groupFooter($group);

            array_push(
                $rGroups,
                $this->__group(
                    $groupHeader,
                    $sectionContent,
                    $groupFooter
                )
            );
        }

        return $rGroups;
    }

    /**
    * Parse the group.
    * @access private
    */
    private function __group($header, $content, $footer) {
        $text = $this->template['sections_group'];

        $text = preg_replace(
            '|<%LOOP-GROUP-HEADER%>|i',
            $header,
            $text
        );

        $text = preg_replace(
            '|<%LOOP-GROUP-CONTENT%>|i',
            $content,
            $text
        );

        $text = preg_replace(
            '|<%LOOP-GROUP-FOOTER%>|i',
            $footer,
            $text
        );

        return $text;
    }

    /**
    * Parse the sections.
    * @access private
    */
    private function __sections($group) {
        $text = $this->template['sections'];

        $text = preg_replace(
            '|<%LOOP-SECTIONS-GROUP%>|i',
            $group,
            $text
        );

        return $text;
    }

    /**
    * Parse the topics.
    * @access private
    */
    private function __topics($topics) {
        $text = $this->template['topics'];

        $text = preg_replace(
            '|<%SECTION-TITLE%>|i',
            $this->data['title']['HTML'],
            $text
        );
 
        if (count($topics) == 0) {
            $text = preg_replace(
                '|<%LOOP-TOPIC%>|i',
                $this->template['no_topics'],
                $text
            );
        }
        else {
            $topicsText = '';
            foreach ($topics as $topic) {
                $topicsText .= $this->__topic($topic);
            }

            $text = preg_replace(
                '|<%LOOP-TOPIC%>|i',
                $topicsText,
                $text
            );
            
        }

        return $text;
    }

    /**
    * Parse the topic.
    * @access private
    */
    private function __topic($topic) {
        $text = $this->template['topic'];
        
        $text = preg_replace(
            '|<%TOPIC-ID%>|i',
            $topic['id']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%TOPIC-TITLE%>|i',
            $topic['title']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%TOPIC-SUBTITLE%>|i',
            $topic['subtitle']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%TOPIC-AUTHOR%>|i',
            $topic['name']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%TOPIC-POSTS-COUNT%>|i',
            $topic['count_posts']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%TOPIC-VIEWS-COUNT%>|i',
            "{$topic['count_views']['HTML']}<span title='{$topic['id']['HTML']}' style='display: none;'/>",
            $text
        );
        $text = preg_replace(
            '|<%LAST-POST-NICK%>|i',
            $topic['last_user_name']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%LAST-POST-TIME%>|i',
            $topic['last_post_time']['HTML'],
            $text
        );


        $text = preg_replace(
            '|<%POST-TOPIC-ID%>|i',
            $topic['id']['POST'],
            $text
        );
        $text = preg_replace(
            '|<%POST-TOPIC-TITLE%>|i',
            $topic['title']['POST'],
            $text
        );

        return $text;
    }

    /**
    * Creates the pager.
    * @access private
    */
    private function __pager($text) {
        $pager = $this->template['pager'];

        $pager = preg_replace(
            '|<%PAGER-FIRST%>|i',
            $this->__first(),
            $pager
        );
        $pager = preg_replace(
            '|<%PAGER-PREVIOUS%>|i',
            $this->__previous(),
            $pager
        );
        $pager = preg_replace(
            '|<%PAGER-PAGES%>|i',
            $this->__pages(),
            $pager
        );
        $pager = preg_replace(
            '|<%PAGER-NEXT%>|i',
            $this->__next(),
            $pager
        );
        $pager = preg_replace(
            '|<%PAGER-LAST%>|i',
            $this->__last(),
            $pager
        );

        $text = preg_replace(
            '|<%PAGER%>|i',
            $pager,
            $text
        );

        return $text;
    }

    private function __first() {
        $text = $this->template['pager_first'];

        if ($this->page == 1) {
            $first = $this->template['pager_first_no'];
        }
        else {
            $first = $this->template['pager_first_yes'];
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

    private function __previous() {
        $text = $this->template['pager_previous'];

        if ($this->page == 1) {
            $previous = $this->template['pager_previous_no'];
        }
        else {
            $previous = $this->template['pager_previous_yes'];
        }
        $previous = preg_replace(
            '|<%PREVIOUS-PAGE%>|i',
            $this->page - 1,
            $previous
        );

        $text = preg_replace(
            '|<%PREVIOUS%>|i',
            $previous,
            $text
        );

        return $text;
    }

    private function __pages() {
        $text = $this->template['pager_pages'];

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
    * Creates the page.
    * @access private
    */
    private function __page($page) {
        if ($page == $this->page) {
            $text = $this->template['pager_current_page'];
        }
        else {
            $text = $this->template['pager_page'];
        }

        $text = preg_replace(
            '|<%PAGE%>|ims',
            $page,
            $text
        );

        return $text;
    }

    private function __next() {
        $text = $this->template['pager_next'];

        if ($this->page == $this->pagesNumber) {
            $next = $this->template['pager_next_no'];
        }
        else {
            $next = $this->template['pager_next_yes'];
        }
        $next = preg_replace(
            '|<%NEXT-PAGE%>|i',
            $this->page + 1,
            $next
        );

        $text = preg_replace(
            '|<%NEXT%>|i',
            $next,
            $text
        );

        return $text;
    }

    private function __last() {
        $text = $this->template['pager_last'];

        if ($this->page == $this->pagesNumber) {
            $last = $this->template['pager_last_no'];
        }
        else {
            $last = $this->template['pager_last_yes'];
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

    /**
    * Common tags.
    * @access private
    */
    private function __common($text) {
        $text = preg_replace(
            '|<%SECTION-ID%>|i',
            $this->section_id,
            $text
        );

        return $text;
    }
}
?>
