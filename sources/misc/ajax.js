/**
* @package lulzBB
* @author  cHoBi
* @license GPLv3 http://opensource.org/licenses/gpl-3.0.html
*/

var section_page = 'first';

function init() {
    argv = init.arguments;

    showMenu('menu');

    if (argv[0] == true) {
        showSection('middle', 0);
    }
}

// Base functions
function GET(show_id, url) {
    var http = ((window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP")
                                       : new XMLHttpRequest());

    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            document.getElementById(show_id).innerHTML = http.responseText;
        }
    }
    
    http.open("GET", url, true);
    document.getElementById(show_id).innerHTML = "<center>Loading...</center>";
    http.send(null);
}

function POST(show_id, url, params) {
    var http = ((window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") 
                                       : new XMLHttpRequest());
 
    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            document.getElementById(show_id).innerHTML = http.responseText;
        }
    }

    http.open( "POST", url, true);

    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", params.length);
    http.setRequestHeader("Connection", "close");

    document.getElementById(show_id).innerHTML = "<center>Loading...</center>";
    http.send(params);
}
function POSTraw(url, params) {
    var http = ((window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") 
                                       : new XMLHttpRequest());

    http.open( "POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.setRequestHeader("Content-length", params.length);
    http.setRequestHeader("Connection", "close");
    http.send(params);
}

function showMenu(show_id) {
    GET(show_id, '?output&menu');
}
function showPage(show_id, page) {
    GET(show_id, '?output&page='+page);
}

// Forum functions
function showSection(show_id, section_id, page) {
    var page = (page == null) ? section_page : page;
    section_page = page;

    POST(show_id, '?output&forum&section', 'id='+section_id+'&page='+page);
}
function showTopic(show_id, topic_id, post_id) {
    var post_id = (post_id == null) ? 1 : post_id;

    POST(show_id, '?output&forum&topic&show', 'id='+topic_id+'&post_id=');
}

// Login functions
function login(username, password) {
    POSTraw('?input&login', 'username='+username+'&password='+password);
}
function showLogin(show_id) {
    GET(show_id, '?output&login');
}
function logout(show_id) {
    GET(show_id, '?input&logout');
}

// Registration functions
function showRegistration(show_id) {
    GET(show_id, '?output&register');
}

// Send functions.
function sendTopic(magic, show_id, parent, type, title, subtitle, content) {
    POST(show_id, '?input&topic&send',
          'type='+type+'&'
        + 'parent='+parent+'&'
        + 'title='+rawurlencode(title)+'&'
        + 'subtitle='+rawurlencode(subtitle)+'&'
        + 'content='+rawurlencode(content)+'&'
        + 'magic='+rawurlencode(magic));
}

function sendPost(magic, show_id, topic_id, title, content) {
    POST(show_id, '?input&forum&post&send',
        'topic_id='+topic_id+'&'
      + 'title='+rawurlencode(title)+'&'
      + 'content='+rawurlencode(content)+'&'
      + 'magic='+rawurlencode(magic));
}

// Misc functions
function getContent(id) {
    return document.getElementById(id).value;
}

function urlencode(value) {
    var encoded = document.getElementById(value).value;
    encoded = encoded.replace(/\?/g, '%3F');
    encoded = encoded.replace(/=/g,  '%3D');
    encoded = encoded.replace(/&/g,  '%26');
    encoded = encoded.replace(/\n/g, '%0A');
    encoded = encoded.replace(/ /g,  '%20');

    return encoded;
}

function rawurlencode(value) {
    var encoded = value;
    encoded = encoded.replace(/\?/g, '%3F');
    encoded = encoded.replace(/=/g,  '%3D');
    encoded = encoded.replace(/&/g,  '%26');
    encoded = encoded.replace(/\n/g, '%0A');
    encoded = encoded.replace(/ /g,  '%20');

    return encoded;
}
