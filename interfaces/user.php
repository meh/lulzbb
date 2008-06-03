<?php
/**
* @package Interfaces
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

if (count($_REQUEST) <= 1) {
    die('No parameters eh? Are you trying to hax me? :(');
}

if (isset($_REQUEST['show'])) {
    if (isset($_REQUEST['profile'])) {
        require_once(SOURCE_PATH.'/output/user/profile.output.php');

        $DATA['user_id'] = $_REQUEST['id'];

        $template = new UserProfile($DATA['user_id']);
        echo $template->output();
    }
}

else if (isset($_REQUEST['send'])) {

}
?>
