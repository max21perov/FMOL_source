<?php

//$document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
//require_once ("$document_root/lib/db_connect.inc.php");
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
//require_once(DOCUMENT_ROOT . "/lib/db_connect_normal.inc.php");

// get the parameters from client 
$comment_id = sql_quote($_GET["comment_id"]); 
$cur_minute_index = sql_quote($_GET["cur_minute_index"]);

//----------------------------------------------------------------------------	
// use the normal method to visit the database
//----------------------------------------------------------------------------	

$link = mysql_connect("localhost", "fmolphp", "123") ;  //or die("Could not connect: " . mysql_error()); 
mysql_select_db("fmol");

//----------------------------------------------------------------------------	
// get the data from database 
//----------------------------------------------------------------------------	

$seperater_big = "||";
$seperater_middle = "**";
$seperater_small = "^^";
	
/**
 * get comment info
 */
$comment_info_arr = getCommentInfo($comment_id);


$cur_minute_comment = getCurMinuteComment($cur_minute_index, $comment_info_arr, $seperater_big);

// change the character from GBK to UTF-8 (important)
$cur_minute_comment = iconv("GBK", "UTF-8", $cur_minute_comment);

$resultXML  = getResultXML($cur_minute_comment);



// return the text to client
echo $resultXML;



//----------------------------------------------------------------------------	
// functions
//----------------------------------------------------------------------------	

/**
 * get comment info
 *
 * @param [comment_id]			comment_id	
 *
 * @return  $comment_info_arr
 */
function getCommentInfo($comment_id)
{
	$comment_info_arr = array();
	
	$query = sprintf(
				" SELECT minutes, comment " . 
				" FROM comment " . 
				" WHERE id='%s' " ,
				$comment_id);
	
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	
	$comment_info_arr["minutes"] = $row[0];
	$comment_info_arr["comment"] = $row[1];
	
	return $comment_info_arr;
}

/**
 * get cur minute comment
 *
 * @param [cur_minute_index]		cur_minute_index
 * @param [comment_info_arr]		comment_info_arr
 * @param [seperater_big]			seperater_big 
 *
 * @return  $cur_minute_comment
 */
function getCurMinuteComment($cur_minute_index, $comment_info_arr, $seperater_big)
{
	$cur_minute_comment = "";	
	
	$minutes = $comment_info_arr["minutes"];
	$comment = $comment_info_arr["comment"];
	
	$minute_arr = explode($seperater_big, $minutes);
	$comment_arr = explode($seperater_big, $comment);

	// find the current minute report	
	if (($cur_minute_index < count($minute_arr)) && ($cur_minute_index < count($minute_arr))) {
		$cur_minute_comment = $comment_arr[$cur_minute_index];
	}

	if (strlen($cur_minute_comment) == 0) {
		$cur_minute_comment = "no comment!";
	}
	
	return $cur_minute_comment;
}

/**
 * get result XML
 *
 * @param [cur_minute_comment]		cur_minute_comment 
 *
 * @return  $resultXML
 */
function getResultXML($cur_minute_comment)
{
	// the style of xml must according the following way
	
	$resultXML  = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
	$resultXML .= "<root>";
	$resultXML .= "<data>";
	$resultXML .= "<comment>$cur_minute_comment</comment>"; 
	$resultXML .= "</data>";
	$resultXML .= "</root>"; 
	
	return $resultXML;
}

?>

