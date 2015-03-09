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
	// go back to the page "transfer_market.php"
	goToPageInTime(0, "/fmol/page/training/training.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("saveTraining" == $myaction) {
	performSaveTraining($db, DOCUMENT_ROOT);
}
else {
	goToPageInTime(0, "/fmol/page/training/training.php");
}

//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
	/**
	 * 保存训练相关内容，包括训练项目、教练、球员之间的关系
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performSaveTraining($db, $document_root)
{
	$team_id = $_SESSION['s_primary_team_id'];
	
	$team_training_pk = 0;
	$team_training_id_contents = array();
	$team_training_id_coaches = array();
	$personal_training_pks = array();
	$personal_training_id_contents = array();
	$personal_training_id_coaches = array();
	$personal_training_id_players = array();
	
	// 第一个循环遍历  
	foreach($_POST as $varname=>$value) { 
		// ----------- id ----------
		// 从 $_POST 得到 personal_training 的主键 id
    	// team training
    	if ($varname == "pk_of_team_training") {      
    		$pk_id = $value;
    		
    		$team_training_pk = $pk_id;
    	}
    	// personal training
    	if (substr($varname, 0, 11) == "pk_of_item_") {
    		$personal_training_id = substr($varname, 11, strlen($varname));
    		$pk_id = $value;
    		
    		$personal_training_pks[$personal_training_id] = $pk_id;
    	}    	
    }
    
    // 第一个循环遍历  
    foreach($_POST as $varname=>$value) { 	
    	// ----------- items ----------
		if ($varname == "team_content_select") {  	
    		$training_content_id = $value;
    		$team_training_pk_id = $team_training_pk;
    		$team_training_id_contents[$team_training_pk_id] = $training_content_id;
    	}
		// 从 $_POST 得到 training_content_id 和 personal_training_id 的对应
    	if (substr($varname, 0, 15) == "content_select_") {
    		$personal_training_id = substr($varname, 15, strlen($varname));
    		$training_content_id = $value;
    		$personal_training_pk_id = $personal_training_pks[$personal_training_id];
    		$personal_training_id_contents[$personal_training_pk_id] = $training_content_id;
    	}
    	if (substr($varname, 0, 18) == "gk_content_select_") {
    		$personal_training_id = substr($varname, 18, strlen($varname));
    		$training_content_id = $value;
    		$personal_training_pk_id = $personal_training_pks[$personal_training_id];
    		$personal_training_id_contents[$personal_training_pk_id] = $training_content_id;
    	}
    	
    	// ----------- coaches ----------
    	if ($varname == "coach_select_first_team") {    		
    		$coach_id = $value;
    		$team_training_pk_id = $team_training_pk;
    		$team_training_id_coaches[$team_training_pk_id]["first_coach"] = $coach_id;
    	}
    	if ($varname == "coach_select_second_team") {    		
    		$coach_id = $value;
    		$team_training_pk_id = $team_training_pk;
    		$team_training_id_coaches[$team_training_pk_id]["second_coach"] = $coach_id;
    	}
    	// 从 $_POST 得到 coach_id 和 personal_training_id 的对应
    	if (substr($varname, 0, 19) == "coach_select_first_" && $varname != "coach_select_first_team") {
    		$personal_training_id = substr($varname, 19, strlen($varname));
    		$coach_id = $value;
    		$personal_training_pk_id = $personal_training_pks[$personal_training_id];
    		$personal_training_id_coaches[$personal_training_pk_id]["first_coach"] = $coach_id;
    	}
    	if (substr($varname, 0, 20) == "coach_select_second_" && $varname != "coach_select_second_team") {
    		$personal_training_id = substr($varname, 20, strlen($varname));
    		$coach_id = $value;
    		$personal_training_pk_id = $personal_training_pks[$personal_training_id];
    		$personal_training_id_coaches[$personal_training_pk_id]["second_coach"] = $coach_id;
    	}
    	
    	// ----------- players ----------
    	// 从 $_POST 得到 player_id 和 personal_training_id 的对应
    	if (substr($varname, 0, 16) == "players_of_item_") {  
    		$personal_training_id = substr($varname, 16, strlen($varname));
    		$player_ids = $value;   
    		$personal_training_pk_id = $personal_training_pks[$personal_training_id];  
    		$personal_training_id_players[$personal_training_pk_id] = $player_ids; 
    	}
    	
    } 
    
	    
    $db->BeginTrans();
    
    // ========= team training ========
    // ----------- items ----------
    // set the training_content_id to be NULL
    $returnValue = clearContentOfTheTeamTraining($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// update the personal_training
    foreach ($team_training_id_contents as $key=>$value) {
    	$team_training_id = $key;
    	$training_content_id = $value;
    	$returnValue = updateTheTeamTraining_content($db, $team_training_id, $training_content_id);
		if ($returnValue != "0") {
			$db->RollbackTrans(); 
			$error_message = $returnValue;
			require ("$document_root/page/system/error.php");
			
			goBackInTime(3500, -1); 
		}	
    }
    
    
    // ----------- coaches ----------
    // set the coach_id to be NULL
    $returnValue = clearCoachOfTheTeamTraining($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}	
    
	// update the team_training
    foreach ($team_training_id_coaches as $key=>$value) {
    	$team_training_id = $key;
    	$coach_arr = $value;
    	$first_coach_id = $coach_arr["first_coach"];
    	$second_coach_id = $coach_arr["second_coach"];
    	
    	if ($value == -1) continue;
    	
    	$returnValue = updateTheTeamTraining_coach($db, $team_training_id, $first_coach_id, $second_coach_id);
		if ($returnValue != "0") {
			$db->RollbackTrans(); 
			$error_message = $returnValue;
			require ("$document_root/page/system/error.php");
			
			goBackInTime(3500, -1); 
		}	
    }
    
    // ======== personal training ========
    // ----------- items ----------
    // set the training_content_id to be NULL
    $returnValue = clearContentOfThePersonalTraining($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
    // update the personal_training
    foreach ($personal_training_id_contents as $key=>$value) {
    	$personal_training_id = $key;
    	$training_content_id = $value;
    	$returnValue = updateThePersonalTraining_content($db, $personal_training_id, $training_content_id);
		if ($returnValue != "0") {
			$db->RollbackTrans(); 
			$error_message = $returnValue;
			require ("$document_root/page/system/error.php");
			
			goBackInTime(3500, -1); 
		}	
    }
    
    // ----------- coaches ----------
    // set the coach_id to be NULL
    $returnValue = clearCoachOfThePersonalTraining($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}	
    
	// update the personal_training
    foreach ($personal_training_id_coaches as $key=>$value) {  
    	$personal_training_id = $key;
    	$coach_arr = $value;
    	$first_coach_id = $coach_arr["first_coach"];
    	$second_coach_id = $coach_arr["second_coach"];
    	
    	if ($value == -1) continue;
    	
    	$returnValue = updateThePersonalTraining_coach($db, $personal_training_id, $first_coach_id, $second_coach_id);
		if ($returnValue != "0") {
			$db->RollbackTrans(); 
			$error_message = $returnValue;
			require ("$document_root/page/system/error.php");
			
			goBackInTime(3500, -1); 
		}	
    }
    
    // ----------- players ----------
    // set the player_id in the personal_training
    $returnValue = clearPlayerTraining($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}	
	
	// update the player_training
    foreach ($personal_training_id_players as $key=>$value) {
    	$personal_training_id = $key;
    	$player_ids = $value;
    	
    	if ($player_ids == null || $player_ids == "") continue; 
    	
    	$player_id_array = split(',', $player_ids);  
    	for ($i=0; $i<count($player_id_array); ++$i) {
    		$player_id = $player_id_array[$i];
	    	$returnValue = insertIntoPlayerTraining($db, $player_id, $personal_training_id);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}	
    	}
    }
    
    // commit
	$db->CommitTrans();
	
	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
	
	// 最后，返回 training.php 页面
	goToPageInTime(2, "/fmol/page/training/training.php");	
	
}


//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------

	/**
	 * set the coach_id to be NULL
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 *
	 * @return return 0, -1, -2
	 */	
function clearCoachOfTheTeamTraining($db, $team_id)
{
	$query = sprintf(
				" UPDATE team_training " .
				" SET first_coach_id=NULL, second_coach_id=NULL " .
				" WHERE team_id='%s' " ,
				$team_id); 
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * set the coach_id to be NULL
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 *
	 * @return return 0, -1, -2
	 */	
function clearCoachOfThePersonalTraining($db, $team_id)
{
	$query = sprintf(
				" UPDATE personal_training " .
				" SET first_coach_id=NULL, second_coach_id=NULL " .
				" WHERE team_id='%s' " ,
				$team_id); 
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * set the training_content_id to be NULL
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 *
	 * @return return 0, -1, -2
	 */	
function clearContentOfTheTeamTraining($db, $team_id)
{
	$query = sprintf(
				" UPDATE team_training " .
				" SET training_content_id=NULL " .
				" WHERE team_id='%s' " ,
				$team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * set the training_content_id to be NULL
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 *
	 * @return return 0, -1, -2
	 */	
function clearContentOfThePersonalTraining($db, $team_id)
{
	$query = sprintf(
				" UPDATE personal_training " .
				" SET training_content_id=NULL " .
				" WHERE team_id='%s' " ,
				$team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * delete from the player_training where the player 
	 * is in the team whose team_id = $team_id
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 *
	 * @return return 0, -1, -2
	 */	
function clearPlayerTraining($db, $team_id)
{
	$query = sprintf(
				" DELETE FROM player_training " . 
				" WHERE player_id in " . 
				" ( " .   
				" SELECT player_id " . 
				" FROM player " . 
				" WHERE team_id='%s' " . 
				" ) " ,
				$team_id);
	
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * update the team_training (coach)
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [team_training_id]	team_training 表的主键
	 * @param [first_coach_id]		first_coach_id
	 * @param [second_coach_id]		second_coach_id
	 *
	 * @return return 0, -1, -2
	 */	
function updateTheTeamTraining_coach($db, $team_training_id, $first_coach_id, $second_coach_id)
{ 
	$query = sprintf(
				" UPDATE team_training " . 
				" SET first_coach_id=%s, second_coach_id=%s " . 
				" WHERE id='%s' " ,
				$first_coach_id, $second_coach_id, $team_training_id);  
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * update the personal_training (coach)
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [personal_training_id]	personal_training 表的主键
	 * @param [first_coach_id]		first_coach_id
	 * @param [second_coach_id]		second_coach_id
	 *
	 * @return return 0, -1, -2
	 */	
function updateThePersonalTraining_coach($db, $personal_training_id, $first_coach_id, $second_coach_id)
{ 
	$query = sprintf(
				" UPDATE personal_training " . 
				" SET first_coach_id=%s, second_coach_id=%s " . 
				" WHERE id='%s' " ,
				$first_coach_id, $second_coach_id, $personal_training_id); 
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * update the team_training (content)
	 *
	 * @param [db]						database
	 * @param [team_id]					team_id
	 * @param [team_training_id]		team_training 表的主键
	 * @param [training_content_id]		training_content_id
	 *
	 * @return return 0, -1, -2
	 */	
function updateTheTeamTraining_content($db, $team_training_id, $training_content_id)
{ 
	$query = sprintf(
				" UPDATE team_training " . 
				" SET training_content_id='%s' " . 
				" WHERE id='%s' " ,
				$training_content_id, $team_training_id);  
	$rs = &$db->Execute($query);  
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * update the personal_training (content)
	 *
	 * @param [db]						database
	 * @param [team_id]					team_id
	 * @param [personal_training_id]		personal_training 表的主键
	 * @param [training_content_id]		training_content_id
	 *
	 * @return return 0, -1, -2
	 */	
function updateThePersonalTraining_content($db, $personal_training_id, $training_content_id)
{ 
	$query = sprintf(
				" UPDATE personal_training " . 
				" SET training_content_id='%s' " . 
				" WHERE id='%s' " ,
				$training_content_id, $personal_training_id);  
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * insert into player_training (content)
	 *
	 * @param [db]						database
	 * @param [team_id]					team_id
	 * @param [player_id]				player_id
	 * @param [personal_training_id]		personal_training 表的主键
	 *
	 * @return return 0, -1, -2
	 */	
function insertIntoPlayerTraining($db, $player_id, $personal_training_id)
{ 
	$query = sprintf(
				" INSERT INTO player_training " . 
				" (player_id, personal_training_id) " . 
				" VALUES ('%s', '%s') " ,
				$player_id, $personal_training_id);

	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


?>


