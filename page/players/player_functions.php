<?php


/**
 * get injure dict from DB
 *
 * @param [db]					db	
 * @param [player_or_gk]		player_or_gk
 *
 * @return  $PR_arr
 */	
function getInjureDict($db, $player_or_gk)
{
	$injure_dict_arr = array();

	$query = sprintf(
				" select injure_id, injure_type, injure_name, injure_grade " .
				" from injure_dict " .
				" where player_type='%s' ",
				$player_or_gk);
				
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		 for (; !$rs->EOF; $rs->MoveNext()) { 
			
			$injure_id = $rs->fields['injure_id'];
			$injure_dict_arr[$injure_id]['injure_type'] = $rs->fields['injure_type'];	
			$injure_dict_arr[$injure_id]['injure_name'] = $rs->fields['injure_name'];		
			$injure_dict_arr[$injure_id]['injure_grade'] = $rs->fields['injure_grade'];			
		}
	}		
	
	return $injure_dict_arr;
}


//----------------------------------------------------------------------------	
// opinion function
//----------------------------------------------------------------------------

/**
 * get player opinion
 *
 * @param [db]			db
 * @param [team_id]		team_id
 *
 * @return  no
 */			
function getPlayerOpinionArr($db, $self_player_id)
{
	$opinion_arr = array();
	
	$query = sprintf(
			" SELECT opinion_content " . 
			" FROM player_opinion " .
			" WHERE self_player_id='%s' " .
			" ORDER BY opinion_type_id, small_type "  ,
			$self_player_id
			);

	$rs = &$db->Execute($query);

	if (!$rs) {
		print "Database error.";
		exit(0);
	}
	else {
		for (; !$rs->EOF; $rs->MoveNext()) {
			
			
			$opinion_arr[count($opinion_arr)] = $rs->fields["opinion_content"];
			
		}
		
		
	}
	
	return $opinion_arr;
}


// display opinion of player
function displayPlayerOpinion($tpl, $opinion_arr)
{
	$len = count($opinion_arr);
	
	for ($i=0; $i<$len; ++$i) {  
		$tpl->setCurrentBlock("player_opinion") ;
		$tpl->setVariable("PLAYER_OPINION_STR", $opinion_arr[$i]) ;
		$tpl->parseCurrentBlock("player_opinion") ;
	}
}

?>




