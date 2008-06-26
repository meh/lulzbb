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
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<%LANG%>" xml:lang="<%LANG%>">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title>Misc - Configuration</title>

    <style>
    * { padding: 0; margin: 0; border: 0; }

    a:link, a:visited {
        color: #AA0000;
        font-weight: bold;
    }
    a:hover {
        color: #DDDDDD;
        font-weight: bold;
    }

    body {
        width: 100%;
        background-color: #000000;
        font-family: 'Verdana';
        font-size: 12px;
        color: #FFFFFF;
    }

    #container {
        width: 80%;
        margin: 0 auto;
        margin-top: 10px;
    }
    </style>
</head>

<body>
    <div id="container">
        <div id="content">
            <span style="font-size: 14px; font-weight: bold">Configuration:</span><br/><br/>
<?php
if (!extension_loaded('session')) {
    echo "The session extension is missing, enable it.<br/>";
    $requisite = false;
}
else if (!(extension_loaded('mysql') or extension_loaded('mysqli'))) {
    echo "The mysql extension is missing, enable it.<br/>";
    $requisite = false;
}
else {
    $requisite = true;
}

if ($requisite) {
    echo <<<HTML
    
Everything's ok, you can proceed with the wizard or if you already 
configured the board you can create the tables.<br/>
<br/>
<a href="#">Wizard</a><br/>
<a href="tables.php">Create tables</a><br/>

HTML;
}
else {
    echo "<br/>Your php installation isn't ready, fix the missing parts kthxbai.<br/>";
}
?>

        </div>
    </div>
</body>
</html>

