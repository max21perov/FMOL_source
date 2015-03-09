<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

$myaction = sql_quote($_GET["myaction"]);
if ("cancelFromTransfer" == $myaction) {
	performCancelFromTransfer($db, DOCUMENT_ROOT);
}
else {
	goToPageInTime(0, "/fmol/page/transfer/transfer_market.php");
}

//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------

	/**
	 * 撤销对球员的挂牌
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performCancelFromTransfer($db, $document_root)
{
	$player_id = sql_quote($_GET["player_id"]);
	$team_id = sql_quote($_SESSION["s_primary_team_id"]);
	
	// 判断该用户是否能出价
	$returnValue = canCancelFromTransfer($db, $player_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// 从 transfer_list 中删除该球员的记录，这样就实现了撤销挂牌
	$returnValue = removeFromTransfer_list($db, $player_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$error_message = "cancel from transfer Success.";
	require ("$document_root/page/system/error.php"); 
	
	// go back to the page "transfer_market.php"
	goToPageInTime(2, "/fmol/page/transfer/transfer_market.php");
}

//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------

	/**
	 * 检验是否能对该球员撤销挂牌
	 *
	 * @param [db]						database
	 * @param [tplayer_id				player_id
	 *
	 * @return return 0, other error message
	 */	
function canCancelFromTransfer($db, $player_id)
{
	$query  = sprintf("SELECT 1 FROM transfer_buffer where player_id='%s' ", $player_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0 ){
		// 该球员刚好挂牌，球队不能对他撤销挂牌
		return "The player has been put to transfer buffer, you can not cancel at present.";  
	}
	
	$query = sprintf("SELECT update_times, bids FROM transfer_list where player_id='%s' ", $player_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() <= 0 ) {
		return "There is not this right record in the database1.";
	}
	
	$update_times = intval($rs->fields['update_times']);
	$bids = intval($rs->fields['bids']);
	if ($update_times < 1) {
		// 第一次计算发生以后，球员才可以被母队撤销挂牌
		return "The player must stay in transfer list for 1 JISUAN before he can be cancel from transfer.";
	}
	if ($bids != 0) {
		// 已经有买家对该球员出价了，所以母队不能对球员撤销挂牌
		return "Others have given a price to this player, so you can not cancel him from transfer.";
	}
	
	return "0";
}

	/**
	 * 从 transfer_list 中删除该球员的记录，这样就实现了撤销挂牌
	 *
	 * @param [db]						database
	 * @param [tplayer_id				player_id
	 *
	 * @return return 0, other error message
	 */	
function removeFromTransfer_list($db, $player_id)
{
	$query = sprintf(" DELETE FROM transfer_list WHERE player_id='%s' ", $player_id);
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

