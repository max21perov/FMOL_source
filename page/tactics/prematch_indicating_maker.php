<?php

session_start();

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
$tpl->loadTemplatefile('prematch_indicating.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from SESSION
//----------------------------------------------------------------------------
$self_team_id = sql_quote($_SESSION['s_primary_team_id']);

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

// --------------------------------
// get the div_id of team
$div_id = getDivIdOfTeam($db, $self_team_id);

// produce next match info
// and get the opponent team_id
$opp_team_id = produceNextMatchInfo($db, $tpl, $div_id, $self_team_id);


// --------------------------------
// get prematch indicating value
$indicating_select_value_arr = array();
$indicating_other_value_arr = array();
getPreMatchIndicatingValue($db, $self_team_id, $indicating_select_value_arr, $indicating_other_value_arr);

// get prematch indicating select option
$indicating_select_option_arr = getIndicatingSelectOptionArr($db, $opp_team_id);

// product select of page
producePageSelect($tpl, $indicating_select_value_arr, $indicating_select_option_arr);

// init other component of page
initOtherComponentOfPage($tpl, $indicating_other_value_arr);



// RETURN_PAGE_URL
$tpl->setVariable("RETURN_PAGE_URL", "/fmol/page/tactics/prematch_indicating.php");

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// in order to show the template file in every case, add this sentense
$tpl->setVariable("SPACE", " ");

$tpl->show();




//----------------------------------------------------------------------------	
// common functions
//----------------------------------------------------------------------------	
/**
 * get the div_id of team
 *
 * @param [db]			db
 * @param [team_id]		team_id
 *
 * @return  no
 */		
function getDivIdOfTeam($db, $team_id)
{
	$div_id = $_SESSION['s_primary_div_id']; // default value
	
	// get div_name
	$query = sprintf(
			" SELECT div_id " . 
			" FROM team_in_div " . 
			" WHERE team_id='%s' " , 
			$team_id);

	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database Error!"; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		if ($rs->RecordCount() > 0) {
			
			// set the div_id
			$div_id = $rs->fields['div_id'];
			
		}
	}		
	
	return $div_id;
	
}


/**
 * produce next match info
 * and get the opponent team_id
 *
 * @param [db]			db
 * @param [team_id]		team_id
 *
 * @return  no
 */		
function produceNextMatchInfo($db, $tpl, $div_id, $team_id)
{
	$opp_team_id = "";
		
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
		
		$opp_team_id = $rs->fields['opp_team_id'];
	}		
	
	return $opp_team_id;
}


/**
 * get PreMatch Indicating value
 *
 * @param [tpl]		php 模板变量
 *
 * @return  no
 */		 
function getPreMatchIndicatingValue($db, $self_team_id, & $indicating_select_value_arr, & $indicating_other_value_arr)
{
	$query = sprintf(
			" SELECT is_indicating_in_use, opp_F_num, opp_D_num, is_opp_AMC, is_opp_DMC, " . 
			" opp_AD_mentality, is_opp_OST, is_opp_CA, opp_tempo, " . 
			" is_spec_opp_in_use, spec_opp_player_id, is_heavy_tackling, is_heavy_pressing " . 
			" FROM team_tactics " .
			" WHERE team_id='%s' " ,
			$self_team_id
			);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error.";
		exit(0);
	}
	else {
		if ($rs->RecordCount() > 0) {
			// not select value
			$indicating_other_value_arr['is_indicating_in_use'] = $rs->fields['is_indicating_in_use']; 
			$indicating_other_value_arr['is_spec_opp_in_use'] = $rs->fields['is_spec_opp_in_use']; 
			
			// select value
			$indicating_select_value_arr['opp_F_num'] = $rs->fields['opp_F_num']; 
			$indicating_select_value_arr['opp_D_num'] = $rs->fields['opp_D_num']; 
			$indicating_select_value_arr['is_opp_AMC'] = $rs->fields['is_opp_AMC'];
			$indicating_select_value_arr['is_opp_DMC'] = $rs->fields['is_opp_DMC'];
			$indicating_select_value_arr['opp_AD_mentality'] = $rs->fields['opp_AD_mentality']; 
			$indicating_select_value_arr['is_opp_OST'] = $rs->fields['is_opp_OST']; 
			$indicating_select_value_arr['is_opp_CA'] = $rs->fields['is_opp_CA']; 
			$indicating_select_value_arr['opp_tempo'] = $rs->fields['opp_tempo']; 
			$indicating_select_value_arr['spec_opp_player_id'] = $rs->fields['spec_opp_player_id']; 
			$indicating_select_value_arr['is_heavy_tackling'] = $rs->fields['is_heavy_tackling']; 
			$indicating_select_value_arr['is_heavy_pressing'] = $rs->fields['is_heavy_pressing']; 
			
		}
	}
}

/**
 * get PreMatch indicating  select option arr
 *
 * @param [tpl]		php 模板变量
 *
 * @return  no
 */		
function getIndicatingSelectOptionArr($db, $opp_team_id)
{
	$indicating_select_option_arr = array();
	
	$spec_opp_player_id_select = getSpecOppPlayerIdSelect($db, $opp_team_id);
	
	// [1-3]
	$opp_F_num_select = array (
							"1"=>"1", "2"=>"2", 
							"3"=>"3"
							);
	// [2-5]
	$opp_D_num_select = array (
							"2"=>"2", "3"=>"3", 
							"4"=>"4", "5"=>"5"
							);
	// [-1, 1]
	$mentality_select = array (
							"-1"=>"Defensive", "0"=>"Normal", 
							"1"=>"Attack"
							);
	// [0, 2]
	$tempo_select = array (
							"0"=>"Quick", "1"=>"Normal", "2"=>"Slow"
							);
	
	$ture_false_select = array (
							"0"=>"false", "1"=>"true"
							);							
	
	// command_select_value							
	$indicating_select_option_arr["opp_F_num"] 			= $opp_F_num_select;
	$indicating_select_option_arr["opp_D_num"] 			= $opp_D_num_select;
	
	$indicating_select_option_arr["is_opp_AMC"] 		= $ture_false_select;
	$indicating_select_option_arr["is_opp_DMC"] 		= $ture_false_select;
	$indicating_select_option_arr["is_opp_OST"] 		= $ture_false_select;
	$indicating_select_option_arr["is_opp_CA"] 			= $ture_false_select;
	$indicating_select_option_arr["is_heavy_tackling"] 	= $ture_false_select;
	$indicating_select_option_arr["is_heavy_pressing"] 	= $ture_false_select;
	
	$indicating_select_option_arr["opp_AD_mentality"] 	= $mentality_select;
	$indicating_select_option_arr["opp_tempo"] 			= $tempo_select;
	
	$indicating_select_option_arr["spec_opp_player_id"] = $spec_opp_player_id_select;
	
	return $indicating_select_option_arr;
	
}


/**
 * get the players of opponent team
 *
 * @param [db]		db
 *
 * @return  no
 */		
function getSpecOppPlayerIdSelect($db, $opp_team_id)
{
	$player_id_name_arr = array();

	$query = sprintf(
			" SELECT player_id, custom_given_name as given_name, custom_family_name as family_name " . 
			" FROM player " .
			" WHERE team_id='%s' " ,
			$opp_team_id
			);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error.";
		exit(0);
	}
	else {
		for (; !$rs->EOF; $rs->MoveNext()) {
			$full_name = "";
			if (empty($given_name)) {
				$full_name = $rs->fields['family_name'];
			}
			else {
				$full_name = substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name'];
			}
		

			$player_id_name_arr[$rs->fields['player_id']] = $full_name; 
			
		}
	}
	
	return $player_id_name_arr;
	
}



/**
 * product page select
 *
 * @param [tpl]		tpl
 *
 * @return  no
 */		 
function producePageSelect($tpl, $indicating_select_value_arr, $indicating_select_option_arr)
{
	foreach($indicating_select_value_arr as $key=>$value) {
		$block_name = $key . "_select";		
		
		$select_arr = $indicating_select_option_arr[$key]; 
		foreach($select_arr as $select_value => $select_text) {
			$tpl->setCurrentBlock($block_name) ;
			$tpl->setVariable("OPTION_VALUE", $select_value) ;
			$tpl->setVariable("OPTION_TEXT", $select_text) ;
			if ($value == $select_value) {
				$tpl->setVariable("OPTION_SELECTED", "selected") ;
			}
			$tpl->parseCurrentBlock($block_name) ;
		}
	}
	
}


/**
 * init other component of page
 *
 * @param [tpl]		tpl
 *
 * @return  no
 */		
function initOtherComponentOfPage($tpl, $indicating_other_value_arr)
{
	// is_indicating_in_use
	if ($indicating_other_value_arr["is_indicating_in_use"] == "0") {
		$tpl->setVariable("IS_INDICATING_IN_USE_CHECKED", "checked");
	}
		
	// is_indicating_in_use
	if ($indicating_other_value_arr["is_spec_opp_in_use"] == "0") {
		$tpl->setVariable("IS_SPEC_OPP_IN_USE_CHECKED", "checked");
	}
	
}



?>

