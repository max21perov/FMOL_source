<?php


/**
 * AP = AP - 5
 *
 * @param [db]						database
 * @param [club_id]					club_id
 *
 * @return return 0, error msg
 */	
function reduceActionPoint($db, $club_id, $reduce_num)
{
	$query = sprintf(
				" UPDATE club c, team t SET c.activity_point_num=c.activity_point_num-%s " .
				" WHERE c.club_id=t.club_id AND t.team_id='%s' " ,
				$reduce_num, $club_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	
	return "0";
}



/**
 * put the player into hot_list
 *
 * @param [db]					database
 * @param [team_id]				team_id
 * @param [player_id]			player_id
 *
 * @return return 0, -1, -2
 */	
function putToHotList($db, $team_id, $player_id)
{
	$query = sprintf(
				" SELECT 1 FROM hot_list WHERE player_id='%s' and team_id='%s' " ,
				$player_id, $team_id); 
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() == 0){ 
		// update the bids=bids+1
		$query = sprintf(
					" INSERT INTO hot_list ( team_id, player_id ) VALUES('%s', '%s') " ,
					$team_id, $player_id);  
		$rs = &$db->Execute($query);
		if (!$rs) {
			return "Database error.";
		}
		else if ($rs->RecordCount() < 0 ){
			return "There is not this right record in the database.";
		}
	}
	
	return "0";
}


?>



