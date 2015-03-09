<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");


if($_SERVER["REQUEST_METHOD"] != "POST")    //check the page is request by method "POST"
{	
	// go back to the page "stadium.php"
	goToPageInTime(0, "/fmol/page/preferences/preferences.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("savePreferences" == $myaction) {
	performSavePreferences($db, DOCUMENT_ROOT);
}
else {
	goToPageInTime(0, "/fmol/page/preferences/preferences.php");
}

//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
/**
 * save preferences
 *
 * @param [db]				db
 * @param [document_root]	document_root
 *
 * @return return true or false
 */	
function performSavePreferences($db, $document_root)
{
	$p_team_id = sql_quote($_SESSION['s_primary_team_id']);
	$bg_color = sql_quote($_POST["bg_color"]);
	$font_color = sql_quote($_POST["font_color"]);
	
	// update the theme color of team
	$returnValue = updateThemeColor($db, $p_team_id, $bg_color, $font_color);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}

	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
	
	// 最后，返回 preferences.php 页面
	goToPageInTime(2, "/fmol/page/preferences/preferences.php");	
	
	return true;
}



//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------
/**
 * update the theme color of team
 *
 * @param [db]				database
 * @param [p_team_id]		team 的主键
 * @param [bg_color]		background color
 * @param [font_color]		font color
 *
 * @return return error message
 */	
function updateThemeColor($db, $p_team_id, $bg_color, $font_color)
{
	$query = sprintf(
				" UPDATE theme_color SET " . 
				" bg_color='%s', font_color='%s' " . 
				" WHERE team_id='%s' " ,
				$bg_color, $font_color, $p_team_id);
	$rs = &$db->Execute($query);
	
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


?>



