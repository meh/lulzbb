<?php
/**
* @package Misc
* @license http://opensource.org/licenses/gpl-3.0.html

* @author cHoBi
*/

if (count($_GET) == 0 && count($_POST) == 0) {
    die();
}

if (isset($_GET['show'])) {
    if (isset($_GET['profile'])) {
        require_once(SOURCE_PATH.'/output/user/profile.output.php');

        $DATA['user_id'] = $_REQUEST['id'];

        $template = new UserProfile($DATA['user_id']);
        echo $template->output();
    }
}

else if (isset($_GET['send'])) {

}
?>
