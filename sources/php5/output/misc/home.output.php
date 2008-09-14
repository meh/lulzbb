<?php
/**
* @package Core-PHP5
* @category Output

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

require_once(SOURCES_PATH.'/output/output.class.php');
require_once(SOURCES_PATH.'/template/misc/home.template.php');
require_once(SOURCES_PATH.'/template/misc/page.template.php');

/**
* Shows the home with data inside.

* @todo Display the menu directly through the home and not with ajax.

* @author cHoBi
*/
class Home extends Output
{
    private $file;

    /**
    * Initialize the file and the data.

    * @param    string    $file    The file to load inside the home.
    * @param    array     $data    The data that the page needs.
    */
    public function __construct ($file, $data = array())
    {
        parent::__construct();

        $this->data = $data;
        $this->file = $file;

        $this->__update();
    }

    /**
    * Initialize the section or the topic when needed.
    */
    protected function __update ()
    {
        if (empty($this->file)) {
            $template = new HomeTemplate();
        }
        else {
            $content  = new PageTemplate($this->file, $this->data);
            $template = new HomeTemplate($content->output());
        }

        $this->output = $template->output();
    }
}
?>

