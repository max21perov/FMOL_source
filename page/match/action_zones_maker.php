<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once("team_stats_functions.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("action_zones.tpl.php", true, true); 

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
$PR_opposite = array(
			   		"PROZ_0" => "PROZ_8", "PROZ_1" => "PROZ_7", "PROZ_2" => "PROZ_6",
			   		"PROZ_3" => "PROZ_5", "PROZ_4" => "PROZ_4", "PROZ_5" => "PROZ_3",
			   		"PROZ_6" => "PROZ_2", "PROZ_7" => "PROZ_1", "PROZ_8" => "PROZ_0"
			   );

/**
 * get the match team arr
 */
$match_team_arr = getMatchTeamArr($db, $match_type, $match_id);
$home_team_id = $match_team_arr['home_team_id'];
$away_team_id = $match_team_arr['away_team_id'];


// PR_arr: T F M B L C R, PROZ_0~8
$PR_arr = getPRArr($db, $match_type, $match_id, $home_team_id, $away_team_id); 

// theme_color
$theme_color_arr = getThemeColorArr($db, $home_team_id, $away_team_id); 			   
	   


foreach ($PR_opposite as $PR_type => $opp_PR_type) {

	$tpl->setVariable("HOME_" . $PR_type, $PR_arr[$home_team_id][$PR_type] . "%");
	$tpl->setVariable("AWAY_" . $opp_PR_type, $PR_arr[$away_team_id][$opp_PR_type] . "%");
	
}

$tpl->setVariable("HOME_PROZ_FONT_COLOR", $theme_color_arr[$home_team_id]["font_color"]);
$tpl->setVariable("AWAY_PROZ_FONT_COLOR", $theme_color_arr[$away_team_id]["font_color"]);

$tpl->setVariable("MATCH_TYPE", $match_type);
$tpl->setVariable("MATCH_ID", $match_id);




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
 * get PR: T F M B L C R, PROZ_0~8
 *
 * @param [db]					db	
 * @param [match_id]			match_id	
 * @param [home_team_id]		home_team_id
 * @param [away_team_id]		away_team_id
 *
 * @return  $PR_arr
 */	
function getPRArr($db, $match_type, $match_id, $home_team_id, $away_team_id)
{
	$PR_arr = array();

	$query = sprintf(
				" select team_id, PR_T, PR_F, PR_M, PR_B, " . 
				" PR_L, PR_C, PR_R, PROZ_0, PROZ_1, " . 
				" PROZ_2, PROZ_3, PROZ_4, PROZ_5, PROZ_6, " .
				" PROZ_7, PROZ_8 " .
				" from team_match_stat " .
				" where match_type='%s' and match_id='%s' and (team_id='%s' or team_id='%s') " .
				" order by team_id ASC ",
				$match_type, $match_id, $home_team_id, $away_team_id);
				
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		 for (; !$rs->EOF; $rs->MoveNext()) { 
			$team_id = $rs->fields['team_id'];
			
			$PR_arr[$team_id]['PR_T'] = $rs->fields['PR_T'];			
			$PR_arr[$team_id]['PR_F'] = $rs->fields['PR_F'];
			$PR_arr[$team_id]['PR_M'] = $rs->fields['PR_M'];
			$PR_arr[$team_id]['PR_B'] = $rs->fields['PR_B'];
			$PR_arr[$team_id]['PR_L'] = $rs->fields['PR_L'];
			$PR_arr[$team_id]['PR_C'] = $rs->fields['PR_C'];
			$PR_arr[$team_id]['PR_R'] = $rs->fields['PR_R'];
			$PR_arr[$team_id]['PROZ_0'] = $rs->fields['PROZ_0'];
			$PR_arr[$team_id]['PROZ_1'] = $rs->fields['PROZ_1'];
			$PR_arr[$team_id]['PROZ_2'] = $rs->fields['PROZ_2'];
			$PR_arr[$team_id]['PROZ_3'] = $rs->fields['PROZ_3'];
			$PR_arr[$team_id]['PROZ_4'] = $rs->fields['PROZ_4'];
			$PR_arr[$team_id]['PROZ_5'] = $rs->fields['PROZ_5'];
			$PR_arr[$team_id]['PROZ_6'] = $rs->fields['PROZ_6'];
			$PR_arr[$team_id]['PROZ_7'] = $rs->fields['PROZ_7'];
			$PR_arr[$team_id]['PROZ_8'] = $rs->fields['PROZ_8'];
		}
	}		
	
	return $PR_arr;
}

/**
 * get PR: T F M B L C R, PROZ_0~8
 *
 * @param [db]					db	
 * @param [home_team_id]		home_team_id
 * @param [away_team_id]		away_team_id
 *
 * @return  $theme_color_arr
 */	
function getThemeColorArr($db, $home_team_id, $away_team_id)
{
	$theme_color_arr = array();

	$query = sprintf(
				" select team_id, bg_color, font_color " . 
				" from theme_color " .
				" where team_id='%s' or team_id='%s' " .
				" order by team_id ASC ",
				$home_team_id, $away_team_id);
				
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		 for (; !$rs->EOF; $rs->MoveNext()) { 
			$team_id = $rs->fields['team_id'];
			
			$theme_color_arr[$team_id]['bg_color'] 		= $rs->fields['bg_color'];			
			$theme_color_arr[$team_id]['font_color'] 	= $rs->fields['font_color'];
		}
	}		
	
	return $theme_color_arr;
	
}


?>


