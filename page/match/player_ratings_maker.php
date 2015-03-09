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
$tpl->loadTemplatefile("player_ratings.tpl.php", true, true); 

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
 * get the home team id
 */
$match_team_arr = getMatchTeamArr($db, $match_type, $match_id);
$home_team_id = $match_team_arr["home_team_id"];
$away_team_id = $match_team_arr["away_team_id"];

// set the self team name's class: to indicate the self team					
$tpl->setVariable("HOME_PRIMARY_TEAM_ID", $home_team_id) ;
$tpl->setVariable("HOME_TEAM_NAME", $match_team_arr['home_team_name']) ;
$tpl->setVariable("AWAY_PRIMARY_TEAM_ID", $away_team_id) ;
$tpl->setVariable("AWAY_TEAM_NAME", $match_team_arr['away_team_name']) ;
			

/**
 * get the team stats
 */
$home_player_ratings_arr = getPlayerRatings($db, $match_type, $match_id, $home_team_id);
$away_player_ratings_arr = getPlayerRatings($db, $match_type, $match_id, $away_team_id);



/**
 * display the player ratings
 */
$block_name = "home_player_ratings";
displayPlayerRatings($db, $tpl, $block_name, $home_player_ratings_arr);

$block_name = "away_player_ratings";
displayPlayerRatings($db, $tpl, $block_name, $away_player_ratings_arr);


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
 * get player ratings
 *
 * @param [db]						db	
 * @param [match_id]				match_id	
 * @param [team_id]					team_id
 *
 * @return  $player_ratings_arr
 */	
function getPlayerRatings($db, $match_type, $match_id, $team_id)
{
	$player_ratings_arr = array();
	
	$query = sprintf(
				" select ps.player_id, ps.player_name, p.custom_given_name as given_name, p.custom_family_name as family_name, " .
				" p.cloth_number, ps.gols, ps.rating, ps.time_PLAY, ps.position_id, p.condition " .
				" from player_match_stat ps " .
				" LEFT JOIN player p ON ps.player_id=p.player_id " .
				" where ps.match_type='%s' and ps.match_id='%s' and ps.team_id='%s' " .
				" order by ps.position_id ASC ",
				$match_type, $match_id, $team_id);
				
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error.";  // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		for (; !$rs->EOF; $rs->MoveNext()) { 
			$player_ratings = array();
			$player_ratings["player_id"] = $rs->fields["player_id"];
			$player_ratings["position_id"] = $rs->fields["position_id"];
			
			
			$player_ratings["rating"] = intval( intval($rs->fields["rating"]) / 100);
			$player_ratings["gols"] = $rs->fields["gols"];
			$player_ratings["time_PLAY"] = intval($rs->fields["time_PLAY"]);
			
			if ($rs->fields["player_id"] == "") {  // is [gray player]
				$player_ratings["player_name"] = $rs->fields['player_name'];
				
				$player_ratings["cloth_number"] = "";
				$player_ratings["condition"] = "100%";
			}
			else {
				if ($rs->fields['given_name'] == "") {
					$player_ratings["player_name"] = $rs->fields['family_name'];
				}
				else {
					$player_ratings["player_name"] = substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name'];
				}
				
				$player_ratings["cloth_number"] = $rs->fields["cloth_number"];
				$player_ratings["condition"] = intval($rs->fields["condition"]) . "%";
			}
			
			$player_ratings_arr[count($player_ratings_arr)] = $player_ratings;				
			
			
		}
	}		
	
	return $player_ratings_arr;
}

/**
 * display the player ratings
 *
 * @param [db]						db	
 * @param [tpl]						tpl
 * @param [block_name]				block_name	
 * @param [player_ratings_arr]		player_ratings_arr
 *
 * @return  void
 */	
function displayPlayerRatings($db, $tpl, $block_name, $player_ratings_arr)
{	

	$len = count($player_ratings_arr);           
	
	for ($i=0; $i<$len; ++$i) {  
		$player_ratings = $player_ratings_arr[$i];
		
		$tpl->setCurrentBlock($block_name) ;
		
		$tpl->setVariable("PRIMARY_PLAYER_ID", $player_ratings["player_id"]) ;
		$tpl->setVariable("PLAYER_NAME", $player_ratings["player_name"]) ;
		$tpl->setVariable("CLOTH_NUMBER", $player_ratings["cloth_number"]) ;
		// only show the stat data of the player whose time_PLAY > 0
		if (intval($player_ratings["time_PLAY"]) > 0) {
			$tpl->setVariable("CONDITION", $player_ratings["condition"]) ;
			$tpl->setVariable("RATING", $player_ratings["rating"]) ;
			if ($player_ratings["gols"] != "0")
				$tpl->setVariable("GOALS", $player_ratings["gols"]) ;
		}
		
		if ($match_log["player_id"] == "") {   // is [gray player]
			$tpl->setVariable("PLAYER_NAME_LINE_DISPLAY", "none") ;	
			$tpl->setVariable("PLAYER_NAME_ONLY_DISPLAY", "block") ;			
		}
		else {
			$tpl->setVariable("PLAYER_NAME_LINE_DISPLAY", "block") ;
			$tpl->setVariable("PLAYER_NAME_ONLY_DISPLAY", "none") ;			
		}
		
		$tpl->parseCurrentBlock($block_name) ;
	}
	
			
}


?>


