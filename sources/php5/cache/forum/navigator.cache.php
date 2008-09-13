<?php
/**
* @package PHP5
* @category Cache

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

require_once(SOURCES_PATH.'/cache/cache.class.php');

/**
* Caching system for the navigator.

* @author cHoBi
*/
class NavigatorCache extends Cache
{
    private $type;

    /**
    * Creates the cache for the navigator.
    * The creation of the id is pretty complex, the idea is to have a file formed by
    * type.parent-parent-id.html in that way you could remove parents and child
    * when something is changed like the title of the topic/section.
    * To get the cache file i just use the glob() and i made sure that the cache file
    * is unique.

    * @param    string    $type    The navigator type, section or topic.
    * @param    int       $id      The id of the section or topic.
    */
    public function __construct ($type, $id)
    {
        $this->type = $type;

        $file = glob(ROOT_PATH."/.cache/navigator/{$type}.*-{$id}.php");
        if (!empty($file)) {
            $file = preg_replace('|.+?/(navigator.+?)\.php$|i', '${1}', $file[0]);
            parent::__construct($file);
        }
        else {
            parent::__construct('set_file');
        }
    }

    /**
    * Set the cache object to a file.
    * The file name is gotten through the elements id took from the navigator object.

    * @param    array    $elements    The $navigator->getElementsId() result.
    */
    public function setNavigator ($elements)
    {
        $id = "{$this->type}.";
        foreach ($elements as $n => $element) {
            $id .= "{$element}-";
        }
        $id = rtrim($id, '-');

        $this->setCache("navigator/{$id}.php");
    }
}
?>
