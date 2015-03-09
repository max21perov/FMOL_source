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
// ʹ�õ���(perform)����
//----------------------------------------------------------------------------

	/**
	 * ��������Ա�Ĺ���
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
	
	// �жϸ��û��Ƿ��ܳ���
	$returnValue = canCancelFromTransfer($db, $player_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// �� transfer_list ��ɾ������Ա�ļ�¼��������ʵ���˳�������
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
// ʹ�õ��ĺ���
//----------------------------------------------------------------------------

	/**
	 * �����Ƿ��ܶԸ���Ա��������
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
		// ����Ա�պù��ƣ���Ӳ��ܶ�����������
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
		// ��һ�μ��㷢���Ժ���Ա�ſ��Ա�ĸ�ӳ�������
		return "The player must stay in transfer list for 1 JISUAN before he can be cancel from transfer.";
	}
	if ($bids != 0) {
		// �Ѿ�����ҶԸ���Ա�����ˣ�����ĸ�Ӳ��ܶ���Ա��������
		return "Others have given a price to this player, so you can not cancel him from transfer.";
	}
	
	return "0";
}

	/**
	 * �� transfer_list ��ɾ������Ա�ļ�¼��������ʵ���˳�������
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

