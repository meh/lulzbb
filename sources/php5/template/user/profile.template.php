<?php
/**
* @package Core-PHP5
* @category Template

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

require_once(SOURCES_PATH.'/template/template.class.php');

/**
* User's profile's template class.

* @author cHoBi
*/
class UserProfileTemplate extends Template
{
    private $id;
    private $name;
    private $email;
    private $avatar;
    private $real_name;
    private $real_surname;
    private $sex;
    private $birth;
    private $location;
    private $biography;
    private $hobby;
    private $job;
    private $signature;
    private $homepage;
    private $msn;
    private $icq;
    private $yahoo;
    private $options;
    private $registration_date;

    /**
    * Creates the user profile template.

    * @param    array    The user data.
    */
    public function __construct ($data)
    {
        parent::__construct('user/profile.tpl');

        $this->id                  = $data['id'];
        $this->name                = $data['name'];
        $this->email               = $data['email'];
        $this->avatar              = $data['avatar'];
        $this->real_name           = $data['real_name'];
        $this->real_surname        = $data['real_surname'];
        $this->sex                 = $data['sex'];
        $this->birth               = $data['birth'];
        $this->location            = $data['location'];
        $this->biography           = $data['biography'];
        $this->hobby               = $data['hobby'];
        $this->job                 = $data['job'];
        $this->signature           = $data['signature'];
        $this->homepage            = $data['homepage'];
        $this->msn                 = $data['msn'];
        $this->icq                 = $data['icq'];
        $this->yahoo               = $data['yahoo'];
        $this->options['email']    = $data['option_email'];
        $this->options['bbcode'] = $data['option_bbcode'];
        $this->registration_date   = $data['registration_date'];

        $this->__parse();
    }

    /**
    * @access private
    */
    private function __parse ()
    {
        $text = $this->output();

        // User name
        $text = preg_replace(
            '|<%USER-NAME%>|i',
            $this->name['HTML'],
            $text
        );

        // Email
        if ($this->options['email']) {
            $email = $this->email['HTML'];
        }
        else {
            $email = '';
        }

        $text = preg_replace(
            '|<%USER-EMAIL%>|i',
            $email,
            $text
        );

        // Avatar
        $text = preg_replace(
            '|<%USER-AVATAR%>|i',
            $this->avatar['HTML'],
            $text
        );

        // Real name
        $text = preg_replace(
            '|<%USER-REAL-NAME%>|i',
            $this->real_name['HTML'],
            $text
        );
        
        // Real surname
        $text = preg_replace(
            '|<%USER-REAL-SURNAME%>|i',
            $this->real_surname['HTML'],
            $text
        );

        // Sex
        $text = preg_replace(
            '|<%USER-SEX%>|i',
            $this->sex['HTML'],
            $text
        );

        // Birth date
        $text = preg_replace(
            '|<%USER-BIRTH%>|i',
            $this->birth['HTML'],
            $text
        );

        // Location
        $text = preg_replace(
            '|<%USER-LOCATION%>|i',
            $this->location['HTML'],
            $text
        );

        // Biography
        $text = preg_replace(
            '|<%USER-BIOGRAPHY%>|i',
            $this->biography['HTML'],
            $text
        );

        // Hobby
        $text = preg_replace(
            '|<%USER-HOBBY%>|i',
            $this->hobby['HTML'],
            $text
        );

        // Job
        $text = preg_replace(
            '|<%USER-JOB%>|i',
            $this->job['HTML'],
            $text
        );

        // Signature
        $text = preg_replace(
            '|<%USER-SIGNATURE%>|i',
            $this->signature['HTML'],
            $text
        );

        // Homepage
        $text = preg_replace(
            '|<%USER-HOMEPAGE%>|i',
            $this->homepage['HTML'],
            $text
        );

        // MSN
        $text = preg_replace(
            '|<%USER-MSN%>|i',
            $this->msn['HTML'],
            $text
        );

        // ICQ
        $text = preg_replace(
            '|<%USER-ICQ%>|i',
            $this->icq['HTML'],
            $text
        );

        // Yahoo
        $text = preg_replace(
            '|<%USER-YAHOO%>|i',
            $this->yahoo['HTML'],
            $text
        );

        // Registration date
        $text = preg_replace(
            '|<%USER-REGISTRATION-DATE%>|i',
            $this->registration_date['HTML'],
            $text
        );

        $this->parsed = $text;
    }
}
?>
