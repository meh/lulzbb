<?php
/**
* @package PHP5

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
* This class loads the configuration file and has methods to get and
* set variables in the file.

* @author cHoBi
*/

class Module
{
    private $infos;
    private $path;

    /**
    * Reads the configuration file and initializes the array with the values.
    */
    public function __construct ($modulePath)
    {
        $this->path = ROOT_PATH."/{$modulePath}";

        $fileName = "{$this->path}/info.php";

        if (!is_file($fileName)) {
            throw new lulzException('module_info_not_existent');
        }

        $this->__loadInfo(read_file($fileName));
    }

    private function __loadInfo ($text)
    {
        $dom = dom_import_simplexml(simplexml_load_string($text))->ownerDocument;

        $infos = $dom->firstChild;

        for ($i = 0; $i < $infos->childNodes->length; $i++) {
            $info = $infos->childNodes->item($i);

            if ($info->nodeType == XML_ELEMENT_NODE) {
                $this->infos[$info->nodeName] = $info->nodeValue;
            }
        }
    }

    public function get ($info)
    {
        if (isset($this->infos[$info])) {
            switch ($this->infos[$info]) {
                case 'true':
                return true;
                break;

                case 'false':
                return false;
                break;

                default:
                return $this->infos[$info];
                break;
            }
        }
        else {
            return null;
        }
    }
}
?>
