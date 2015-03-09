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
$tpl->loadTemplatefile("match_stats.tpl.php", true, true); 

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
 * get the match team
 */
$match_team_arr = getMatchTeamArr($db, $match_type, $match_id);

// set the self team name's class: to indicate the self team					
$tpl->setVariable("HOME_PRIMARY_TEAM_ID", $match_team_arr['home_team_id']) ;
$tpl->setVariable("HOME_TEAM", $match_team_arr['home_team_name']) ;
$tpl->setVariable("AWAY_PRIMARY_TEAM_ID", $match_team_arr['away_team_id']) ;
$tpl->setVariable("AWAY_TEAM", $match_team_arr['away_team_name']) ;


/**
 * get the match stats of teams
 */
$match_stats_arr = getMatchStatsOfTeams($db, $match_type, $match_id, $match_team_arr);

$stats_item_name_arr = getTeamStatsItemNameArr();


/**
 * display the match stats
 */
displayMatchStats($db, $tpl, $match_stats_arr, $stats_item_name_arr);


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
 * get Match stats of teams
 *
 * @param [db]						db	
 * @param [match_id]				match_id	
 * @param [match_team_arr]			match_team_arr
 *
 * @return  $match_team_arr
 *   $match_team_arr["home_team"]
 *   $match_team_arr["away_team"]
 */	
function getMatchStatsOfTeams($db, $match_type, $match_id, $match_team_arr)
{
	$match_stats_arr = array();
	
	$query = sprintf(
				" select team_id, shots, shots_on, shots_off, PR_T, " . 
				" corners, free_kicks, throw_ins, fouls, offsides, " .
				" passes_completed, crosses_completed, tackles_won, headers_won, yellow_cards, " .
				" red_cards " .
				" from team_match_stat " .
				" where match_type='%s' and match_id='%s' ",
				$match_type, $match_id);
				
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error.";  // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		for (; !$rs->EOF; $rs->MoveNext()) { 
			$match_stats = array();
			$match_stats["Shots"] = $rs->fields["shots"];
			$match_stats["On Target"] = $rs->fields["shots_on"];
			$match_stats["Off Target"] = $rs->fields["shots_off"];
			$match_stats["Procession"] = $rs->fields["PR_T"] . "%";
			$match_stats["Corners"] = $rs->fields["corners"];
			$match_stats["Free Kicks"] = $rs->fields["free_kicks"];
			$match_stats["Throw-ins"] = $rs->fields["throw_ins"];
			$match_stats["Fouls"] = $rs->fields["fouls"];
			$match_stats["Offsides"] = $rs->fields["offsides"];
			$match_stats["Passes Completed"] = $rs->fields["passes_completed"] . "%";
			$match_stats["Crosses Completed"] = $rs->fields["crosses_completed"] . "%";
			$match_stats["Tackles Won"] = $rs->fields["tackles_won"] . "%";
			$match_stats["Headers Won"] = $rs->fields["headers_won"] . "%";
			$match_stats["Yellow Cards"] = $rs->fields["yellow_cards"];
			$match_stats["Red Cards"] = $rs->fields["red_cards"];
			
			if ($match_team_arr["home_team_id"] == $rs->fields['team_id']) {
				$match_stats_arr["home_team"] = $match_stats;				
			}
			else if ($match_team_arr["away_team_id"] == $rs->fields['team_id']) {
				$match_stats_arr["away_team"] = $match_stats;							
			}
			
		}
	}		
	
	return $match_stats_arr;
}

/**
 * get team stats item name arr
 *
 *
 * @return  $item_name_arr
 */	
function getTeamStatsItemNameArr()
{
	$item_name_arr = array();
	$i = 0;
	$item_name_arr[$i++] = "Shots";
	$item_name_arr[$i++] = "On Target";
	$item_name_arr[$i++] = "Off Target";
	$item_name_arr[$i++] = "Procession";
	$item_name_arr[$i++] = "Corners";
	$item_name_arr[$i++] = "Free Kicks";
	$item_name_arr[$i++] = "Throw-ins";
	$item_name_arr[$i++] = "Fouls";
	$item_name_arr[$i++] = "Offsides";
	$item_name_arr[$i++] = "Passes Completed";
	$item_name_arr[$i++] = "Crosses Completed";
	$item_name_arr[$i++] = "Tackles Won";
	$item_name_arr[$i++] = "Headers Won";
	$item_name_arr[$i++] = "Yellow Cards";
	$item_name_arr[$i++] = "Red Cards";
	
	return $item_name_arr;
}

/**
 * display the match goals
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [match_stats_arr]		match_stats_arr	
 * @param [stats_item_name_arr]	stats_item_name_arr
 *
 * @return  void
 */	
function displayMatchStats($db, $tpl, $match_stats_arr, $stats_item_name_arr)
{	

	$len = count($stats_item_name_arr);
	for ($i=0; $i<$len; ++$i) {
		$item_name = $stats_item_name_arr[$i];
		
		$tpl->setCurrentBlock("match_stats") ;
		
		$tpl->setVariable("HOME_TEAM_STATS", $match_stats_arr["home_team"][$item_name]) ;
		$tpl->setVariable("STATS_ITEM_NAME", $item_name) ;
		$tpl->setVariable("AWAY_TEAM_STATS", $match_stats_arr["away_team"][$item_name]) ;
		
		if ($item_name == "Yellow Cards") {
			$tpl->setVariable("STATS_TR_CLASS", "gSGRowYellow") ;
		}
		else if ($item_name == "Red Cards") {
			$tpl->setVariable("STATS_TR_CLASS", "gSGRowRed") ;
		}
		else {
			$tpl->setVariable("STATS_TR_CLASS", "gSGRowOdd") ;
		}
		
		$tpl->parseCurrentBlock("match_stats") ;
	}
			
}


?>


