<?php
/**
* @package lulzBB-PHP5
* @category Template

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/template/template.class.php');

/**
* Section template class.

* @author cHoBi
*/
class SectionTemplate extends Template {
    /**
    * Create the template of a section.

    * @param    int      $section_id    The section id.
    * @param    array    $sections      The sections data.
    * @param    array    $topics        The topics data.
    */
    public function __construct($section_id, $groups, $topics) {
        parent::__construct('forum/section.tpl');
        global $Database;
        
        $this->data['section_id'] = $section_id;
        $this->data['groups']     = $groups;
        $this->data['topics']     = $topics;
        $this->data['template']   = array();

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
        if (!empty($this->data['groups'])) {
            $sectionGroups = $this->__sectionsGroup($this->data['groups']);
            foreach ($sectionGroups as $group) {
                $sections .= $this->__sections($group);
            }
        }

        $text = preg_replace(
            '|<%LOOP-SECTIONS%>|i',
            $sections,
            $text
        );

        if ($this->data['section_id'] != 0) {
            $topics = $this->__topics($this->data['topics']);
        }
        else {
            $topics = '';
        }

        $text = preg_replace(
            '|<%LOOP-TOPICS%>|i',
            $topics,
            $text
        );

        $this->parsed = $text;
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
        
        // Sections
        if (preg_match_all(
                '|<Sections>(.+?)?</Sections>|ims',
                $text,
                $sections) != 1) {
            die('The template has an error. (There MUST be 1 Sections loop)');
        }
        $this->data['template']['sections'] = $sections[1][0];

        // Sections group
        if (preg_match_all(
                '|<Sections-Group>(.+?)?</Sections-Group>|ims',
                $this->data['template']['sections'],
                $sections_group) != 1) {
            die('The template has an error. (There MUST be 1 Sections-Group loop)');
        }
        $this->data['template']['sections_group'] = $sections_group[1][0];

        // Section group header
        if (preg_match_all(
                '|<Group-Header>(.+?)?</Group-Header>|ims',
                $this->data['template']['sections_group'],
                $group_header) != 1) {
            die('The template has an error. (There MUST be 1 Group-Header loop)');
        }
        $this->data['template']['group_header'] = $group_header[1][0];

        // Section group content
        if (preg_match_all(
                '|<Section-Content>(.+?)?</Section-Content>|ims',
                $this->data['template']['sections_group'],
                $section_content) != 1) {
            die('The template has an error. (There MUST be 1 Section-Content loop)');
        }
        $this->data['template']['section_content'] = $section_content[1][0];

        if (preg_match_all(
                '|<Last-Info>(.+?)?</Last-Info>|ims',
                $this->data['template']['section_content'],
                $last_info) != 1) {
            die('The template has an error. (There MUST be 1 Last-Info)');
        }
        $this->data['template']['last_info'] = $last_info[1][0];

        if (preg_match_all(
                '|<No-Info>(.+?)?</No-Info>|ims',
                $this->data['template']['section_content'],
                $no_info) != 1) {
            die('The template has an error. (There MUST be 1 No-Info)');
        }
        $this->data['template']['no_info'] = $no_info[1][0];

        $this->data['template']['section_content']
            = preg_replace(
                '|<Last-Info>(.+?)?</Last-Info>|ims',
                '<%LAST-INFO%>',
                $this->data['template']['section_content']
        );
        $this->data['template']['section_content']
            = preg_replace(
                '|<No-Info>(.+?)?</No-Info>|ims',
                '',
                $this->data['template']['section_content']
        );

        // Section group footer
        if (preg_match_all(
                '|<Group-Footer>(.+?)?</Group-Footer>|ims',
                $this->data['template']['sections_group'],
                $group_footer) != 1) {
            die('The template has an error. (There MUST be 1 Group-Footer loop)');
        }
        $this->data['template']['group_footer'] = $group_footer[1][0];

        $this->data['template']['sections_group']
            = preg_replace(
                '|<Group-Header>(.+?)</Group-Header>|ims',
                '<%LOOP-GROUP-HEADER%>',
                $this->data['template']['sections_group']
        );
        $this->data['template']['sections_group']
            = preg_replace(
                '|<Section-Content>(.+?)?</Section-Content>|ims',
                '<%LOOP-GROUP-CONTENT%>',
                $this->data['template']['sections_group']
        );
        $this->data['template']['sections_group']
            = preg_replace(
                '|<Group-Footer>(.+?)?</Group-Footer>|ims',
                '<%LOOP-GROUP-FOOTER%>',
                $this->data['template']['sections_group']
        );
        $this->data['template']['sections']
            = preg_replace(
                '|<Sections-Group>(.+?)?</Sections-Group>|ims',
                '<%LOOP-SECTIONS-GROUP%>',
                $this->data['template']['sections']
        );

        // Topics
        if (preg_match_all(
                '|<Topics>(.+?)?</Topics>|ims',
                $text,
                $topics) != 1) {
            die('The template has an error. (There MUST be 1 Topics loop)');
        }
        $this->data['template']['topics'] = $topics[1][0];

        // Topic
        if (preg_match_all(
                '|<Topic>(.+?)?</Topic>|ims', 
                $this->data['template']['topics'],
                $topic) != 1) {
            die('The template has an error. (There MUST be 1 Topic loop)');
        }
        $this->data['template']['topic'] = $topic[1][0];

        // No topics
        if (preg_match_all(
                '|<No-Topic>(.+?)?</No-Topic>|ims',
                $this->data['template']['topics'],
                $no_topics) != 1) {
            die('The template has an error. (There MUST be 1 No-Topic loop)');
        }
        $this->data['template']['no_topics'] = $no_topics[1][0];
        
        $this->data['template']['topics']
            = preg_replace(
                '|<Topic>(.+?)?</Topic>|ims',
                '<%LOOP-TOPIC%>',
                $this->data['template']['topics']
        );
        $this->data['template']['topics']
            = preg_replace(
                '|<No-Topic>(.+?)?</No-Topic>|ims',
                '',
                $this->data['template']['topics']
        );
        
        $text
            = preg_replace(
                '|<Sections>(.+?)?</Sections>|ims',
                '<%LOOP-SECTIONS%>',
                $text
        );
        $text
            = preg_replace(
                '|<Topics>(.+?)?</Topics>|ims',
                '<%LOOP-TOPICS%>',
                $text
        );

        if (preg_match_all(
                '|<New-Topic>(.+?)?</New-Topic>|ims',
                $text,
                $new_topic) != 1) {
            die('The template has an error. (There MUST be 1 New-Topic)');
        }
        $this->data['template']['new_topic'] = $new_topic[1][0];

        $text
            = preg_replace(
                '|<New-Topic>(.+?)?</New-Topic>|i',
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
        $text = $this->data['template']['group_header'];
        
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
        $text = $this->data['template']['section_content'];

        $text = preg_replace(
            '|<%SECTION-TITLE%>|i',
            $section['title']['HTML'],
            $text
        );
        $text = preg_replace(
            '|<%SECTION-SUBTITLE%>|i',
            $section['subtitle']['HTML'],
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
            $text = $this->data['template']['last_info'];

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
            $text = $this->data['template']['no_info'];
        }

        return $text;
    }

    /**
    * Parse the group footer.
    * @access private
    */
    private function __groupFooter($section) {
        $text = $this->data['template']['group_footer'];
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
        $text = $this->data['template']['sections_group'];

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
        $text = $this->data['template']['sections'];

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
        $text = $this->data['template']['topics'];

        $text = preg_replace(
            '|<%SECTION-TITLE%>|i',
            $this->data['title']['HTML'],
            $text
        );
 
        if (count($topics) == 0) {
            $text = preg_replace(
                '|<%LOOP-TOPIC%>|i',
                $this->data['template']['no_topics'],
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
        $text = $this->data['template']['topic'];
        
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
            '|<%SECTION-ID%>|i',
            $this->data['section_id'],
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
}
?>
