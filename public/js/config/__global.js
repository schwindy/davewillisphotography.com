var BASE_URL = location.protocol+"\/\/"+location.hostname+"\/";
var SITE_URL = BASE_URL;
var PATH_CSS = BASE_URL+'css/';
var PATH_IMG = BASE_URL+'img/';
var PATH_INC = BASE_URL+'inc/';
var PATH_JS = BASE_URL+'js/';
var PATH_LIB = BASE_URL+'lib/';
var PATH_PHP = BASE_URL+'php/';
var PATH_PORTAL = BASE_URL+'php/portal';
var PATH_WORLD = BASE_URL+'php/world';

var CURRENT_PATH = location.href.substr(location.href.lastIndexOf("/"));
var CURRENT_PAGE = CURRENT_PATH.indexOf("?")!== -1?CURRENT_PATH.substr(1, CURRENT_PATH.indexOf("?")-1):CURRENT_PATH.substr(1);
var PATH_ACCOUNT = BASE_URL+'account/';
var PATH_HOME = BASE_URL+'home/';