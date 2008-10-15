<?php
/**
* @package  Install

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

* @author   cHoBi
**/

function checkInstall ()
{
    header('Location: install/index.php');
}

function installed ()
{
    $index = file(ROOT_PATH.'/index.php');

    $file = fopen(ROOT_PATH.'/index.php', 'w');
    foreach ($index as $line) {
        if (!(      preg_match("|'install/functions.php'|", $line)
                 or preg_match("|checkInstall\(\);|", $line))) {
            fwrite($file, $line);
        }
    }
    fclose($file);
}
?>
