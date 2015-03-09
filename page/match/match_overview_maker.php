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
$tpl->loadTemplatefile("match_overview.tpl.php", true, true); 

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
$match_team_arr = getMatchTeamArr($db, $tpl, $match_type, $match_id, $s_primary_team_id);


/**
 * display the match result
 */
displayMatchGoals($db, $tpl, $match_type, $match_id, $match_team_arr);


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
 * get Match Team Arr
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
function getMatchTeamArr($db, $tpl, $match_type, $match_id, $s_primary_team_id)
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
				" select team1.name as home_team, m.home_id, " . 
				" team2.name as away_team, m.away_id " .
				" from %s m, team team1, team team2 " .
				" where m.id='%s' " .
				" and m.home_id=team1.team_id and m.away_id=team2.team_id ",
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
				
			$tpl->setVariable("HOME_PRIMARY_TEAM_ID", $rs->fields['home_id']) ;
			$tpl->setVariable("HOME_TEAM", $rs->fields['home_team']) ;
			$tpl->setVariable("AWAY_PRIMARY_TEAM_ID", $rs->fields['away_id']) ;
			$tpl->setVariable("AWAY_TEAM", $rs->fields['away_team']) ;
			
			
			$match_team_arr["home_team_id"] = $rs->fields['home_id'];
			$match_team_arr["home_team_name"] = $rs->fields['home_team'];
			$match_team_arr["away_team_id"] = $rs->fields['away_id'];
			$match_team_arr["away_team_name"] = $rs->fields['away_team'];
			
		}
	}		
	
	return $match_team_arr;
	
}

/**
 * display the match goals
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [match_id]			match_id	
 * @param [match_team_arr]		match_team_arr
 *
 * @return  void
 */	
function displayMatchGoals($db, $tpl, $match_type, $match_id, $match_team_arr)
{
	$home_team_id = $match_team_arr["home_team_id"];
	$home_team_name = $match_team_arr["home_team_name"];  
	$away_team_id = $match_team_arr["away_team_id"];
	$away_team_name = $match_team_arr["away_team_name"];

	$query = sprintf(
				" select g.id, g.match_id, g.goal_id, g.minute, " . 
				" g.team_id, g.player_id, g.player_name, " .
				" p.custom_given_name as given_name, p.custom_family_name as family_name " .
				" from goal g " .
				" LEFT JOIN player p ON g.player_id=p.player_id " .
				" where g.match_type='%s' and g.match_id='%s' and (g.team_id='%s' or g.team_id='%s') " .
				" order by g.minute ",
				$match_type, $match_id, $home_team_id, $away_team_id);

	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error."; //  $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		$block_name = "";
		$team_name = "";
		for (; !$rs->EOF; $rs->MoveNext()) {
			if ($rs->fields["team_id"] == $home_team_id) {
				$block_name = "home_team_goal";
				$team_name = $home_team_name;
			}
			else {
				$block_name = "away_team_goal";
				$team_name = $away_team_name;				
			}
			
			$full_name = "";
			if ($rs->fields['player_id'] == "") {  // is [gray player]
				$full_name = $rs->fields['player_name'];
			}
			else {
				if ($rs->fields['given_name'] == "") {
					$full_name = $rs->fields['family_name'];
				}
				else {
					$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
				}
			}
		
			// block
			$tpl->setCurrentBlock($block_name) ;
			
			$tpl->setVariable("GOAL_ID", $rs->fields["id"]) ;
			$tpl->setVariable("MINUTE", $rs->fields["minute"]) ;
			$tpl->setVariable("PLAYER_NAME", $full_name) ;
			
			$tpl->setVariable("MATCH_TYPE", $match_type) ;
			$tpl->setVariable("MATCH_ID", $match_id) ;
			
			$tpl->parseCurrentBlock($block_name) ;
			
		}
	}
}


?>


