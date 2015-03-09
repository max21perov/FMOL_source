<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once("transfer_functions.php");


if($_SERVER["REQUEST_METHOD"] != "POST")    //check the page is request by method "POST"
{	
	// go back to the page "club_info.php"
	goToPageInTime(0, "/fmol/page/transfer/free_market.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("inviteJoinFreeSigning" == $myaction) {
	performInviteJoinFreeSigning($db, DOCUMENT_ROOT);
}
else if ("inviteJoinFreeTransfer" == $myaction) {
	performInviteJoinFreeTransfer($db, DOCUMENT_ROOT);
}
else {
	$return_page_url = sql_quote($_POST["return_page_url"]);

	if ($return_page_url == "")
		goToPageInTime(0, "/fmol/page/transfer/free_market.php");
	else 
		goToPageInTime(0, $return_page_url);
}




//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
/**
	 * invite the player in Free Market to join
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performInviteJoinFreeSigning($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']); 
	$player_id = sql_quote($_POST["player_id"]);
	$contract_seasons = sql_quote($_POST["contract_seasons"]);
	$return_page_url = sql_quote($_POST["return_page_url"]);
	$time = date("Y-m-d H:i:s");
	
	// 判断该用户是否能邀请
	$returnValue = canInviteJoin($db, $team_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$db->BeginTrans();
	
	$returnValue = updateTheBidsInFreeMarket($db, $team_id, $player_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = insertIntoFree_signing_detail($db, $team_id, $player_id, $time, $contract_seasons);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$reduce_num = 5;
	$returnValue = reduceActionPoint($db, $team_id, $reduce_num);  
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = putToHotList($db, $team_id, $player_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue ;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// commit
	$db->CommitTrans();
	
	$error_message = "invite join Success.";
	require ("$document_root/page/system/error.php"); 
		
	
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, $return_page_url);	
	
}

/**
	 * invite the player in contract_being_full to join
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performInviteJoinFreeTransfer($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']); 
	$player_id = sql_quote($_POST["player_id"]);
	$contract_seasons = sql_quote($_POST["contract_seasons"]);
	$return_page_url = sql_quote($_POST["return_page_url"]);
	$time = date("Y-m-d H:i:s");
	
	// 判断该用户是否能邀请
	$returnValue = canInviteJoin($db, $team_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$db->BeginTrans();
	
	$returnValue = updateTheBidsInContract_being_full($db, $team_id, $player_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = insertIntoFree_transfer_detail($db, $team_id, $player_id, $time, $contract_seasons);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$reduce_num = 5;
	$returnValue = reduceActionPoint($db, $team_id, $reduce_num);  
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = putToHotList($db, $team_id, $player_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue ;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// commit
	$db->CommitTrans();
	
	$error_message = "invite join Success.";
	require ("$document_root/page/system/error.php"); 
		
	
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, $return_page_url);	
	
}

//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------
	/**
	 * save free transfer detail into DB
	 *
	 * @param [db]					database
	 * @param [player_id]			player_id
	 * @param [extend_seasons]		extend_seasons
	 *
	 * @return return 0, -1, -2
	 */	
function insertIntoFree_signing_detail($db, $team_id, $player_id, $time, $contract_seasons)
{    
	$query = sprintf (
				" INSERT INTO free_signing_detail " . 
				" (team_id, player_id, time, contract_seasons) " .
				" values('%s', '%s', '%s', '%s') " ,
				$team_id, $player_id, $time, $contract_seasons);

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
	 * check whether the player can be put into transfer
	 *
	 * @param [db]						database
	 * @param [club_id]					club_id
	 * @param [team_id]					team_id
	 * @param [price]					the given price
	 *
	 * @return return 0, other error message
	 */	
function canInviteJoin($db, $team_id)
{

	
	// 
	$query  = sprintf(
				" SELECT c.activity_point_num " .
				" FROM club c, team t " .
				" where t.team_id='%s' AND c.club_id=t.club_id " ,
				$team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	
	$activity_point_num = intval($rs->fields['activity_point_num']);
	if ($activity_point_num <= 5) {
		return "The activity_point_num is only $activity_point_num, it must be more than 5.";
	}
	
	return "0";
}


	/**
	 * update the "bids" in the transfer_list
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [player_id]			player_id
	 *
	 * @return return 0, -1, -2
	 */	
function updateTheBidsInFreeMarket($db, $team_id, $player_id) 
{
	$query = sprintf(
				" SELECT 1 FROM free_signing_detail WHERE player_id='%s' and team_id='%s' " ,
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
					" UPDATE free_market SET bids=bids+1 WHERE player_id='%s' " ,
					$player_id);
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


	/**
	 * update the "bids" in the contract_being_full
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [player_id]			player_id
	 *
	 * @return return 0, -1, -2
	 */	
function updateTheBidsInContract_being_full($db, $team_id, $player_id) 
{
	$query = sprintf(
				" SELECT 1 FROM free_transfer_detail WHERE player_id='%s' and team_id='%s' " ,
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
					" UPDATE contract_being_full SET bids=bids+1 WHERE player_id='%s' " ,
					$player_id);
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

	/**
	 * save free transfer detail into DB
	 *
	 * @param [db]					database
	 * @param [player_id]			player_id
	 * @param [extend_seasons]		extend_seasons
	 *
	 * @return return 0, -1, -2
	 */	
function insertIntoFree_transfer_detail($db, $team_id, $player_id, $time, $contract_seasons)
{    
	$query = sprintf (
				" INSERT INTO free_transfer_detail " . 
				" (team_id, player_id, time, contract_seasons) " .
				" values('%s', '%s', '%s', '%s') " ,
				$team_id, $player_id, $time, $contract_seasons);

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


