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
	goToPageInTime(0, "/fmol/page/transfer/free_coach_pool.php");
}


$myaction = sql_quote($_GET["myaction"]); 

if ("signCoach" == $myaction) {
	performSignCoach($db, DOCUMENT_ROOT);
	exit(0);
}
else {

	goToPageInTime(0, "/fmol/page/transfer/free_coach_pool.php");

}



//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
/**
	 * sign coach
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performSignCoach($db, $document_root)
{
	$coach_id = sql_quote($_GET["coach_id"]);
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	
	
	// 
	$returnValue = canSignCoach($db, $team_id, $coach_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	
	//
	$db->BeginTrans();
	
	$returnValue = updateTheBids($db, $team_id, $coach_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue . "2";
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// sign coach
	$returnValue = insertIntoFree_coach_signing_detail($db, $team_id, $coach_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = reduceActionPoint($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue . "4";
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
		
	// commit
	$db->CommitTrans();
	
	
	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
	
	
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, "/fmol/page/transfer/free_coach_pool.php");	
	
}



//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------

	/**
	 * check whether the player can be put into transfer
	 *
	 * @param [db]						database
	 * @param [team_id]					team_id
	 *
	 * @return return 0, other error message
	 */	
function canSignCoach($db, $team_id, $coach_id)
{
	// 
	$query  = sprintf(
				"SELECT c.activity_point_num ".
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
	/*
	$activity_point_num = intval($rs->fields['activity_point_num']);
	if ($activity_point_num <= 5) {
		return "The activity_point_num is only $activity_point_num, it must be more than 5.";
	}
	*/
	
	
	//
	$query = sprintf(
				" SELECT 1 FROM free_coach_signing_detail WHERE coach_id='%s' and team_id='%s' " ,
				$coach_id, $team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0){ 		
		return "The same team can not sign the same coach for two times. You had already offer a contract to him.";
	}
	
	
	return "0";
}


	/**
	 * update the "bids" in the free_coach_pool
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [coach_id]			coach_id
	 *
	 * @return return 0, -1, -2
	 */	
function updateTheBids($db, $team_id, $coach_id) 
{
	
	// update the bids=bids+1
	$query = sprintf(
				" UPDATE free_coach_pool SET bids=bids+1 WHERE coach_id='%s' " ,
				$coach_id);
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
 * release coach from team
 *
 * @param [db]					database
 * @param [coach_id]			coach_id
 *
 * @return return 0, -1, -2
 */	
function insertIntoFree_coach_signing_detail($db, $team_id, $coach_id)
{    
	$query = sprintf(
				" INSERT INTO free_coach_signing_detail " .
				" (team_id, coach_id, time) " .
				" VALUES ('%s', '%s', now()) " ,
				$team_id, $coach_id, $time);

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



?>
