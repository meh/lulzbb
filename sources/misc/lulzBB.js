/**
* @package Misc

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

* @author  cHoBi
*/

var loadingMessage = "<center>Loading...</center>";

function include(filename)
{
    var head    = document.getElementsByTagName('head').item(0);
    script      = document.createElement('script');
    script.src  = filename;
    script.type = 'text/javascript';
    head.appendChild(script)
}

var section_id   = 0;
var section_page = 'first';

include("sources/misc/unFocus-History-p.js");

function init ()
{
    argv = init.arguments;

    showMenu('menu');

    if (argv[0] == true) {
        showSection('middle', 0);
    }
}

// Base functions
function GET (show_id, url)
{
    var http = ((window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP")
                                       : new XMLHttpRequest());

    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            document.getElementById(show_id).innerHTML = http.responseText;
        }
    }
    
    http.open("GET", url, true);
    document.getElementById(show_id).innerHTML = loadingMessage;
    http.send(null);
}
function rawGET (url)
{
    var http = ((window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP")
                                       : new XMLHttpRequest());
    
    http.open("GET", url, true);
    http.send(null);
}

function POST (show_id, url, params)
{
    var http = ((window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") 
                                       : new XMLHttpRequest());
 
    http.onreadystatechange = function()
    {
        if (http.readyState == 4 && http.status == 200) {
            document.getElementById(show_id).innerHTML = http.responseText;
        }
    }

    http.open("POST", url, true);

    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", params.length);
    http.setRequestHeader("Connection", "close");

    document.getElementById(show_id).innerHTML = loadingMessage;
    http.send(params);
}
function rawPOST (url, params)
{
    var http = ((window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") 
                                       : new XMLHttpRequest());

    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", params.length);
    http.setRequestHeader("Connection", "close");
    http.send(params);
}

function showMenu (show_id)
{
    GET(show_id, '?out&menu');
}

function showHome (show_id)
{
    GET(show_id, '?out&home');
}

function showPage (show_id, page)
{
    GET(show_id, '?out&page='+page);
}

function showContent (show_id, page)
{
    GET(show_id, 'pages/'+page);
}

// Forum functions
function showSection (show_id, id, page)
{
    if (id < section_id) {
        section_page = 1;
    }
    section_id = id;

    var page = (page == null) ? section_page : page;
    section_page = page;

    POST(show_id, '?out&forum&section', 'id='+id+'&page='+page);
}
function showTopic (show_id, id, page, post)
{
    var page    = (page == null) ? 1 : page;
    var post_id = (post == null) ? 1 : post;

    POST(show_id, '?out&forum&topic&show', 'id='+id+'&post_id='+post+'&page='+page);
}

// Login functions
function doLogin (show_id, username, password)
{
    POST(show_id, '?in&user&login', 'username='+rawurlencode(username)+'&password='+rawurlencode(password));
}

function login (username, password)
{
    rawPOST('?in&user&login', 'username='+username+'&password='+password);
}
function showLogin (show_id)
{
    GET(show_id, '?out&user&login');
}
function logout (show_id)
{
    GET(show_id, '?in&user&logout');
}

// Registration functions
function showRegistration (show_id)
{
    GET(show_id, '?out&user&register');
}

// Show functions
function showTopicForm (show_id, parent)
{
    POST(show_id, '?out&forum&topic&send', 'parent='+parent+'id=-10');
}

// Send functions.
function sendTopic (magic, show_id, parent, type, title, subtitle, content)
{
    POST(show_id, '?in&forum&topic&send',
          'type='+type+'&'
        + 'parent='+parent+'&'
        + 'title='+rawurlencode(title)+'&'
        + 'subtitle='+rawurlencode(subtitle)+'&'
        + 'content='+rawurlencode(content)+'&'
        + 'magic='+rawurlencode(magic));
}

function sendPost (magic, show_id, topic_id, title, content)
{
    POST(show_id, '?in&forum&post&send',
        'topic_id='+topic_id+'&'
      + 'title='+rawurlencode(title)+'&'
      + 'content='+rawurlencode(content)+'&'
      + 'magic='+rawurlencode(magic));
}

// Misc functions
function getContent (id)
{
    return document.getElementById(id).value;
}

function rawurlencode (value)
{
    var encoded = value;
    encoded = encoded.replace(/\?/g, '%3F');
    encoded = encoded.replace(/=/g,  '%3D');
    encoded = encoded.replace(/&/g,  '%26');
    encoded = encoded.replace(/\n/g, '%0A');
    encoded = encoded.replace(/ /g,  '%20');
    encoded = encoded.replace(/\+/g, '%2B');

    return encoded;
}

function urlencode (value)
{
    var encoded = document.getElementById(value).value;

    return rawurlencode(encoded);
}

