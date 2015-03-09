<?php

/**
 * get the div_id of team 
 *
 * @param [db]			db
 * @param [team_id]		team_id
 *
 * @return  $div_id
 */
function getDivIdOfTeam($db, $team_id)
{
	$div_id = "";
	$query = sprintf(
				" SELECT div_id " . 
				" FROM team_in_div " . 
				" WHERE team_id='%s' " , 
				$team_id);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print  "Database Error. " ;   // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		if ($rs->RecordCount() > 0) {
			$div_id = $rs->fields['div_id'];
		}
	}	
	
	return $div_id;
}


/**
 * get the query str of league fixtures 
 *
 * @param [team_id]		team_id
 * @param [div_id]		div_id
 * @param [finish_filter]		
 *   0: not play; 
 *   1: played
 *  10: all
 *
 * @return  $query
 */		 
function getLeagueFixturesQueryStr($team_id, $div_id, $finish_filter)
{
	$query = " ( ";
	$query .= " SELECT s.id AS schedule_id, s.played, s.home_score as self_score, s.away_score as opp_score, ";
	$query .= " t.name as opp_name, t.team_id AS opp_team_id, 'H' as home_or_away, ";
	$query .= " date_format( s.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( s.time, '%H:%i' ) AS match_time, ";
	$query .= " '0' AS match_type, s.time ";  // 0 - schedule
	$query .= " FROM schedule s, team t ";
	$query .= sprintf(" WHERE s.div_id='%s' ", $div_id);
	$query .= sprintf(" AND s.home_id='%s' ", $team_id);
	$query .= $finish_filter=="10" ? "" : sprintf(" and s.played='%s' ", $finish_filter);
	$query .= " and s.away_id=t.team_id ";
	$query .= " ) ";
	$query .= " union ";
	$query .= " ( ";
	$query .= " SELECT s.id AS schedule_id, s.played, s.away_score as self_score, s.home_score as opp_score, ";
	$query .= " t.name as opp_name, t.team_id AS opp_team_id, 'A' as home_or_away, ";
	$query .= " date_format( s.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( s.time, '%H:%i' ) AS match_time, ";
	$query .= " '0' AS match_type, s.time ";  // 0 - schedule
	$query .= " FROM schedule s, team t ";
	$query .= sprintf(" WHERE s.div_id='%s' ", $div_id);
	$query .= sprintf(" AND s.away_id='%s' ", $team_id);
	$query .= $finish_filter=="10" ? "" : sprintf(" and s.played='%s' ", $finish_filter);
	$query .= " and s.home_id=t.team_id ";
	$query .= " ) ";
	$query .= " order by played DESC, time ASC ";
	
	return $query;

}

/**
 * get the query str of friendly fixtures 
 *
 * @param [team_id]		team_id
 * @param [div_id]		div_id
 * @param [finish_filter]		
 *   0: not play; 
 *   1: played
 *  10: all
 *
 * @return  $query
 */		 
function getFriendlyFixturesQueryStr($team_id, $div_id, $finish_filter)
{
	$query = " ( ";
	$query .= " SELECT f.id AS schedule_id, f.played, f.home_score as self_score, f.away_score as opp_score, ";
	$query .= " t.name as opp_name, t.team_id AS opp_team_id, 'H' as home_or_away, ";
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, ";
	$query .= " '1' AS match_type, f.time ";  // 1 - friendly
	$query .= " FROM friendly f, team t ";
	$query .= " WHERE f.status='2' ";  // 2-fix
	$query .= sprintf(" AND f.home_id='%s' ", $team_id);
	$query .= $finish_filter=="10" ? "" : sprintf(" AND f.played='%s' ", $finish_filter);
	$query .= " AND f.away_id=t.team_id ";
	$query .= " ) ";
	$query .= " union ";
	$query .= " ( ";
	$query .= " SELECT f.id AS schedule_id, f.played, f.away_score as self_score, f.home_score as opp_score, ";
	$query .= " t.name as opp_name, t.team_id AS opp_team_id, 'A' as home_or_away, ";
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, ";
	$query .= " '1' AS match_type, f.time ";  // 1 - friendly
	$query .= " FROM friendly f, team t ";
	$query .= " WHERE f.status='2' ";  // 2-fix
	$query .= sprintf(" AND f.away_id='%s' ", $team_id);
	$query .= $finish_filter=="10" ? "" : sprintf(" AND f.played='%s' ", $finish_filter);
	$query .= " AND f.home_id=t.team_id ";
	$query .= " ) ";
	$query .= " order by played DESC, time ASC ";
	
	return $query;

}

/**
 * get the query str of all fixtures (including league, friendly)
 *
 * @param [team_id]		team_id
 * @param [div_id]		div_id
 * @param [finish_filter]		
 *   0: not play; 
 *   1: played
 *  10: all
 *
 * @return  $query
 */		 
function getAllFixturesQueryStr($team_id, $div_id, $finish_filter)
{
	// league
	$query = " ( ";
	$query .= " SELECT s.id AS schedule_id, s.played, s.home_score as self_score, s.away_score as opp_score, ";
	$query .= " t.name as opp_name, t.team_id AS opp_team_id, 'H' as home_or_away, ";
	$query .= " date_format( s.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( s.time, '%H:%i' ) AS match_time, ";
	$query .= " '0' AS match_type, s.time ";  // 0 - schedule
	$query .= " FROM schedule s, team t ";
	$query .= sprintf(" WHERE s.div_id='%s' ", $div_id);
	$query .= sprintf(" AND s.home_id='%s' ", $team_id);
	$query .= $finish_filter=="10" ? "" : sprintf(" and s.played='%s' ", $finish_filter);
	$query .= " and s.away_id=t.team_id ";
	$query .= " ) ";
	$query .= " union ";
	$query .= " ( ";
	$query .= " SELECT s.id AS schedule_id, s.played, s.away_score as self_score, s.home_score as opp_score, ";
	$query .= " t.name as opp_name, t.team_id AS opp_team_id, 'A' as home_or_away, ";
	$query .= " date_format( s.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( s.time, '%H:%i' ) AS match_time, ";
	$query .= " '0' AS match_type, s.time ";  // 0 - schedule
	$query .= " FROM schedule s, team t ";
	$query .= sprintf(" WHERE s.div_id='%s' ", $div_id);
	$query .= sprintf(" AND s.away_id='%s' ", $team_id);
	$query .= $finish_filter=="10" ? "" : sprintf(" and s.played='%s' ", $finish_filter);
	$query .= " and s.home_id=t.team_id ";
	$query .= " ) ";
	
	$query .= " union ";
	                                      
	$query .= " ( ";  
	$query .= " SELECT f.id AS schedule_id, f.played, f.home_score as self_score, f.away_score as opp_score, ";
	$query .= " t.name as opp_name, t.team_id AS opp_team_id, 'H' as home_or_away, ";
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, ";
	$query .= " '1' AS match_type, f.time ";  // 1 - friendly
	$query .= " FROM friendly f, team t ";
	$query .= " WHERE f.status='2' ";  // 2-fix
	$query .= sprintf(" AND f.home_id='%s' ", $team_id);
	$query .= $finish_filter=="10" ? "" : sprintf(" AND f.played='%s' ", $finish_filter);
	$query .= " AND f.away_id=t.team_id ";
	$query .= " ) ";
	$query .= " union ";
	$query .= " ( ";
	$query .= " SELECT f.id AS schedule_id, f.played, f.away_score as self_score, f.home_score as opp_score, ";
	$query .= " t.name as opp_name, t.team_id AS opp_team_id, 'A' as home_or_away, ";
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, ";
	$query .= " '1' AS match_type, f.time ";  // 1 - friendly
	$query .= " FROM friendly f, team t ";
	$query .= " WHERE f.status='2' ";  // 2-fix
	$query .= sprintf(" AND f.away_id='%s' ", $team_id);
	$query .= $finish_filter=="10" ? "" : sprintf(" AND f.played='%s' ", $finish_filter);
	$query .= " AND f.home_id=t.team_id ";
	$query .= " ) ";
	$query .= " order by played DESC, time ASC ";

	return $query;
}

?>


