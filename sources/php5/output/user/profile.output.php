<?php
/**
* @package PHP5
* @category Output

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

require_once(SOURCE_PATH.'/output/output.class.php');
require_once(SOURCE_PATH.'/cache/user/profile.cache.php');
require_once(SOURCE_PATH.'/show/user/profile.show.php');

/**
* User's profile output class.

* @author cHoBi
*/
class UserProfile extends Output {
    /**
    * Initialize the user profile and output the Show or the Cache.

    * @param    int    $user_id    The user's id.
    */
    public function __construct($user_id) {
        parent::__construct();

        $cache = new UserProfileCache($user_id);
        if (!$cache->isCached()) {
            $template = new UserProfileShow($user_id);
            $cache->put($template->output());
        }

        $this->output = $cache->get();
    }
}
?>
