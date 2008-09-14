<?php
/**
* @package Core-PHP5
* @category Misc

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

/**
* Class that highlights various sources.

* @author cHoBi
*/
class SyntaxHighlight
{
    private $language;
    private $source;
    private $highlighted;

    /**
    * Create the object and highlight the source.

    * @param    string    $source      The source text or the file name.
    * @param    string    $mode        The class mode (string | file)
    * @param    string    $language    The programming language of the source.
    */
    public function __construct ($source, $mode = 'file', $language = 'auto')
    {
        if ($mode == 'file') {
            $this->language = preg_replace('|^.*\.|', '', $language);
            $this->source = file_get_contents(ROOT_PATH.$source);
        }

        $this->highlighted = $this->highlight($this->source);
    }

    public function highlight ($source)
    {
        $highlighted = $source;

        $highlighted = str_replace('&', '&amp;', $highlighted);
        $highlighted = str_replace('<', '&lt;', $highlighted);
        $highlighted = str_replace('>', '&gt;', $highlighted);

        return $highlighted;
    }

    public function output ()
    {
        return $this->highlighted;
    }
}
?>
