<?php
/**
* @package PHP5
* @category Filter

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
* Filtering like SQL, HTML, POST and crypting.

* @author cHoBi
*/
class Filter
{
    private $magic_gpc;

    /**
    * Initialize the magic_gpc, we have to know if it's on or not.
    */
    public function __construct ()
    {
        $this->magic_gpc = get_magic_quotes_gpc();
    }

    /**
    * HTML filtering, htmlentities.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function HTML ($string)
    {
        if ($this->magic_gpc) {
            $string = Filter::SQLclean($string);
        }
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = str_replace("\t", str_repeat('&nbsp;', 4), $string);
        
        return $string;
    }
    
    /**
    * SQL filtering, mysql_real_escape_string.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function SQL ($string)
    {
        if ($this->magic_gpc) {
           $string = Filter::SQLclean($string);
        }
        $string = mysql_real_escape_string($string);
        
        return $string;
    }

    /**
    * POST filtering, rawurlencode.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function POST ($string)
    {
        if ($this->magic_gpc) {
            $string = Filter::SQLclean($string);
        }
        $string = rawurlencode($string);

        return $string;
    }

    /**
    * HTML clean, html_entity_decode.

    * @param    string    $string    The string to clean.

    * @return    string    The cleaned string.
    */
    public function HTMLclean ($string)
    {
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        return $string;
    }

    /**
    * SQL clean, stripslashes.

    * @param    string    $string    The string to clean.

    * @return    string    The cleaned string.
    */
    public function SQLclean ($string)
    {
        $string = stripslashes($string);
        return $string;
    }

    /**
    * POST clean, rawurldecode.

    * @param    string    $string    The string to clean.

    * @return    string    The cleaned string.
    */
    public function POSTclean ($string)
    {
        $string = rawurldecode($string);
        return $string;
    }

    /**
    * SQL cleaned and HTML filtered.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function HTML_SQLclean ($string)
    {
        $string = Filter::HTML(Filter::SQLclean($string));
        return $string;
    }

    /**
    * POST cleaned and HTML filtered.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function HTML_POSTclean ($string)
    {
        $string = Filter::HTML(Filter::POSTclean($string));
        return $string;
    }

    /**
    * SQL cleaned and POST filtered.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function POST_SQLclean ($string)
    {
        $string = Filter::POST(Filter::SQLclean($string));
        return $string;
    }

    /**
    * HTML cleaned and SQL filtered.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function SQL_HTMLclean ($string)
    {
        $string = Filter::SQL(Filter::HTMLclean($string));
        return $string;
    }

    /**
    * Double SHA512 encryption.

    * @param    string    $string    The string to crypt.

    * @return    string    The crypted string.
    */
    public function crypt ($string)
    {
        $string = hash('sha512', hash('sha512', $string));
        return $string;
    }
}
?>
