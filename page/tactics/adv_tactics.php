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

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = $_SESSION['s_primary_team_id'];

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$team_id = $_GET["team_id"];

//----------------------------------------------------------------------------	
// decide the pate direct
//----------------------------------------------------------------------------
if ($team_id == "") {
	require_once("adv_tactics_self.php");
}
else if ($team_id == $s_primary_team_id) {
	require_once("adv_tactics_self.php");
}
else {
	require_once("adv_tactics_other.php");
}


?>