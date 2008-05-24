<?php
/**
* @package lulzBB-PHP5
* @category Send

* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/send/send.class.php');

/**
* Login class.

* @author cHoBi
*/
class Login extends Send {
    /**
    * Do the login.

    * @param    string    $username    The username.
    * @param    string    $password    The password.

    * @exception    Error on the database.
    */
    public function __construct($username, $password) {
        parent::__construct();

        if ($this->connected) {
            die('FAIL');
        }
        
        try {
            $this->output = $this->__send(array(
                'username' => $username,
                'password' => $password
            ));
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }
    }
   
    /**
    * Send the data and return the answer.

    * @param    array    $data    Username and password.

    * @return    string    The information about the login.

    * @access private
    */
    protected function __send($data) {
        $username = $data['username'];
        $password = $data['password'];
    
        if ($this->__login($username, $password)) {
            $template = new InformativeMessage('login_successful');
        }
        else {
            $template = new InformativeMessage('login_unsuccessful');
        }
        
        return $template->output();
    }

    /**
    * Do the login.

    * @param    string    $username    The username.
    * @param    string    $password    The password.

    * @return    bool    True if the login is successful, false if it's not.

    * @access private
    */
    private function __login($username, $password) {
        global $database;
        $username = trim($username);
        $password = trim($password);
    
        $user = $database->user->login->check($username, $password);

        if ($user) {
            $database->user->login->exec($username);
            $groups = $database->user->getGroups($username);
                
            $_SESSION[SESSION]['user'] = array(
               'id'     => $user['id']['RAW'],
               'name'   => $user['name'],
               'groups' => $groups
            );

            return true;
        }
        else {
            return false;
        }
    }
}
?>
