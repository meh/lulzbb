<?php
/**
* @package PHP5
* @category Output

* @license http://opensource.org/licenses/gpl-3.0.html
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
