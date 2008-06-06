<?php
/**
* @package Misc

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

* @author cHoBi
*/

/**
* Anti DDoS main function.
*/
function antiDoS() {
    global $Config;

    if (!isset($_COOKIE['PHPSESSID'])) {
        die("You need cookies to be activated.");
    }

    loadBlacklist();

    if (isBanned($_SERVER['REMOTE_ADDR'])) {
        die('Your ip address is banned.');
    }
/**
* @todo This isn't that useful, the forum caches everything so no queries.
*       also i don't like it that much and it's not working anymore.
*/
/*  else {
        if ($_SESSION[SESSION]['anti-dos']['last-time'] > time()-$Config->get('anti-dos-seconds')) {
            $_SESSION[SESSION]['anti-dos']['times']++;
        }
        else if ($_SESSION[SESSION]['anti-dos']['last-time'] > time()-60) {
            $_SESSION[SESSION]['anti-dos']['times'] = 0;
        }

        if ($_SESSION[SESSION]['anti-dos']['times'] > $Config->get('anti-dos-times')) {
            banIp($_SERVER['REMOTE_ADDR']);
        }

        $_SESSION[SESSION]['anti-dos']['last-time'] = time();
    }*/
}

/**
* Says if the ip is banned or not.

* @param    string    $ip    The ip address to check.
*/
function isBanned($ip) {
    if (isset($_SESSION[SESSION]['anti-dos'][$ip])) {
        return true;
    }
    else {
        return false;
    }
}

/**
* Loads the blacklist if the file is changed.
*/
function loadBlacklist() {
    $path  = ROOT_PATH.'/config/ip_blacklist.php';
    $ctime = filectime($path);

    if ($_SESSION[SESSION]['anti-dos']['ctime'] != $ctime) {
        $_SESSION[SESSION]['anti-dos']['ctime'] = $ctime;
        $bannedIps = file($path);

        foreach ($bannedIps as $banned) {
            if (preg_match('|(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})|', $banned, $banned)) {
                $_SESSION[SESSION]['anti-dos'][$banned[1]] = true;
            }
        }
    }
}

/**
* Adds an ip to the blacklist file.

* @param    string    $ip    The ip address to add.
*/
function banIp($ip) {
    $path = ROOT_PATH.'/config/ip_blacklist.php';
    $data = file($path);

    $file = fopen($path, 'w');
    foreach ($data as $n => $line) {
        if ($n == count($data)-2) {
            break;
        }
        fwrite($file, "{$line}");
    }
    fwrite($file, "{$ip} \n\n*/?>");
    fclose($file);

    $_SESSION[SESSION]['anti-dos'][$ip] = true;
}

/**
* Cleans the blacklisted ips.
*/
function cleanBlacklist() {
    $path = ROOT_PATH.'/config/ip_blacklist.php';
    $data = file($path);

    $file = fopen($path, 'w');
    foreach ($data as $n => $line) {
        if ($n > 3) {
            break;
        }
        fwrite($file, "{$line}");
    }
    fwrite($file, "*/?>");
    fclose($file);
}
?>
