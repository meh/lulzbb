<?php
/**
* @package PHP5
* @category Cache

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

require_once(SOURCE_PATH.'/cache/cache.class.php');

/**
* User's profile cache class.

* @author cHoBi
*/
class UserProfileCache extends Cache {
    private $user_id;

    /**
    * Initialize the cache and the file.

    * @param    int    $section_id    The section id.
    */
    public function __construct($user_id) {
        $this->user_id = $user_id;

        $file = "users/profile-{$user_id}.html";
        parent::__construct($file);
    }
}
?>
