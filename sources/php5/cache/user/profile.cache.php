<?php
/**
* @package lulzBB-PHP5
* @category Cache

* @license http://opensource.org/licenses/gpl-3.0.html
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
