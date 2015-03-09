<?php



/**
 * get Match Team Arr
 *
 * @param [db]						db	
 * @param [match_id]				match_id	
 *
 * @return  $match_team_arr
 *   $match_team_arr["home_team_id"]
 *   $match_team_arr["home_team_name"]
 *   $match_team_arr["away_team_id"]
 *   $match_team_arr["away_team_name"]
 */	
function getMatchTeamArr($db, $match_type, $match_id)
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
	
			
			$match_team_arr["home_team_id"] = $rs->fields['home_id'];
			$match_team_arr["home_team_name"] = $rs->fields['home_team'];
			$match_team_arr["away_team_id"] = $rs->fields['away_id'];
			$match_team_arr["away_team_name"] = $rs->fields['away_team'];
			
		}
	}		
	
	return $match_team_arr;
	
}

/**
 * get team match stats
 *
 * @param [db]						db	
 * @param [match_id]				match_id	
 * @param [team_id]					team_id
 *
 * @return  $team_stats_arr
 */	
function getTeamStats($db, $match_type, $match_id, $team_id)
{
	$team_stats_arr = array();
	
	$query = sprintf(
				" select ps.player_id, ps.player_name, p.custom_given_name as given_name, p.custom_family_name as family_name, " .
				" p.cloth_number, p.player_or_gk, ps.position_id, p.condition, ps.rating, " .
				" ps.passesatm, ps.passesmad, ps.tacklesatm, ps.tacklesmad, ps.headersatm, " . 
				" ps.headersmad, ps.ints, ps.runs, ps.offs, ps.fous, " .
				" ps.flds, ps.shotsatm, ps.shotson, ps.gols, ps.asts, " .
				" ps.unsaves, ps.saves, ps.holds, ps.looses, ps.tipouts, " .
				" ps.rsomad, ps.rsofal, ps.intmad, ps.intfal, ps.misses, " .
				" ps.keypasses, ps.keytackles, ps.keyheaders, ps.keymisses, ps.time_PLAY " .
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
			$player_stats = array();
			$player_stats["player_id"] = $rs->fields["player_id"];
			$player_stats["player_or_gk"] = $rs->fields["player_or_gk"];
			$player_stats["position_id"] = $rs->fields["position_id"];
			
			$player_stats["passesatm"] = $rs->fields["passesatm"];
			$player_stats["passesmad"] = $rs->fields["passesmad"];
			$player_stats["tacklesatm"] = $rs->fields["tacklesatm"];
			$player_stats["tacklesmad"] = $rs->fields["tacklesmad"];
			$player_stats["headersatm"] = $rs->fields["headersatm"];
			$player_stats["headersmad"] = $rs->fields["headersmad"];
			$player_stats["ints"] = $rs->fields["ints"];
			$player_stats["runs"] = $rs->fields["runs"];
			$player_stats["offs"] = $rs->fields["offs"];
			$player_stats["fous"] = $rs->fields["fous"];
			$player_stats["flds"] = $rs->fields["flds"];
			$player_stats["shotsatm"] = $rs->fields["shotsatm"];
			$player_stats["shotson"] = $rs->fields["shotson"];
			$player_stats["gols"] = $rs->fields["gols"];
			$player_stats["asts"] = $rs->fields["asts"];
			
			$player_stats["unsaves"] = $rs->fields["unsaves"];
			$player_stats["saves"] = $rs->fields["saves"];
			$player_stats["holds"] = $rs->fields["holds"];
			$player_stats["looses"] = $rs->fields["looses"];
			$player_stats["tipouts"] = $rs->fields["tipouts"];
			$player_stats["rsomad"] = $rs->fields["rsomad"];
			$player_stats["rsofal"] = $rs->fields["rsofal"];
			$player_stats["intmad"] = $rs->fields["intmad"];
			$player_stats["intfal"] = $rs->fields["intfal"];
			$player_stats["misses"] = $rs->fields["misses"];
			
			$player_stats["keypasses"] = $rs->fields["keypasses"];
			$player_stats["keytackles"] = $rs->fields["keytackles"];
			$player_stats["keyheaders"] = $rs->fields["keyheaders"];
			$player_stats["keymisses"] = $rs->fields["keymisses"];
			$player_stats["rating"] = intval( intval($rs->fields["rating"] ) / 100);  
			$player_stats["time_PLAY"] = intval( $rs->fields["time_PLAY"] );  
			
			if ($rs->fields["player_id"] == "") {  // is [gray player]
				$player_stats["player_name"] = $rs->fields['player_name'];
				
				$player_stats["cloth_number"] = "";
				$player_stats["condition"] = "100%";
			}
			else {
				if ($rs->fields['given_name'] == "") {
					$player_stats["player_name"] = $rs->fields['family_name'];
				}
				else {
					$player_stats["player_name"] = substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name'];
				}
				
				$player_stats["cloth_number"] = $rs->fields["cloth_number"];  
				$player_stats["condition"] = intval($rs->fields["condition"]) . "%";
			}
			
			$team_stats_arr[count($team_stats_arr)] = $player_stats;				
			
			
		}
	}		
	
	return $team_stats_arr;
}


/**
 * display the match goals
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [team_stats_arr]		team_stats_arr	
 *
 * @return  void
 */	
function displayTeamStats($db, $tpl, $team_stats_arr)
{	

	$len = count($team_stats_arr);
	$block_name = "";
	for ($i=0; $i<$len; ++$i) {
		$team_stats = $team_stats_arr[$i];
		
		$player_or_gk = $team_stats["player_or_gk"];
		
		if ($player_or_gk == "1") $block_name = "gk_team_stats";
		else $block_name = "player_team_stats";
			
		$tpl->setCurrentBlock($block_name) ;
		
		$tpl->setVariable("CLOTH_NUMBER", $team_stats["cloth_number"]) ;
		$tpl->setVariable("PRIMARY_PLAYER_ID", $team_stats["player_id"]) ;
		$tpl->setVariable("PLAYER_NAME", $team_stats["player_name"]) ;
		// only show the stat data of the player whose time_PLAY > 0
		if (intval($team_stats["time_PLAY"]) > 0) {
			if ($player_or_gk == "1") {
				$tpl->setVariable("UNSAVES", $team_stats["unsaves"]) ;
				$tpl->setVariable("SAVES", $team_stats["saves"]) ;
				$tpl->setVariable("HOLDS", $team_stats["holds"]) ;
				$tpl->setVariable("LOOSES", $team_stats["looses"]) ;
				$tpl->setVariable("TIP_OUTS", $team_stats["tipouts"]) ;
				$tpl->setVariable("RSO_MADE", $team_stats["rsomad"]) ;
				$tpl->setVariable("RSO_FALSE", $team_stats["rsofal"]) ;
				$tpl->setVariable("INT_MADE", $team_stats["intmad"]) ;
				$tpl->setVariable("INT_FALSE", $team_stats["intfal"]) ;
				$tpl->setVariable("MISSES", $team_stats["misses"]) ;
			}
			else {
				$tpl->setVariable("PASSES_ATTEMPT", $team_stats["passesatm"]) ;
				$tpl->setVariable("PASSES_MADE", $team_stats["passesmad"]) ;
				$tpl->setVariable("KEY_PASSES", $team_stats["keypasses"]) ;
				$tpl->setVariable("TACKLES_ATTEMPT", $team_stats["tacklesatm"]) ;
				$tpl->setVariable("TACKLES_MADE", $team_stats["tacklesmad"]) ;
				$tpl->setVariable("KEY_TACKLES", $team_stats["keytackles"]) ;
				$tpl->setVariable("HEADERS_ATTEMPT", $team_stats["headersatm"]) ;
				$tpl->setVariable("HEADERS_MADE", $team_stats["headersmad"]) ;
				$tpl->setVariable("KEY_HEADERS", $team_stats["keyheaders"]) ;
				$tpl->setVariable("INTERCEPTION", $team_stats["ints"]) ;
				$tpl->setVariable("RUNS", $team_stats["runs"]) ;
				$tpl->setVariable("OFFSIDES", $team_stats["offs"]) ;
				$tpl->setVariable("FOULS", $team_stats["fous"]) ;
				$tpl->setVariable("FOULEDS", $team_stats["flds"]) ;
				$tpl->setVariable("SHOTS_ATTEMPT", $team_stats["shotsatm"]) ;
				$tpl->setVariable("SHOTS_ON", $team_stats["shotson"]) ;
			
			}		
			
			$tpl->setVariable("ASSISTS", $team_stats["asts"]) ;
			$tpl->setVariable("CONDITION", $team_stats["condition"]) ;
			$tpl->setVariable("RATING", $team_stats["rating"]) ;
			if ($team_stats["gols"] != "0")
				$tpl->setVariable("GOALS", $team_stats["gols"]) ;
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


