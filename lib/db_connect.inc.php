<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/adodb/adodb.inc.php");

/*
$database_type="mysqlt";    // to support the transaction 
$db_host = "localhost"; // local database
$db_user = "fmoladm";
$db_password = "123";
$db_database = "fmol";
*/

define('DATABASE_TYPE', "mysqlt");  // to support the transaction 
define('DB_HOST', "localhost");  // local database
define('DB_USER', "fmolphp");
define('DB_PASSWORD', "123");
define('DB_DATABASE', "fmol");

$db = NewADOConnection(DATABASE_TYPE);
$db->PConnect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
$db->Execute('set names "utf8"');  // added to show the chinese character in the page
$db->Execute('SET CHARACTER SET "utf8"');  // added to show the chinese character in the page
$db->Execute('SET COLLATION_CONNECTION="utf8_general_ci"');  // added to show the chinese character in the page




?> 