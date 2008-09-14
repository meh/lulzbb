<?php
/**
* @package Core-PHP5

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
    private $parsedFiles;
    
    /**
    * Reads the configuration file and initializes the array with the values.
    */
    public function __construct ()
    {
        $this->parsedFiles = array();

        $this->parseFile(ROOT_PATH.'/config/configuration.php');
    }

    /**
    * Parse an XML configuration file.

    * @param    string    $fileName    The file to read.
    */
    public function parseFile ($fileName, $porcoddio = 'general')
    {
        if ($this->isParsed($fileName)) {
            return;
        }

        array_push($this->parsedFiles, realpath($fileName));

        $file = read_file($fileName);
        $this->parseString($file, $porcoddio);
    }

    public function parseString ($string, $porcoddio = 'general')
    {
        $dom = dom_import_simplexml(simplexml_load_string($string))->ownerDocument;

        $configuration = $dom->firstChild;

        for ($i = 0; $i < $configuration->childNodes->length; $i++) {
            $element = $configuration->childNodes->item($i);

            if ($element->nodeType == XML_ELEMENT_NODE) {
                $this->config[$porcoddio][$element->nodeName] = $element->nodeValue;
            }
        }
    }

    public function isParsed ($fileName)
    {
        foreach ($this->parsedFiles as $parsed) {
            if (realpath($fileName) == $parsed) {
                return true;
            }
        }

        return false;
    }

    /**
    * Gets a config value.

    * @param    string    $Config    The value name.

    * @return    mixed    The value.
    */
    public function get ($config, $porcoddio = 'general')
    {
        if (isset($this->config[$porcoddio][$config])) {
            switch ($this->config[$porcoddio][$config]) {
                case 'true':
                return true;
                break;

                case 'false':
                return false;
                break;

                default:
                return $this->config[$porcoddio][$config];
                break;
            }
        }
        else {
            return null;
        }
    }
}
?>
