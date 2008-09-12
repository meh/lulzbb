<?php
/**
* @package PHP5
* @category Send

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

require_once(SOURCE_PATH.'/send/send.class.php');

/**
* Login class.

* @author cHoBi
*/
class Login extends Send
{
    /**
    * Do the login.

    * @param    string    $username    The username.
    * @param    string    $password    The password.

    * @exception    Error on the database.
    */
    public function __construct ($username, $password)
    {
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

    * @param    array    $data    (username, password)

    * @return    string    The information about the login.
    */
    protected function __send ($data)
    {
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
    private function __login ($username, $password)
    {
        global $Database;
        global $User;
    
        $user = $Database->user->login->check($username, $password);
        if ($user) {
            $User = $_SESSION[SESSION]['user'] = new User($user);
            return true;
        }
        else {
            return false;
        }
    }
}
?>
