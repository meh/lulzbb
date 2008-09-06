<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<%LANG%>" xml:lang="<%LANG%>">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title><%FORUM-TITLE%></title>

    <link href="<%TEMPLATE-PATH%>/stylesheet.css" rel="stylesheet" type="text/css">
    <script src="sources/misc/lulzBB.js" type="text/javascript"></script>
    <script src="<%TEMPLATE-PATH%>/scripts/effects.js" type="text/javascript"></script>

    <script>
    document.write("<style>span.url { display: none; }</style>");
    </script>
</head>

<body onload="init(<%INIT-OR-NOT%>);">
    <div id="container">
        <div id="top"><%TOP%></div>
        <div id="middle"><%CONTENT%></div>
        <div id="bottom"><%BOTTOM%></div>
    </div>
    <div id="hidden" style="display: none;"></div>
</body>
</html>

