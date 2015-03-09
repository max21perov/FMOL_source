
<?php

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");


//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile('preferences.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$p_team_id = sql_quote($_SESSION['s_primary_team_id']);

$club_name = sql_quote($_SESSION['s_self_club_name']);


/**
 * get the theme color from Database 
 */
$preferences_theme_color_arr = preferences_getThemeColor($db, $p_team_id);
$tpl->setVariable("BG_COLOR_VALUE", $preferences_theme_color_arr["bg_color"]);
$tpl->setVariable("FONT_COLOR_VALUE", $preferences_theme_color_arr["font_color"]);


$tpl->setVariable("CLUB_NAME", $club_name); 
//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time
$tpl->setVariable("SPACE", " ");
$tpl->show();


//----------------------------------------------------------------------------	
// 自定义函数部分
//----------------------------------------------------------------------------	
/**
 * get the theme color from Database 
 *
 * @param [tpl]			php 模板变量
 * @param [team_id]		team_id
 *
 * @return  no
 */		
function preferences_getThemeColor($db, $team_id)
{
	$theme_color_arr = array();
	
	
	$query = sprintf(
			" SELECT bg_color, font_color " . 
			" FROM theme_color " .
			" WHERE team_id='%s' ",
			$team_id
			);  
	$rs = &$db->Execute($query);
	
	if (!$rs) {
	    print "Database error.";
	    exit(0);
	}
	else if ($rs->RecordCount() > 0){			
		$theme_color_arr["bg_color"] = $rs->fields['bg_color'];
		$theme_color_arr["font_color"] = $rs->fields['font_color'];	
	}
	else {
		$theme_color_arr["bg_color"] = "#c4a47b";
		$theme_color_arr["font_color"] = "#ffffff";
			
	}
	
	return $theme_color_arr;	
}


?>


