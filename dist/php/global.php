<?php
include("$_SERVER[DOCUMENT_ROOT]/dist/php/f_phxtime.php");
include("$_SERVER[DOCUMENT_ROOT]/config/config.php");
include("$_SERVER[DOCUMENT_ROOT]/dist/php/functions.php");
define('HTTP', "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']);
define('IFTA_UPLOAD',$_SERVER['DOCUMENT_ROOT']."/ifta_uploads");

$localdate = phx_time("date");
$localtime = phx_time("time");
$localdateYear = phx_time("dateYear");
$recaptcha_key = $recaptcha_key;

define("BX_HAWB", "bx_hawb");
define("BX_PUDN", "PUAgentDriverName");
define("BX_PUDP", "PUAgentDriverPhone");
define("BX_LP", "LoadPosition");
define("BX_TI", "TruckID");
define("BX_LD", "bx_localdate");
define("BX_LT", "bx_localtime");

define("VTEXTFILE", "./vtext_message.txt");

# Config array isn't used yet.
$config = array(
    "db" => array(
        "db1" => array(
            "dbname" => "database1",
            "username" => "dbUser",
            "password" => "pa$$",
            "host" => "localhost"
        ),
        "db2" => array(
            "dbname" => "database2",
            "username" => "dbUser",
            "password" => "pa$$",
            "host" => "localhost"
        )
    ),
    "urls" => array(
        "baseUrl" => "http://example.com"
    ),
    "paths" => array(
        "resources" => "/path/to/resources",
        "images" => array(
            "content" => $_SERVER["DOCUMENT_ROOT"] . "/images/content",
            "layout" => $_SERVER["DOCUMENT_ROOT"] . "/images/layout"
        )
    )
);
 
/*
    I will usually place the following in a bootstrap file or some type of environment
    setup file (code that is run at the start of every page request), but they work 
    just as well in your config file if it's in php (some alternatives to php are xml or ini files).
*/
 
/*
    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
*/
defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));
     
defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));
 
/*
    Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);
?>
