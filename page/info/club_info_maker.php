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
$tpl->loadTemplatefile("club_info.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']); 

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$div_id = "";

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * get the club info
 */
// get user_name and club_name
$team_name = "";

$query = sprintf(
			" SELECT u.name AS user_name, c.name AS club_name, t.name AS team_name, t.team_id, t.team_id as p_team_id " . 
			" FROM club c, team t " . 
			" LEFT JOIN user_info u ON u.user_id=c.user_id " . 
			" WHERE t.team_id='%s' AND t.club_id=c.club_id " ,
			$team_id);

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database Error!"; // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    if ($rs->RecordCount() > 0) {

		// set the variable of the template
        $tpl->setVariable("TEAM_ID", $rs->fields['team_id']);
        $tpl->setVariable("USER_NAME", $rs->fields['user_name']);
        $tpl->setVariable("CLUB_NAME", $rs->fields['club_name']);
		
		$team_name = $rs->fields['club_name'];
    }
}		

// get div_name
$query = sprintf(
			" SELECT d.div_id AS primary_div_id, d.name AS div_name " . 
			" FROM division d, team_in_div tid " . 
			" WHERE tid.team_id='%s' AND tid.div_id=d.div_id " , 
			$team_id);

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database Error!"; // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    if ($rs->RecordCount() > 0) {
	    if ($s_primary_team_id == $team_id) {
			$_SESSION['s_primary_div_id'] = $rs->fields['primary_div_id']; 
		 	$s_primary_div_id = $rs->fields['primary_div_id'];  // change the "primary_div_id" this time
		 }
		// set the variable of the template
        $tpl->setVariable("DIV_NAME", $rs->fields['div_name']);
		// set the div_id
		$div_id = $rs->fields['primary_div_id'];
    }
}		


/**
 * (1)get the last match and the next match
 * (2)get the form (the lastest 6 match result)
 */
// the last match 
$query = sprintf(
			" ( " . 
			" SELECT s.id AS schedule_id, t.name AS opp_name, t.team_id AS opp_team_id, 'H' AS home_or_away, " .  
			" s.home_score AS self_score, s.away_score AS opp_score, s.time as match_time " . 
			" FROM schedule s, team t " . 
			" WHERE s.div_id = '%s' " . 
			" AND s.home_id = '%s' " . 
			" AND s.played = '1' " . 
			" AND s.away_id = t.team_id " . 
			" ) " . 
			" UNION " . 
			" ( " .  
			" SELECT s.id AS schedule_id, t.name AS opp_name, t.team_id AS opp_team_id, 'A' AS home_or_away, " .  
			" s.away_score AS self_score, s.home_score AS opp_score, s.time as match_time " . 
			" FROM schedule s, team t " . 
			" WHERE s.div_id = '%s' " . 
			" AND s.away_id = '%s' " . 
			" AND s.played = '1' " . 
			" AND s.home_id = t.team_id " . 
			" ) " . 
			" ORDER BY match_time DESC " .  
			" LIMIT 6 " ,
			$div_id, $team_id, $div_id, $team_id); 

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database Error!"; // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
     
	if ($rs->RecordCount() <= 0) {  
		// last match
		// if not last match
		$tpl->setVariable('LAST_MATCH_COL_SPAN', "5") ;
	}
	else {
		$index = 1;
		$form = ''; 
		for (; !$rs->EOF; $index+=1, $rs->MoveNext()) {
			
			if ($rs->RecordCount() > 0 && $index == 1) {  
				// last match
				$tpl->setCurrentBlock('last_match') ;
				$tpl->setVariable("SCHEDULE_ID", $rs->fields['schedule_id']) ;
				$tpl->setVariable('SELF_SCORE', $rs->fields['self_score']) ;
				$tpl->setVariable('OPPONENT_SCORE', $rs->fields['opp_score']) ;
				$tpl->setVariable('OPPONENT_PRIMARY_TEAM_ID', $rs->fields['opp_team_id']) ;
				$tpl->setVariable('OPPONENT_NAME', $rs->fields['opp_name']) ;
				$tpl->setVariable('HOME_OR_AWAY', $rs->fields['home_or_away']) ;
				$tpl->parseCurrentBlock('last_match') ;
			}
			
			
			$self_score = 0;
			$opp_score = 0;
			$self_score = $rs->fields['self_score']; 
			$opp_score = $rs->fields['opp_score']; 
			$result = '';
			if ($self_score < $opp_score) {
				$result = ' L '; // match result: lost
			}
			else if ($self_score == $opp_score) {
				$result = ' D '; // match result: drawn
			}
			else {
				$result = ' W '; // match result: won
			}
			$form .= $result;
			
		}
	}
	$tpl->setVariable('FORM', strrev($form)) ;   // strrev: µßµ¹×Ö·û´®
}		

// the next match
$query = sprintf(
			" ( " . 
			" SELECT t.name AS opp_name, t.team_id AS opp_team_id, 'H' AS home_or_away, '' AS self_score, " .  
			" '' AS opp_score, s.time as match_time " . 
			" FROM schedule s, team t " . 
			" WHERE s.div_id = '%s' " . 
			" AND s.home_id = '%s' " . 
			" AND s.played = '0' " . 
			" AND s.away_id = t.team_id " . 
			" ) " . 
			" UNION " .  
			" ( " . 
			" SELECT t.name AS opp_name, t.team_id AS opp_team_id, 'A' AS home_or_away, '' AS self_score, " .  
			" '' AS opp_score, s.time as match_time " . 
			" FROM schedule s, team t " . 
			" WHERE s.div_id = '%s' " . 
			" AND s.away_id = '%s' " . 
			" AND s.played = '0' " . 
			" AND s.home_id = t.team_id " . 
			" ) " . 
			" ORDER BY match_time ASC " .  
			" LIMIT 1 " ,
			$div_id, $team_id, $div_id, $team_id); 

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database Error!"; // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    if ($rs->RecordCount() > 0) {

		$tpl->setCurrentBlock('next_match') ;
		$tpl->setVariable('SELF_SCORE', $rs->fields['self_score']) ;
		$tpl->setVariable('OPPONENT_SCORE', $rs->fields['opp_score']) ;
		$tpl->setVariable('OPPONENT_PRIMARY_TEAM_ID', $rs->fields['opp_team_id']) ;
		$tpl->setVariable('OPPONENT_NAME', $rs->fields['opp_name']) ;
		$tpl->setVariable('HOME_OR_AWAY', $rs->fields['home_or_away']) ;
		$tpl->parseCurrentBlock('next_match') ;
    }
	else {
		$tpl->setVariable('NEXT_MATCH_COL_SPAN', "5") ;
	}
}		


/**
 * get the rank and old_rank 
 */
$query = sprintf(
			" select rank, old_rank " . 
			" from rank " . 
			" where team_id='%s' " ,
			$team_id);

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database Error!"; // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    if ($rs->RecordCount() > 0) {

		$tpl->setVariable('TEAM_POS', $rs->fields['rank']) ;
        $rank = $rs->fields['rank'];
		$old_rank = $rs->fields['old_rank'];
		$rank_img_location = '';
		if ($rank < $old_rank) {
		    $rank_img_location = 'rank_up.gif';
		}
		else if($rank == $old_rank) {
		    $rank_img_location = 'rank_no_change.gif';
		}
		else {
		    $rank_img_location = 'rank_down.gif';
		}
		
		$tpl->setVariable('RANK_IMG_LOCATION', $rank_img_location) ;
    }
}	

/**
 * get the rank and old_rank 
 */
$query = sprintf(
			" SELECT p.games_played, p.games_won, p.games_drawn, " .  
			" p.games_lost, p.goals_for, p.goals_against, " .  
			" p.goals_difference, p.points " . 
			" FROM points p, team_in_div t " . 
			" WHERE t.team_id = '%s' " . 
			" AND t.points_id = p.id " ,
			$team_id); 

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database Error!"; // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    if ($rs->RecordCount() > 0) {

		$tpl->setCurrentBlock("points") ;
		$tpl->setVariable("GAMES_PLAYED", $rs->fields['games_played']) ;
		$tpl->setVariable("GAMES_WON", $rs->fields['games_won']) ;
		$tpl->setVariable("GAMES_DRAWN", $rs->fields['games_drawn']) ;
		$tpl->setVariable("GAMES_LOST", $rs->fields['games_lost']) ;
		$tpl->setVariable("GOALS_FOR", $rs->fields['goals_for']) ;
		$tpl->setVariable("GOALS_AGAINST", $rs->fields['goals_against']) ;
		$tpl->setVariable("GOALS_DIFFERENCE", $rs->fields['goals_difference']) ;
		$tpl->setVariable("POINTS", $rs->fields['points']) ;
		$tpl->parseCurrentBlock("points") ;
    }
}	

/**
 * send the team_id, team_name to cookie
 */
$cookie_value = $HTTP_COOKIE_VARS["team_player_cookie"];
$is_exist = false;
if ($cookie_value != null && $cookie_value != "") {
	$big_arr = explode("|", $cookie_value);
	$big_len = count($big_arr);
	for ($i=0; $i<$big_len; ++$i) {
		$item_value = $big_arr[$i];
		$little_arr = explode(":", $item_value);
		
		if (count($little_arr)==3 && $little_arr[0]=="0" && $little_arr[1]==$team_id) {
			$is_exist = true;
			break;
		}
	}
	
	if (!$is_exist) {
		$temp_value = "0" . ":" . $team_id . ":" . $team_name;
		$cookie_value .= "|" . $temp_value;
	}
}
else {
	$temp_value = "0" . ":" . $team_id . ":" . $team_name;
	$cookie_value = $temp_value;
}
setcookie("team_player_cookie", $cookie_value, time()+3600*24, "/fmol");

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();


?>

