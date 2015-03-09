
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
$tpl->loadTemplatefile('status_bar.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$self_team_id = sql_quote($_SESSION['s_primary_team_id']);

$team_id = sql_quote($_GET['team_id']);
$opponent_team_id = sql_quote($_GET['team_id']);;


$home_flag = false;
if (strlen($team_id) == 0 || $team_id==$self_team_id) {
	$team_id = $self_team_id;
	
	$home_flag = true;	
}





// --------------------------------
/**
 * the club info
 */
// get the club info from Database  
$club_info_arr = getClubInfo($db, $team_id);

// display the club info
if (strlen($club_info_arr["user_name"]) == 0) {
	$tpl->setVariable("NO_USER_NAME", "no user");
}
else {	
	$tpl->setVariable("USER_NAME", $club_info_arr["user_name"]);
}
$tpl->setVariable("CLUB_ID", $club_info_arr["team_id"]);
$tpl->setVariable("CLUB_NAME", $club_info_arr["club_name"]);
$tpl->setVariable("TEAM_ID", $club_info_arr["team_id"]);

/**
 *  the theme color 
 */
// get the theme color from Database 
$theme_color_arr = getThemeColor($db, $team_id);

// set the theme color
// 以下这两个是球队的主题色
$tpl->setVariable("STATUS_BAR_BGCOLOR", $theme_color_arr["bg_color"]);
$tpl->setVariable("STATUS_BAR_FONTCOLOR", $theme_color_arr["font_color"]);



$tpl->setVariable("OPPONENT_TEAM_ID", $opponent_team_id);
$tpl->setVariable("SELF_TEAM_ID", $self_team_id);

if ($home_flag) $tpl->setVariable("NEWS_DISPLAY", "");
else  $tpl->setVariable("NEWS_DISPLAY", "none");
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
 * get the club info from Database 
 *
 * @param [tpl]			php 模板变量
 * @param [team_id]		team_id
 *
 * @return  no
 */		
function getClubInfo($db, $team_id)
{
	$club_info_arr = array();
		
	$query = sprintf(
			" SELECT u.user_id, u.name as user_name, c.club_id, " . 
			" c.name as club_name, t.name as team_name " .
			" FROM club c, team t " .
			" LEFT JOIN user_info u ON c.user_id=u.user_id  " .
			" WHERE t.team_id='%s' AND c.club_id=t.club_id ",
			$team_id
			);  
	$rs = &$db->Execute($query);
	
	if (!$rs) {
	    print "Database error.";
	    exit(0);
	}
	else if ($rs->RecordCount() > 0){				
		$club_info_arr["user_id"] = $rs->fields['user_id'];
		$club_info_arr["user_name"] = $rs->fields['user_name'];
		$club_info_arr["club_id"] = $rs->fields['club_id'];
		$club_info_arr["club_name"] = $rs->fields['club_name'];
		$club_info_arr["team_id"] = $team_id;
		$club_info_arr["team_name"] = $rs->fields['team_name'];
	}
	
	return $club_info_arr;	
}

/**
 * get the theme color from Database 
 *
 * @param [tpl]			php 模板变量
 * @param [team_id]		team_id
 *
 * @return  no
 */		
function getThemeColor($db, $team_id)
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
	
	return $theme_color_arr;	
}


?>


