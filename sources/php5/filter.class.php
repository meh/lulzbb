<?php
/**
* @package PHP5
* @category Filter

* @license http://opensource.org/licenses/gpl-3.0.html
*/

/**
* Filtering like SQL, HTML, POST and crypting.

* @author cHoBi
*/
class Filter {
    private $magic_gpc;

    /**
    * Initialize the magic_gpc, we have to know if it's on or not.
    */
    public function __construct() {
        $this->magic_gpc = get_magic_quotes_gpc();
   }

    /**
    * HTML filtering, htmlentities.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function HTML($string) {
        if ($this->magic_gpc) {
            $string = Filter::SQLclean($string);
        }
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = str_replace("\t", str_repeat('&nbsp', 4), $string);
        
        return $string;
    }
    
    /**
    * SQL filtering, mysql_real_escape_string.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function SQL($string) {
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
    public function POST($string) {
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
    public function HTMLclean($string) {
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        return $string;
    }

    /**
    * SQL clean, stripslashes.

    * @param    string    $string    The string to clean.

    * @return    string    The cleaned string.
    */
    public function SQLclean($string) {
        $string = stripslashes($string);
        return $string;
    }

    /**
    * POST clean, rawurldecode.

    * @param    string    $string    The string to clean.

    * @return    string    The cleaned string.
    */
    public function POSTclean($string) {
        $string = rawurldecode($string);
        return $string;
    }

    /**
    * SQL cleaned and HTML filtered.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function HTML_SQLclean($string) {
        $string = Filter::HTML(Filter::SQLclean($string));
        return $string;
    }

    /**
    * POST cleaned and HTML filtered.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function HTML_POSTclean($string) {
        $string = Filter::HTML(Filter::POSTclean($string));
        return $string;
    }

    /**
    * SQL cleaned and POST filtered.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function POST_SQLclean($string) {
        $string = Filter::POST(Filter::SQLclean($string));
        return $string;
    }

    /**
    * HTML cleaned and SQL filtered.

    * @param    string    $string    The string to filter.

    * @return    string    The filtered string.
    */
    public function SQL_HTMLclean($string) {
        $string = Filter::SQL(Filter::HTMLclean($string));
        return $string;
    }

    /**
    * Double SHA512 encryption.

    * @param    string    $string    The string to crypt.

    * @return    string    The crypted string.
    */
    public function crypt($string) {
        $string = hash('sha512', hash('sha512', $string));
        return $string;
    }
}
?>
