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
$team_id_of_coach = "";
//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$coach_id = $_GET['coach_id'];

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------


$query = sprintf(
			" SELECT c.team_id as team_id_of_coach, " . 
			" c.release_flag " .
			" FROM coach c " . 
			" WHERE c.coach_id='%s' " ,
			$coach_id); 
			
$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database error.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit(0);
}
else {
    if ($rs->RecordCount() > 0) {
		$team_id_of_coach = $rs->fields['team_id_of_coach'];
		$release_flag = $rs->fields['release_flag']; 
    }
}

//----------------------------------------------------------------------------	
// decide the pate direct
//----------------------------------------------------------------------------
if ($team_id_of_coach == "" || $team_id_of_coach == "0") {
	// free coach
	
	$query = sprintf(
			" SELECT 1 FROM free_coach_pool where coach_id='%s' ",
			$coach_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		$error_message = "Database error.";
		require (DOCUMENT_ROOT . "/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() == 0 ){
		// 该自由球员不在自由市场
		require_once("coach_info_alone.php");
		exit(0);
	}
	else if ($rs->RecordCount() > 0 ){
		// 该球员是自由市场中的球员
		require_once("coach_info_pool.php");
		exit(0);
	}
}
else if($team_id_of_coach == $s_primary_team_id) {
	
	
	// 如果没有在转会状态，那么动作就是“挂牌”
	require_once("coach_info_self_release.php");
	exit(0);
}
else { 
	// 看其他球队球员信息时候的页面
	require_once("coach_info_other.php");
	exit(0);
}

?>
