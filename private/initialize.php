<?php
ob_start(); // output buffering is turned on

session_start(); // turn on sessions

define("PRIVATE_PATH", dirname(__FILE__));  // gives the current diroctory of inizialize.php -> private
define("PROJECT_PATH", dirname(PRIVATE_PATH)); // get the current directory of diractory "private" -> globe_bank
define("PUBLIC_PATH", PROJECT_PATH . "/public"); // get the directory of public -> global_bank/public
define("SHARED_PATH", PRIVATE_PATH . "/shared"); // get the directory of shared -> private/shared


$public_end = strpos($_SERVER['SCRIPT_NAME'],"/public") + 7; // $_SERVER['SCRIPT_NAME'] = globe_bank/public/staff/index.php
$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end); 
define("WWW_ROOT", $doc_root);  //  doc_root = globe_bank/public

// echo $_SERVER['SCRIPT_NAME'] . "<br>";
// echo WWW_ROOT . "<br>";
// echo PUBLIC_PATH . "<br>";

require_once('functions.php');
require_once("database.php");
require_once("query_functions.php");
require_once("validation_functions.php");
require_once("auth_functions.php");

$db = db_connect();
$errors= []; // for error handling
?>
