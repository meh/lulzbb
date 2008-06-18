<?php
/**
* @package PHP5
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

require_once(SOURCE_PATH.'/output/output.class.php');
require_once(SOURCE_PATH.'/cache/forum/navigator.cache.php');
require_once(SOURCE_PATH.'/show/forum/navigator.show.php');

/**
* Navigator class.

* @author cHoBi
*/
class Navigator extends Output
{
    /**
    * Creates the navigator and gets the cache if it exists.

    * @param    string    $type      The navigator type. section | topic
    * @param    int       $id        The section or topic id.
    * @param    int       $option    The possible option for the navigator.
    */
    public function __construct ($type, $id, $option = '')
    {
        parent::__construct();
        $id = (int) $id;

        $cache = new NavigatorCache($type, $id.$option);
        if (!$cache->isCached()) {
            $navigator = new NavigatorShow($type, $id, $option);
            $cache->setNavigator($navigator->getElementsId());
            $cache->put($navigator->output());
        }

        $this->output = $cache->get();
    }
}
?>
