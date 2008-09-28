<?php
/**
* @package Core-PHP5
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

include_once(SOURCES_PATH.'/template/template.class.php');

/**
* Menu template.

* @author cHoBi
*/
class MenuTemplate extends Template
{
    private $groups;

    /**
    * Sets the file to get and parse it.

    * @param    string    $file    The file to get.
    * @param    string    $mode    The page view mode.
    */
    public function __construct ($userGroup, $groups)
    {
        $userGroup = strtolower($userGroup);

        if (is_file(TEMPLATE_PATH."/menu/{$userGroup}.tpl")) {
            parent::__construct("menu/{$userGroup}.tpl");
        }
        else {
            parent::__construct('menu/user.tpl');
        }

        $this->groups = $groups;

        $this->__parse();
    }

    /**
    * Puts the gotten page into the template variable.
    * @access private
    */
    private function __parse ()
    {
        $text = $this->output();
        $text = $this->__loops($text);

        foreach ($this->groups as $groupName => $group) {
            $text = preg_replace(
                "|<%MENU-{$groupName}%>|ims",
                $this->__parseGroup($groupName, $group),
                $text
            );
        }

        $this->parsed = $text;
    }

    private function __loops ($text)
    {
        foreach ($this->groups as $group => $null) {
            if (preg_match_all(
                    "|<{$group}>(.*?)</{$group}>|ims",
                    $text,
                    $groupMatch) != 1) {
                die("Something's wrong in the template.");
            }
            $this->template["{$group}"] = $groupMatch[1][0];

            preg_match(
                '|<Loop>(.*?)</Loop>|ims',
                $this->template["{$group}"],
                $groupMatch
            );
            $this->template["{$group}_loop"] = $groupMatch[0];

            $this->template["{$group}"] = preg_replace(
                '|<Loop>.*?</Loop>|ims',
                '<%MENU-LOOP%>',
                $this->template["{$group}"]
            );

            $text = preg_replace(
                "|<{$group}>.*?</{$group}>|ims",
                "<%MENU-{$group}%>",
                $text
            );
        }

        return $text;
    }

    private function __parseGroup ($groupName, $group)
    {
        $text = $this->template["{$groupName}"];

        $loop = '';
        foreach ($group as $menu) {
            $loop .= $this->__parseMenu($groupName, $menu);
        }

        $text = preg_replace(
            '|<%MENU-LOOP%>|ims',
            $loop,
            $text
        );

        return $text;
    }

    private function __parseMenu ($groupName, $menu)
    {
        $text = $this->template["{$groupName}_loop"];

        $text = preg_replace(
            '|<%MENU-NAME%>|i',
            $menu['name'],
            $text
        );

        $text = preg_replace(
            '|<%MENU-URL%>|i',
            $menu['url'],
            $text
        );

        return $text;
    }
}
?>
