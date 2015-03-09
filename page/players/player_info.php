<?php 
// when the page go back, it will not appear time out
ob_start(); 
if(function_exists(session_cache_limiter)) { 
    session_cache_limiter("private, must-revalidate"); 
} 

session_start();

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
// check whether the user can access the page
require_once(DOCUMENT_ROOT . "/lib/access_control.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = $_SESSION['s_primary_team_id'];
$team_id_of_player = "";
//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$player_id = $_GET['player_id'];

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------


$query = sprintf(
			" SELECT p.team_id as team_id_of_player, p.player_or_gk, p.sign_contract, " . 
			" p.contract_negotiating, p.transfer_flag, p.season_remains, p.retire_after_contract, " .
			" ftb.id as free_transfer_buffer_id " . 
			" FROM player p " . 
			" LEFT JOIN free_transfer_buffer ftb ON p.player_id=ftb.player_id " . 
			" WHERE p.player_id='%s' " ,
			$player_id); 
			
$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database error.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit(0);
}
else {
    if ($rs->RecordCount() > 0) {
		$team_id_of_player 		= $rs->fields['team_id_of_player'];
		$player_or_gk 			= $rs->fields['player_or_gk'];
		$sign_contract 			= $rs->fields['sign_contract']; 
		$contract_negotiating 	= $rs->fields['contract_negotiating']; 
		$transfer_flag 			= $rs->fields['transfer_flag']; 
		$season_remains			= $rs->fields['season_remains']; 
		$retire_after_contract 	= $rs->fields['retire_after_contract']; 
		$free_transfer_buffer_id = $rs->fields['free_transfer_buffer_id']; 
    }
}

//----------------------------------------------------------------------------	
// decide the pate direct
//----------------------------------------------------------------------------
if ($team_id_of_player == "" || $team_id_of_player == "0") {
	// free player
	
	$query = sprintf(
			" SELECT 1 FROM free_market where player_id='%s' ",
			$player_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		$error_message = "Database error.";
		require (DOCUMENT_ROOT . "/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() == 0 ){
		// 该自由球员不在自由市场
		require_once("player_info_alone.php");
		exit(0);
	}
	else if ($rs->RecordCount() > 0 ){
		// 该球员是自由市场中的球员
		require_once("player_info_free_market.php");
		exit(0);
	}
}
else if($team_id_of_player == $s_primary_team_id) {
	$query  = "SELECT 1 FROM transfer_buffer where player_id='$player_id'";
	$rs = &$db->Execute($query);
	if (!$rs) {
		$error_message = "Database error.";
		require (DOCUMENT_ROOT . "/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() < 0 ){
		$error_message = "There is not this right record in the database.";
		require (DOCUMENT_ROOT . "/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() > 0 ){
		// 该球员已经挂牌了，所以现在的动作是 “撤销挂牌”
		require_once("player_info_self_cancel.php");
		exit(0);
	}
	
	$query  = "SELECT 1 FROM transfer_list where player_id='$player_id'";
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0 ) {
		// 该球员已经在转会市场中了，所以现在的动作是 “撤销挂牌”
		require_once("player_info_self_cancel.php");
		exit(0);
	}
	
	// 如果没有在转会状态，那么动作就是“挂牌”
	require_once("player_info_self_put.php");
	exit(0);
}
else { 
	// 看其他球队球员信息时候的页面
	require_once("player_info_other.php");
	exit(0);
}

?>
