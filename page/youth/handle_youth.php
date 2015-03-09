<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");



if($_SERVER["REQUEST_METHOD"] != "POST")    //check the page is request by method "POST"
{	
	// go back to the page "youth.php"
	goToPageInTime(0, "/fmol/page/youth/youth.php");
}


$myaction = sql_quote($_GET["myaction"]); 

if ("elevateNewPlayer" == $myaction) {
	performElevateNewPlayer($db, DOCUMENT_ROOT);
	
	exit(0);
	
}
else if ("increaseYouthTrainingInvest" == $myaction) {
	performIncreaseYouthTrainingInvest($db, DOCUMENT_ROOT);
	
	exit(0);
	
}
else {

	goToPageInTime(0, "/fmol/page/youth/youth.php");

}



//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
/**
 * elevate new player
 *
 * @param [db]				db
 * @param [document_root]	document_root
 *
 * @return return true or false
 */	
function performElevateNewPlayer($db, $document_root)
{
	$player_id = sql_quote($_POST["player_id"]);
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	
	
	// 
	$returnValue = canElevateNewPlayer($db, $team_id, $player_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// get correct player ability from new_player_trial_training
	$correct_ability = array();
	$returnValue = GetPlayerCorrectAbility($db, $player_id, $correct_ability);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// get contract of player
	$contract_arr = array();
	$returnValue = GetPlayerContract($db, $player_id, $contract_arr);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	
	//
	$db->BeginTrans();
	
	
	// elevate new player
	$returnValue = elevateNewPlayer($db, $team_id, $player_id, $correct_ability, $contract_arr);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	//
	$returnValue = increaseSeasonWeekElevateNum($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	//	
	$returnValue = deleteThePlayerFromNew_player_pool($db, $player_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	//
	$returnValue = deleteAllPlayerFromNew_player_trial_training($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	//
	$returnValue = reduceActionPoint($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
		
	// commit
	$db->CommitTrans();
	
	
	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
	
	
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, "/fmol/page/youth/youth.php");	
	
}


/**
 * increate youth training invest
 *
 * @param [db]				db
 * @param [document_root]	document_root
 *
 * @return return true or false
 */	
function performIncreaseYouthTrainingInvest($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$increase_num = sql_quote($_POST['increase_num']);
	$youth_training_level = sql_quote($_POST['youth_training_level']);
	$youth_training_cur_invest = sql_quote($_POST['youth_training_cur_invest']);
	
	// 
	$returnValue = canIncreaseYouthTrainingInvest($db, $team_id, $increase_num);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// calculate new level after increase invest
	$new_level = $youth_training_level;
	$new_cur_invest = $youth_training_cur_invest; 
	$returnValue = calculateNewYouthTrainingLevel($youth_training_level, $youth_training_cur_invest, 
													$increase_num, $new_level, $new_cur_invest);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	
	
	//
	$db->BeginTrans();
	
	
	// update youth training invest result
	$returnValue = updateYouthTrainingInvestResult($db, $team_id, $increase_num, $new_level, $new_cur_invest);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	
	//
	$returnValue = reduceActionPoint($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
		
	// commit
	$db->CommitTrans();
	
	
	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
	
	
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, "/fmol/page/youth/youth.php");	
	
}


//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------
/**
 * check whether the team can elevate this new player
 *
 * @param [db]						database
 * @param [team_id]					team_id
 *
 * @return return 0, other error message
 */  
function canElevateNewPlayer($db, $team_id, $player_id)
{
	// activity_point_num
	$query  = sprintf(
				"SELECT c.activity_point_num ".
				" FROM club c, team t " .
				" where t.team_id='%s' AND c.club_id=t.club_id" ,
				$team_id);
				
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() <= 0 ){
		return "There is not this right record in the database.";
	}
	/*
	$activity_point_num = intval($rs->fields['activity_point_num']);
	if ($activity_point_num <= 5) {
		return "The activity_point_num is only $activity_point_num, it must be more than 5.";
	}
	*/
	
	
	// elevate_num
	$query  = sprintf(
				"SELECT week_elevate_num, season_elevate_num ".
				" FROM team  " .
				" where team_id='%s' " ,
				$team_id);
				
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() <= 0 ){
		return "There is not this right record in the database.";
	}

	$week_elevate_num = intval($rs->fields['week_elevate_num']);
	$season_elevate_num = intval($rs->fields['season_elevate_num']);
	if ($week_elevate_num != 0) {
		return "This week, you have already elevate a new player.";
	}
	if ($season_elevate_num > 3) {
		return "You can only elevate 3 new players at most each season.";
	}

	
	return "0";
	
}


/**
 * AP = AP - 5
 *
 * @param [db]						database
 * @param [club_id]					club_id
 *
 * @return return 0, error msg
 */	
function reduceActionPoint($db, $team_id)
{
	$query = sprintf(
				" UPDATE club c, team t SET " .
				" c.activity_point_num=c.activity_point_num-5 " .
				" WHERE c.club_id=t.club_id AND t.team_id='%s' " ,
				$team_id);
				
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
 * elevate New Player
 *
 * @param [db]						database
 * @param [team_id]					team_id
 * @param [player_id]				player_id
 * @param [correct_ability]			correct_ability
 * @param [contract_arr]			contract_arr
 *
 * @return return 0, error msg
 */	
function elevateNewPlayer($db, $team_id, $player_id, $correct_ability, $contract_arr)
{
	
	$query = sprintf(
				" UPDATE player SET " . 
				" pace=pace+%d, power=power+%d, stamina=stamina+%d, " . 
				" finishing=finishing+%d, passing=passing+%d, crossing=crossing+%d, " .
				" ball_control=ball_control+%d, tackling=tackling+%d, heading=heading+%d, " . 
				" play_making=play_making+%d, off_awareness=off_awareness+%d, def_awareness=def_awareness+%d, " .
				" agility=agility+%d, reflex=reflex+%d, " . 
				" handing=handing+%d, rushing_out=rushing_out+%d, positioning=positioning+%d, " .
				" aerial_ability=aerial_ability+%d, judgment=judgment+%d, " . 
				" team_id='%s', season_remains='%s', player_value='%s', salary='%s', sign_contract='1' " . 
				" WHERE player_id='%s' " ,
				$correct_ability["pace"], $correct_ability["power"], $correct_ability["stamina"],
				$correct_ability["finishing"], $correct_ability["passing"], $correct_ability["crossing"],
				$correct_ability["ball_control"], $correct_ability["tackling"], $correct_ability["heading"],
				$correct_ability["play_making"], $correct_ability["off_awareness"], $correct_ability["def_awareness"],
				$correct_ability["agility"], $correct_ability["reflex"], 
				$correct_ability["handing"], $correct_ability["rushing_out"], $correct_ability["positioning"],
				$correct_ability["aerial_ability"], $correct_ability["judgment"],
				$team_id, $contract_arr["season_remains"], $contract_arr["player_value"], $contract_arr["salary"],
				$player_id);
				
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
 * increase the season_elevate_num and week_elevate_num of team
 *
 * @param [db]						database
 * @param [team_id]					team_id
 *
 * @return return 0, error msg
 */	
function increaseSeasonWeekElevateNum($db, $team_id)
{
	$query = sprintf(
				" UPDATE team SET " . 
				" week_elevate_num=week_elevate_num+1, " . 
				" season_elevate_num=season_elevate_num+1 " . 
				" WHERE team_id='%s' " ,
				$team_id);
				
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
 * delete the Player From New_player_pool
 *
 * @param [db]						database
 * @param [player_id]				player_id
 *
 * @return return 0, error msg
 */	
function deleteThePlayerFromNew_player_pool($db, $player_id)
{
	$query = sprintf(
				" DELETE FROM new_player_pool " .
				" WHERE player_id='%s' " ,
				$player_id);
				
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
 * delete all Player From New_player_trial_training
 *
 * @param [db]						database
 * @param [team_id]					team_id
 *
 * @return return 0, error msg
 */	
function deleteAllPlayerFromNew_player_trial_training($db, $team_id)
{
	$query = sprintf(
				" DELETE FROM new_player_trial_training " .
				" WHERE team_id='%s' " ,
				$team_id);
				
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
 * get selected player correct ability
 *
 * @param [db]			db
 * @param [player_id]		player_id
 *
 * @return correct_player_ability
 */
function GetPlayerCorrectAbility($db, $player_id, & $correct_ability)
{
	
	$correct_ability = array();
	
	$query = sprintf(
			" SELECT pace, power, stamina, " . 
			" finishing, passing, crossing, ball_control, tackling, heading, " . 
			" play_making, off_awareness, def_awareness, " .
			" agility, reflex, " . 
			" handing, rushing_out, positioning, aerial_ability, " . 
			" judgment " . 
			" FROM new_player_trial_training " .
			" WHERE player_id='%s' " ,
			$player_id
			);

	$rs = &$db->Execute($query);

	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	else {
		if ($rs->RecordCount() > 0) {
			
			$correct_ability = $rs->fields;	
							
		}		
	}
	
	
	return "0";	
}


/**
 * get player contract
 *
 * @param [db]			db
 * @param [player_id]	player_id
 *
 * @return contract_arr
 */
function GetPlayerContract($db, $player_id, & $contract_arr)
{
	$contract_arr = array();
	$contract_arr["season_remains"] = "3";  // default is: season begin		
	$contract_arr["player_value"] = 0;
	$contract_arr["salary"] = 0.1;   // default 0.1
	
	// season_remains
	$query = sprintf(
			" SELECT value as season_period " . 
			" FROM system_const " .
			" WHERE name='season_period' " 
			);

	$rs = &$db->Execute($query);

	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0) {			
		$season_period = $rs->fields["season_period"];		
		
		if (doubleval(season_period) == 0.5) {
			// season mid
			$contract_arr["season_remains"] = 2.5; 	
		}	
		else {
			// season begin
			$contract_arr["season_remains"] = 3; 	
		}							
	}
	
	
	// player_value, salary
	$query = sprintf(
			" SELECT player_value, age " . 
			" FROM player " .
			" WHERE player_id='%s' ",
			$player_id 
			);

	$rs = &$db->Execute($query);

	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0) {		
				
		$contract_arr["player_value"] = $rs->fields["player_value"];	
		
		$age = intval($rs->fields["age"]);
		if (intval($age) == 16) {
			// 100k  (0.1m)
			$contract_arr["salary"] = 0.1;
		}
		else {
			$contract_arr["salary"] = doubleval($contract_arr["player_value"]) * 0.2; 	
		}
													
	}
	
	return "0";	
	
}


/**
 * check whether the team can increate so much invest
 *
 * @param [db]						database
 * @param [team_id]					team_id
 *
 * @return return 0, other error message
 */  
function canIncreaseYouthTrainingInvest($db, $team_id, $increase_num)
{
	// season_invest
	$query  = sprintf(
				"SELECT c.youth_training_season_invest as season_invest ".
				" FROM club c, team t " .
				" where t.team_id='%s' AND c.club_id=t.club_id" ,
				$team_id);
				
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}

	$season_invest = intval($rs->fields['season_invest']);
	if (($season_invest+$increase_num) > 10) {
		return "The increase num you choosed is $increase_num m, and you have invest $season_invest m in this season, it cannot more than 10 m.";
	}
	

	// current_fund
	$query  = sprintf(
				"SELECT f.current_fund ".
				" FROM finance f, club c, team t " .
				" where t.team_id='%s' AND c.club_id=t.club_id AND f.club_id=c.club_id " ,
				$team_id);
	
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}

	$current_fund = intval($rs->fields['current_fund']) / 1000000;
	if ($increase_num > $current_fund) {
		return "Your current found is only $current_fund m, so you can not increase $increase_num m.";
	}
	
	return "0";
	
}


/**
 * calculate new level after increase invest
 *
 * @param [db]						database
 * @param [team_id]					team_id
 *
 * @return return 0, other error message
 */  
function calculateNewYouthTrainingLevel($youth_training_level, $youth_training_cur_invest, 
										$increase_num, & $new_level, & $new_cur_invest)
{
	$new_level = intval($youth_training_level);
	$next_level = $new_level + 1;
	
	$all_invest_num = intval($increase_num) + intval($youth_training_cur_invest);
	
	while ($all_invest_num >= $next_level) {
		$new_level += 1;
		
		$all_invest_num -= $next_level;
		
		$next_level = $new_level + 1;	
	}
	
	$new_cur_invest = $all_invest_num;
	
	
	return "0";
	
}




/**
 * update youth training invest result
 *
 * @param [db]						database
 * @param [team_id]					team_id
 *
 * @return return 0, error msg
 */	
function updateYouthTrainingInvestResult($db, $team_id, $increase_num, $new_level, $new_cur_invest)
{
	$query = sprintf(
				" UPDATE finance f, club c, team t SET " . 
				" c.youth_training_season_invest=youth_training_season_invest+%d, " . 
				" t.youth_training_level='%s', " . 
				" c.youth_training_cur_invest='%s', " . 
				" f.current_fund=f.current_fund-%d " . 
				" WHERE t.team_id='%s' and c.club_id=t.club_id and f.club_id=c.club_id " ,
				$increase_num, $new_level, $new_cur_invest, ($increase_num*1000000),
				$team_id);
				
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	
	return "0";
}



?>


