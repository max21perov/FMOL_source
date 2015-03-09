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
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

// ===========================================================================

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$player_id = sql_quote($_GET["player_id"]);

/**
 * check whether the player is already in the transfer_list
 */
$query = sprintf(
			" SELECT 1 " . 
			" FROM transfer_list " . 
			" WHERE player_id='%s' " ,
			$player_id);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database error.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit (0);
}
else if ($rs->RecordCount() > 0) {
	// 该球员已经在转会市场中了，现在买家的动作是“竞价”
	require_once("give_price.php");
	exit (0);
}	
else {
	// 该球员还没有在转会市场中，现在买家的动作可以是“问价”或者“出价”
	require_once("ask_price.php");
	exit (0);
}

?>
