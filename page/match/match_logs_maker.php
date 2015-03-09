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
$tpl->loadTemplatefile("match_logs.tpl.php", true, true); 

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
 * get the match team arr
 *     home_team_id
 *     home_team_name
 *     away_team_id
 *     away_team_name
 */
$match_team_arr = getMatchTeamArr($db, $match_type, $match_id);

/**
 * get the team players
 */
$team_players_arr = getTeamPlayersArr($db, $match_team_arr);
			
			
$log_event_type = array("1" => "SHOT", 
						"2" => "GOAL", 
						"3" => "OWNGOAL", 
						"4" => "PENMAD", 
						"5" => "PENMIS", 
						"6" => "FKGOAL",
						"10" => "SUB", 
						"11" => "SENTOFF", 
						"12" => "INJOFF", 
						"13" => "YELLOW", 
						"14" => "YELLOW2", 
						"15" => "RED");
$log_event_type_td_color = 
				  array("1" => "SHOT", 
						"2" => "#996600; font-weight: bold;", 
						"3" => "OWNGOAL", 
						"4" => "PENMAD", 
						"5" => "PENMIS", 
						"6" => "FKGOAL",
						"10" => "green; font-weight: bold;", 
						"11" => "red; font-weight: bold;", 
						"12" => "red; font-weight: bold;", 
						"13" => "yellow; font-weight: bold;", 
						"14" => "yellow; font-weight: bold;", 
						"15" => "red; font-weight: bold;"); 
												
/**
 * get the team stats
 */
$match_logs_arr = getMatchLogs($db, $match_type, $match_id, $match_team_arr, $team_players_arr, $log_event_type, $log_event_type_td_color);


/**
 * display the match logs
 */
$block_name = "match_logs";
displayMatchLogs($db, $tpl, $block_name, $match_logs_arr);


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
 * get the team players
 *
 * @param [db]						db	
 * @param [match_team_arr]			match_team_arr	
 *
 * @return  $team_players_arr
 */	
function getTeamPlayersArr($db, $match_team_arr)
{
	$team_players_arr = array();

	$query = sprintf(
				" select team_id, player_id, custom_given_name as given_name, " . 
				" custom_family_name as family_name " .
				" from player " .
				" where team_id='%s' or team_id='%s' " .
				" order by team_id ASC ",
				$match_team_arr["home_team_id"], $match_team_arr["away_team_id"]);
				
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		 for (; !$rs->EOF; $rs->MoveNext()) { 
			
			$player_name = "";
			if ($rs->fields['given_name'] == "") {
				$player_name = $rs->fields['family_name'];
			}
			else {
				$player_name = substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name'];
			}
			
			
			$team_players_arr[$rs->fields['team_id']][$rs->fields['player_id']] = $player_name;
		}
	}		
	
	return $team_players_arr;
}

/**
 * get Match Logs Arr
 *
 * @param [db]						db	
 * @param [match_id]				match_id	
 * @param [match_team_arr]			match_team_arr	
 * @param [team_players_arr]		team_players_arr	
 * @param [log_event_type]			log_event_type	
 * @param [log_event_type_td_color]	log_event_type_td_color	
 *
 * @return  none
 */	
function getMatchLogs($db, $match_type, $match_id, $match_team_arr, $team_players_arr, $log_event_type, $log_event_type_td_color)
{
	$match_logs_arr = array();
	
	$home_team_id = $match_team_arr["home_team_id"];
	$away_team_id = $match_team_arr["away_team_id"];
	
	$query = sprintf(
				" select log_index, time, team_id, type_event, " . 
				" player1_id, player1_name, player2_id, player1_name " .
				" from match_log log " .
				" where match_type='%s' and match_id='%s' and (log.team_id='%s' or log.team_id='%s')  " .
				" order by time, log_index ASC ",
				$match_type, $match_id, $home_team_id, $away_team_id);

	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error."; //  $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		 for (; !$rs->EOF; $rs->MoveNext()) { 
			$match_logs = array();
			
			if (intval($rs->fields["type_event"]) >= 16) {
				// do not display
				continue;
			}
			
			$match_logs["time"] = $rs->fields['time'];
			$match_logs["team_id"] = $rs->fields['team_id'];
			$match_logs["type_event"] = $log_event_type[$rs->fields['type_event']];
			$match_logs["type_event_td_color"] = $log_event_type_td_color[$rs->fields['type_event']];
			
			//
			if ($rs->fields['player1_id'] != "0") {
				$match_logs["player1_id"] = $rs->fields['player1_id'];
				if ($rs->fields['player1_id'] == "-1") { // [gray playerS]								 
					$match_logs["player1_name"] = $rs->fields['player1_name'];
				}
				else {
					$match_logs["player1_name"] = $team_players_arr[$rs->fields['team_id']][$rs->fields['player1_id']];
				}
				
			}
			//
			if ($rs->fields['player2_id'] != "0") {
				$match_logs["player2_id"] = $rs->fields['player2_id'];
				if ($rs->fields['player2_id'] == "-1") { // [gray playerS]				  
					$match_logs["player2_name"] = $rs->fields['player2_name'];
				}
				else {  
					$match_logs["player2_name"] = $team_players_arr[$rs->fields['team_id']][$rs->fields['player2_id']];					
				}
			}
			// ---
			if ($rs->fields['team_id'] == $match_team_arr["home_team_id"]) {
				$match_logs["team_name"] = $match_team_arr["home_team_name"];
			}	
			else if ($rs->fields['team_id'] == $match_team_arr["away_team_id"])  {
				$match_logs["team_name"] = $match_team_arr["away_team_name"];
			}
			
			
			$match_logs_arr[count($match_logs_arr)] = $match_logs;
		}
	}		
	
	return $match_logs_arr;
	
}


/**
 * display the match logs
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [block_name]			block_name	
 * @param [match_logs_arr]		match_logs_arr
 *
 * @return  void
 */	
function displayMatchLogs($db, $tpl, $block_name, $match_logs_arr)
{	

	$len = count($match_logs_arr);
	for ($i=0; $i<$len; ++$i) {
		$match_log = $match_logs_arr[$i];
		
		
		$tpl->setCurrentBlock($block_name) ;
		
		$tpl->setVariable("LOG_TIME", $match_log["time"]) ;
		$tpl->setVariable("PRIMARY_TEAM_ID", $match_log["team_id"]) ;
		$tpl->setVariable("TEAM_NAME", $match_log["team_name"]) ;
		$tpl->setVariable("TYPE_EVENT", $match_log["type_event"]) ;
		$tpl->setVariable("TYPE_EVENT_TD_COLOR", $match_log["type_event_td_color"]);
		$tpl->setVariable("PRIMARY_PLAYER1_ID", $match_log["player1_id"]) ;
		$tpl->setVariable("PLAYER1_NAME", $match_log["player1_name"]) ;
		$tpl->setVariable("PRIMARY_PLAYER2_ID", $match_log["player2_id"]) ;
		$tpl->setVariable("PLAYER2_NAME", $match_log["player2_name"]) ;
		
		
		if ($match_log["player1_id"] == "-1") { 
			$tpl->setVariable("PLAYER1_NAME_LINE_DISPLAY", "none") ;	
			$tpl->setVariable("PLAYER1_NAME_ONLY_DISPLAY", "block") ;			
		}
		else {
			$tpl->setVariable("PLAYER1_NAME_LINE_DISPLAY", "block") ;
			$tpl->setVariable("PLAYER1_NAME_ONLY_DISPLAY", "none") ;			
		}
		
		if ($match_log["player2_id"] == "-1") { 
			$tpl->setVariable("PLAYER2_NAME_LINE_DISPLAY", "none") ;	
			$tpl->setVariable("PLAYER2_NAME_DISPLAY", "block") ;			
		}
		else {
			$tpl->setVariable("PLAYER2_NAME_LINE_DISPLAY", "block") ;
			$tpl->setVariable("PLAYER2_NAME_ONLY_DISPLAY", "none") ;			
		}
		
		$tpl->parseCurrentBlock($block_name) ;
	}
			
}



?>


