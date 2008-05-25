<?php
/**
* @package lulzBB-PHP5
* @license http://opensource.org/licenses/gpl-3.0.html
*/

require_once(SOURCE_PATH.'/send/send.class.php');

/**
* Registration class.

* @author cHoBi
*/
class Registration extends Send {
    /**
    * Initialize and send the registration or check the data.

    * @param    string    $type    The operation to do.
    * @param    array     $data    The data if needed from the check or send.
    */
    public function __construct($type, $data = array()) {
        parent::__construct();
    
        if ($this->connected) {
            die("You're already connected.");
        }

        $this->data['type'] = $type;
        
        try {
            $this->output = $this->__send($data);
        }
        catch (lulzException $e) {
            die($e->getMessage());
        }
    }

    /**
    * Sends the data, it does the 3 checks or the registration.

    * @param    array    $data    The data being used.

    * @access private
    */
    protected function __send($data) {
        switch ($this->data['type']) {
            case 'check_username':
            $output = $this->__checkUsername($data['username']);
            break;

            case 'check_email':
            $output = $this->__checkEmail($data);
            break;
        
            case 'check_password':
            $output = $this->__checkPassword($data);
            break;

            case 'send':
            $output = $this->__register($data);
            break;

            default:
            $output = 'Something went wrong :(';
            break;
        }
        
        return $output;
    }

    /**
    * Check if the username is ok or not.

    * @param    string    $username    The username.

    * @return    string    The output.
    * @access private
    */
    private function __checkUsername($username) {
        global $Database;
        $username = trim($username);
        
        if (empty($username)) {
            $output = 'Insert username from 1 to 30 chars.';
        }
                    
        else if (strlen($username) < 1) {
            $this->output = 'The username is too short.';
        }
    
        else if (strlen($username) > 30) {
            $output = 'The username is too long.';
        }
    
        else if ($Database->user->exists($username)) {
            $output = 'The username already exists.';
        }
    
        else {
            $output = 'Ok.';
        }

        return $output;
    }

    /**
    * Check if the email address is ok or not (check for ban too)

    * @param    array    $data    The email addresses.

    * @return    string    The output.
    * @access private
    */
    private function __checkEmail($data) {
        global $Database;

        if (isset($data['email'])) {
            $email1 = $email2 = $data['email'];
        }
        else {
            $email1 = trim($data['email1']);
            $email2 = trim($data['email2']);
        }
        $re_email  = '|^[^\d]\w+(\.\w+)*@\w+(\.\w+)*\.[A-z]{2,4}$|i';

        if ($email1 != $email2) {
            $output = "The email addresses don't match.";
        }
        
        else if (empty($email1)) {
            $output = 'Insert a valid email address.';
        }

        else if (!preg_match($re_email, $email1)) {
            $output = "The email address isn't valid.";
        }

        else if ($Database->user->emailExists($email1)) {
            $output = 'The email address is already in use.';
        }
        else {
            $output = $this->__isBanned($email1);
        }

        return $output;
    }

    /**
    * Check if the email address is banned or not.

    * @param    string    $email    The email address.

    * @return    string    The output.
    * @access private
    */
    private function __isBanned($email) {
        $output = 'Ok.';
        $re_email  = '|^[^\d]\w+(\.\w+)*@\w+(\.\w+)*\.[A-z]{2,4}$|i';
        $re_domain = '|^@\w+(\.\w+)*\.[[:alpha:]]{2,4}$|i';

        $bannedEmails = file(ROOT_PATH.'/config/email_blacklist.php');
        foreach($bannedEmails as $banned) {
            $banned = trim($banned);

            if (preg_match($re_domain, $banned)) {
                if (preg_match("|$banned$|i", $email)) {
                    $output = 'The domain is banned.';
                    break;
                }
            }
            else if (preg_match($re_email, $banned)) {
                if (preg_match("|^$banned$|i", $email)) {
                    $output = 'The email address is banned.';
                    break;
                }
            }
        }

        return $output;
    }

    /**
    * Check if the password is ok or not.

    * @param    array    $data    The passwords.

    * @return    string    The output.
    * @access private
    */
    private function __checkPassword($data) {
        if (isset($data['password'])) {
            $password1 = $password2 = $data['password'];
        }
        else {
            $password1 = $data['password1'];
            $password2 = $data['password2'];
        }

        if ($password1 != $password2) {
            $output = "The passwords don't match.";
        }

        else if (empty($password1)) {
            $output = 'Insert a password from 6 to 30 chars.';
        }

        else if (strlen($password1) < 6) {
            $output = 'The password is too short.';
        }

        else if (strlen($password1) > 30) {
            $output = 'The password is too long.';
        }

        else {
            $output = 'Ok.';
        }

        return $output;
    }

    /**
    * Register the user.

    * @param    array    $data    Username, email address and password.

    * @return    string    The output.
    * @access private
    */
    private function __register($data) {
        global $Database;
        $username = trim($data['username']);
        $email    = trim($data['email']);
        $password = trim($data['password']);

        if (    $this->__checkUsername($username) == 'Ok.'
             && $this->__checkEmail(array('email' => $email) == 'Ok.')
             && $this->__checkPassword(array('password' => $password)) == 'Ok.'
           ) {
            $Database->user->registration->exec($username, $password, $email);
            $message = new InformativeMessage(
                'registration_successful',
                 array(
                    'username' => $username,
                    'password' => $password
                )
            );
        }
        else {
            $message = new InformativeMessage('Something went wrong :<');
        }

        return $message->output();
    }
}
?>
