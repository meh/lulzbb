<?php
/**
* Package  Misc
* Author   cHoBi
* License  http://opensource.org/licenses/gpl-3.0.html
**/
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

