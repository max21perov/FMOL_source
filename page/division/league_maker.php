<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");


// update the div_id 
// when a season finishes and the team's div_id may be changed
require_once(DOCUMENT_ROOT . "/page/system/update_div_id.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("league.tpl.php", true, true); 


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
/**
 * get the div id by team_id
 */
$div_id = "";

$query = sprintf(
			" SELECT div_id " .
			" FROM team_in_div " .
			" WHERE team_id='%s' " ,
			$team_id);
$rs = &$db->Execute($query);

if (!$rs) {
    print "Database error.";  // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    if ($rs->RecordCount() > 0) {
        $div_id = $rs->fields['div_id'];
    }
}	

/**
 * show the schedule of current round 
 */
$query = sprintf(
			" select sch.id AS schedule_id, sch.round, rank1.rank as home_rank, team1.name as home_team, " . 
			" sch.home_id, sch.home_score, sch.away_score, " .
			" team2.name as away_team, sch.away_id, rank2.rank as away_rank " .
			" from schedule sch, team team1, team team2, rank rank1, rank rank2, division " .
			" where division.div_id='%s' and sch.div_id=division.div_id " .
			" and sch.round=division.cur_round " .
			" and sch.home_id=team1.team_id and sch.away_id=team2.team_id " .
			" and sch.home_id=rank1.team_id and sch.away_id=rank2.team_id " .
			" order by home_team, away_team " ,
			$div_id);  
$rs = &$db->Execute($query);

if (!$rs) {
    print "Database error.";  // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    $tpl->setVariable("EMPTY_VALUE", ' ') ; // not to display {ROUND} when the ROUND is empty
    if(!$rs->EOF) {
	    $cur_round = $rs->fields['round'];
		$tpl->setVariable("ROUND", $rs->fields['round']) ;
	}
	$index = 1;
    while (!$rs->EOF) {

		$tpl->setCurrentBlock("cur_round") ;
		if ($index % 2 != 0 )
			$tpl->setVariable("CUR_ROUND_TR_CLASS", 'gSGRowEven') ;
		else 
			$tpl->setVariable("CUR_ROUND_TR_CLASS", 'gSGRowOdd') ;
		$index++;	
		
		// set the self team name's class: to indicate the self team
		if ($rs->fields['home_id'] == $s_primary_team_id)
			$tpl->setVariable("HOME_TEAM_CLASS", 'SelfTeamText') ;
		else 
			$tpl->setVariable("HOME_TEAM_CLASS", 'OtherTeamText') ;	
		if ($rs->fields['away_id'] == $s_primary_team_id)
			$tpl->setVariable("AWAY_TEAM_CLASS", 'SelfTeamText') ;
		else 
			$tpl->setVariable("AWAY_TEAM_CLASS", 'OtherTeamText') ;	
			
		$tpl->setVariable("HOME_POS", $rs->fields['home_rank']) ;
		$tpl->setVariable("MATCH_ID", $rs->fields['schedule_id']) ;
		$tpl->setVariable("HOME_PRIMARY_TEAM_ID", $rs->fields['home_id']) ;
		$tpl->setVariable("HOME_TEAM", $rs->fields['home_team']) ;
		$tpl->setVariable("HOME_SCORE", $rs->fields['home_score']) ;
		$tpl->setVariable("AWAY_SCORE", $rs->fields['away_score']) ;
		$tpl->setVariable("AWAY_PRIMARY_TEAM_ID", $rs->fields['away_id']) ;
		$tpl->setVariable("AWAY_TEAM", $rs->fields['away_team']) ;
		$tpl->setVariable("AWAY_POS", $rs->fields['away_rank']) ;
		$tpl->parseCurrentBlock("cur_round") ;
		
		$rs->MoveNext(); 
    }
}		

/**
 * show the points table 
 */
$query = sprintf(
		" select r.rank, r.old_rank, tid.team_id, " .
		" team.name as team_name, p.games_played, p.games_won, " .
		" p.games_drawn, p.games_lost, p.goals_for, " .
		" p.goals_against, p.goals_difference, p.points " .
		" from points p, team_in_div tid, team, rank r " .
		" where tid.div_id='%s' and tid.points_id=p.id " .
		" and tid.team_id=team.team_id and r.team_id=tid.team_id " .
		" ORDER BY rank " ,
		$div_id);
		
$rs = &$db->Execute($query);

if (!$rs) {
    print "Database error.";  // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    $pos = 1;
    while (!$rs->EOF) {

		$tpl->setCurrentBlock("points") ;
		if($pos == 1) {
		    $tpl->setVariable("POINTS_TR_CLASS", 'gSGRowFirstOne') ;
		}
		else if($pos >= 6) {
		    $tpl->setVariable("POINTS_TR_CLASS", 'gSGRowLastThree') ;
		}
		else if($pos %2 != 0) {
		    $tpl->setVariable("POINTS_TR_CLASS", 'gSGRowEven') ;
		}
		else {
		    $tpl->setVariable("POINTS_TR_CLASS", 'gSGRowOdd') ;
		}
		$tpl->setVariable("POS", $rs->fields['rank']) ;
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
		
		// set the self team name's class: to indicate the self team
		if ($rs->fields['team_id'] == $s_primary_team_id)
			$tpl->setVariable("TEAM_CLASS", 'SelfTeamText') ;
		else 
			$tpl->setVariable("TEAM_CLASS", 'OtherTeamText') ;
			
		$tpl->setVariable("PRIMARY_TEAM_ID", $rs->fields['team_id']) ;
		$tpl->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
		$tpl->setVariable("GAMES_PLAYED", $rs->fields['games_played']) ;
		$tpl->setVariable("GAMES_WON", $rs->fields['games_won']) ;
		$tpl->setVariable("GAMES_DRAWN", $rs->fields['games_drawn']) ;
		$tpl->setVariable("GAMES_LOST", $rs->fields['games_lost']) ;
		$tpl->setVariable("GOALS_FOR", $rs->fields['goals_for']) ;
		$tpl->setVariable("GOALS_AGAINST", $rs->fields['goals_against']) ;
		$tpl->setVariable("GOALS_DIFFERENCE", $rs->fields['goals_difference']) ;
		$tpl->setVariable("POINTS", $rs->fields['points']) ;
		$tpl->parseCurrentBlock("points") ;
		
		$pos += 1;
		$rs->MoveNext(); 
    }
}		


/**
 * show the schedule of next round 
 */
$query = sprintf(
		" select sch.round, rank1.rank as home_rank, team1.name as home_team, " .
		" sch.home_id, sch.home_score, sch.away_score, " .
		" team2.name as away_team, sch.away_id, rank2.rank as away_rank " .
		" from schedule sch, team team1, team team2, rank rank1, rank rank2, division " .
		" where division.div_id='%s' and sch.div_id=division.div_id " .
		" and sch.round=(division.cur_round+1) " .
		" and sch.home_id=team1.team_id and sch.away_id=team2.team_id " .
		" and sch.home_id=rank1.team_id and sch.away_id=rank2.team_id " .
		" order by home_team, away_team " ,
		$div_id );

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database error.";  // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    $index = 1;
    while (!$rs->EOF) {

		$tpl->setCurrentBlock("next_round") ;
		if ($index % 2 != 0 )
			$tpl->setVariable("NEXT_ROUND_TR_CLASS", 'gSGRowEven') ;
		else 
			$tpl->setVariable("NEXT_ROUND_TR_CLASS", 'gSGRowOdd') ;
		$index++;	
		
		// set the self team name's class: to indicate the self team
		if ($rs->fields['home_id'] == $s_primary_team_id)
			$tpl->setVariable("HOME_TEAM_CLASS", 'SelfTeamText') ;
		else 
			$tpl->setVariable("HOME_TEAM_CLASS", 'OtherTeamText') ;	
		if ($rs->fields['away_id'] == $s_primary_team_id)
			$tpl->setVariable("AWAY_TEAM_CLASS", 'SelfTeamText') ;
		else 
			$tpl->setVariable("AWAY_TEAM_CLASS", 'OtherTeamText') ;	
			
		$tpl->setVariable("HOME_POS", $rs->fields['home_rank']) ;
		$tpl->setVariable("HOME_PRIMARY_TEAM_ID", $rs->fields['home_id']) ;
		$tpl->setVariable("HOME_TEAM", $rs->fields['home_team']) ;
		$tpl->setVariable("HOME_SCORE", $rs->fields['home_score']) ;
		$tpl->setVariable("AWAY_SCORE", $rs->fields['away_score']) ;
		$tpl->setVariable("AWAY_PRIMARY_TEAM_ID", $rs->fields['away_id']) ;
		$tpl->setVariable("AWAY_TEAM", $rs->fields['away_team']) ;
		$tpl->setVariable("AWAY_POS", $rs->fields['away_rank']) ;
		$tpl->parseCurrentBlock("next_round") ;
		
		$rs->MoveNext(); 
    }
}		


//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");
$tpl->show();


?>

