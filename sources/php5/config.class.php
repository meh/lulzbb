<?php
/**
* @package PHP5

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

/**
* This class loads the configuration file and has methods to get and
* set variables in the file.

* @author cHoBi
*/
class Config {
    private $config;
    
    /**
    * Reads the configuration file and initializes the array with the values.
    */
    public function __construct() {
        $data = file(ROOT_PATH.'/config/configuration.php');

        foreach ($data as $line) {
            if (preg_match('/^(^[\s]?[;])|^(^([\s]+))$|^([\w]+=[\w]+)/', $line)) {
                $line = split('=', $line);

                if (count($line) > 1) {
                    $this->config[$line[0]]
                        = ereg_replace(
                              '(;.*$)',
                              '',
                              trim($line[1])
                          );
                }
            }
        }
    }

    /**
    * Gets a config value.

    * @param    string    $Config    The value name.

    * @return    mixed    The value.
    */
    public function get($Config) {
        if (isset($this->config)) {
            return $this->config[$Config];
        }
        else {
            return 'None';
        }
    }

    /**
    * Sets the template to be used.

    * @todo Rewrite this because it's from the 0.1.0 Misc.
    */
    public function setTemplate($templateName) {
        $template = ROOT_PATH.'/templates/'.$templateName; // New template name
        $oldTemplate = $this->getTemplate(); // Old template name

        if (is_dir($template)) {
            if (is_file($template.'/template.cfg')) {
                $this->config['template'] = $templateName;
                $data = file(ROOT_PATH.'/config/configuration.php');

                // Insert lines in an array except the template
                foreach ($data as $n => $line) {
                    if (ereg($oldTemplate, $line)) {
                        $data[$n] = "template=".$templateName."\n";
                    }
                }

                $fp = fopen('config/lulz.config', 'w');
                foreach($data as $n => $line) {
                    fwrite($fp, $line);
                }

                $_SESSION['config']['template'] = $templateName;
            }
        }
    }
}
?>
