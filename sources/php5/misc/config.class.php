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
class Config
{
    private $config;
    
    /**
    * Reads the configuration file and initializes the array with the values.
    */
    public function __construct ()
    {
        $this->parseFile(ROOT_PATH.'/config/configuration.php');
    }

    /**
    * Parse an XML configuration file.

    * @param    string    $fileName    The file to read.
    */
    public function parseFile ($fileName)
    {
        $file = file($fileName);
        array_pop($file);
        array_shift($file);
        $file = join("\n", $file);
        
        return $this->parseString($file);
    }

    public function parseString ($string)
    {
        $dom = dom_import_simplexml(simplexml_load_string($string))->ownerDocument;

        $configuration = $dom->firstChild;

        for ($i = 0; $i < $configuration->childNodes->length; $i++) {
            $element = $configuration->childNodes->item($i);

            if ($element->nodeType == XML_ELEMENT_NODE) {
                $this->config[$element->nodeName] = $element->nodeValue;
            }
        }
    }

    /**
    * Gets a config value.

    * @param    string    $Config    The value name.

    * @return    mixed    The value.
    */
    public function get ($config)
    {
        if (isset($this->config)) {
            switch ($this->config[$config]) {
                case 'true':
                return true;
                break;

                case 'false':
                return false;
                break;

                default:
                return $this->config[$config];
                break;
            }
        }
        else {
            return null;
        }
    }
}
?>
