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
$tpl->loadTemplatefile("match_result.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = $_SESSION['s_primary_team_id'];

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$match_type = sql_quote($_GET['match_type']);
$match_id = sql_quote($_GET['match_id']);

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	


/**
 * display the match result
 */
$match_team_arr = displayMatchResult($db, $tpl, $match_type, $match_id, $s_primary_team_id);



// --------------------------------
/**
 * get the theme color from Database 
 */
$p_home_team_id = $match_team_arr['home_team_id'];
$home_theme_color_arr = getThemeColor_MatchResult($db, $p_home_team_id);
$p_away_team_id = $match_team_arr['away_team_id'];
$away_theme_color_arr = getThemeColor_MatchResult($db, $p_away_team_id);

/**
 * set the theme color
 * 以下这两个是球队的主题色
 */
$tpl->setVariable("HOME_THEME_BGCOLOR", $home_theme_color_arr["bg_color"]);
$tpl->setVariable("HOME_THEME_FONTCOLOR", $home_theme_color_arr["font_color"]);

$tpl->setVariable("AWAY_THEME_BGCOLOR", $away_theme_color_arr["bg_color"]);
$tpl->setVariable("AWAY_THEME_FONTCOLOR", $away_theme_color_arr["font_color"]);


//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------
// add a space in the page to show the page any time
$tpl->setVariable("SPACE", " ");

$tpl->show();


//----------------------------------------------------------------------------	
// functions
//----------------------------------------------------------------------------	

/**
 * display the match result
 *
 * @param [db]						db	
 * @param [tpl]						tpl
 * @param [match_id]				match_id	
 * @param [s_primary_team_id]		s_primary_team_id
 *
 * @return  $match_team_arr
 *   $match_team_arr["home_team_id"]
 *   $match_team_arr["home_team_name"]
 *   $match_team_arr["away_team_id"]
 *   $match_team_arr["away_team_name"]
 */	
function displayMatchResult($db, $tpl, $match_type, $match_id, $s_primary_team_id)
{
	$match_team_arr = array();
	$match_type_str = "schedule";
	switch(intval($match_type)) {
	case 0:
		$match_type_str = "schedule";
		break;
	case 1:
		$match_type_str = "friendly";
		break;
	}
	
	$query = sprintf(
				" select rank1.rank as home_rank, team1.name as home_team, " . 
				" m.home_id, m.home_score, m.away_score, " .
				" team2.name as away_team, m.away_id, rank2.rank as away_rank " .
				" from %s m, team team1, team team2, rank rank1, rank rank2 " .
				" where m.id='%s' " .
				" and m.home_id=team1.team_id and m.away_id=team2.team_id " .
				" and m.home_id=rank1.team_id and m.away_id=rank2.team_id ",
				$match_type_str, $match_id);
				
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error.";  // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		if ($rs->RecordCount() >= 1) {
	
			
			// set the self team name's class: to indicate the self team
			if ($rs->fields['home_id'] == $s_primary_team_id)
				$tpl->setVariable("HOME_TEAM_CLASS", 'SelfTeamText') ;
			else 
				$tpl->setVariable("HOME_TEAM_CLASS", 'OtherTeamText') ;	
			if ($rs->fields['away_id'] == $s_primary_team_id)
				$tpl->setVariable("AWAY_TEAM_CLASS", 'SelfTeamText') ;
			else 
				$tpl->setVariable("AWAY_TEAM_CLASS", 'OtherTeamText') ;	
				
			$tpl->setVariable("match_id", $match_id) ;
			$tpl->setVariable("HOME_POS", $rs->fields['home_rank']) ;
			$tpl->setVariable("HOME_PRIMARY_TEAM_ID", $rs->fields['home_id']) ;
			$tpl->setVariable("HOME_TEAM", $rs->fields['home_team']) ;
			$tpl->setVariable("HOME_SCORE", $rs->fields['home_score']) ;
			$tpl->setVariable("AWAY_SCORE", $rs->fields['away_score']) ;
			$tpl->setVariable("AWAY_PRIMARY_TEAM_ID", $rs->fields['away_id']) ;
			$tpl->setVariable("AWAY_TEAM", $rs->fields['away_team']) ;
			$tpl->setVariable("AWAY_POS", $rs->fields['away_rank']) ;
			

			
			$match_team_arr["home_team_id"] = $rs->fields['home_id'];
			$match_team_arr["home_team_name"] = $rs->fields['home_team'];
			$match_team_arr["away_team_id"] = $rs->fields['away_id'];
			$match_team_arr["away_team_name"] = $rs->fields['away_team'];
			
		}
	}		
	
	return $match_team_arr;
	
}

/**
 * get the theme color from Database 
 *
 * @param [tpl]			php 模板变量
 * @param [team_id]		team_id
 *
 * @return  no
 */		
function getThemeColor_MatchResult($db, $team_id)
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


