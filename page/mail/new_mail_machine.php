<?php

//$document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
//require_once ("$document_root/lib/db_connect.inc.php");
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
//require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
//require_once(DOCUMENT_ROOT . "/lib/db_connect_normal.inc.php");

// get the parameters from client 
$to_id = sql_quote($_GET["to_id"]); 

//----------------------------------------------------------------------------	
// use the simple method to connect database
//----------------------------------------------------------------------------	
 

$link = mysql_connect("localhost", "fmolphp", "123") ;  //or die("Could not connect: " . mysql_error()); 
mysql_select_db("fmol");

//----------------------------------------------------------------------------	
// get the data from database 
//----------------------------------------------------------------------------	
/**
 * get the comment info
 */
$query = sprintf(
			" SELECT count(1) AS count " . 
			" FROM mail " . 
			" WHERE to_id='%s' " . 
			" AND status='0' " ,
			$to_id);

$result = mysql_query($query);
$row = mysql_fetch_row($result);

$count = $row[0];

// xml , must be written in this method, line by line
$resultXML  = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
$resultXML .= "<root>";
$resultXML .= "<data>";
$resultXML .= "<mail_count>$count</mail_count>"; 
$resultXML .= "</data>";
$resultXML .= "</root>"; 

// return the text to client
echo $resultXML;




?>

