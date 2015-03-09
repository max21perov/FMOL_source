<?php
/**
 * go back to self team 
 */
 
session_start();

//$document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//----------------------------------------------------------------------------	
// set the session to back to self team
//----------------------------------------------------------------------------
/*
$_SESSION['s_primary_user_id'] = $_SESSION['s_self_primary_user_id'];
$_SESSION['s_primary_club_id'] = $_SESSION['s_self_primary_club_id'];
$_SESSION['s_primary_team_id'] = $_SESSION['s_self_primary_team_id'];

$_SESSION['s_primary_div_id'] = $_SESSION['s_self_primary_div_id'];
*/
$target_page = sql_quote($_GET['target_page']);

switch (strtolower($target_page)) {
    case 'user': 
	    // go to the users page
		if (file_exists('users.php')) {
            require 'uses.php';   
		}
		else {
		    require DOCUMENT_ROOT . "/page/system/file_not_exists.php";
		}
		break;   
	case 'club':
	    // go to the club_info page
		if (file_exists(DOCUMENT_ROOT . "/page/info/club_info.php")) {
            require DOCUMENT_ROOT . "/page/info/club_info.php";   
		}
		else {
		    require DOCUMENT_ROOT . "/page/system/file_not_exists.php";
		}   
		break;
    default:
	    // go to the club_info page
        if (file_exists('club_info.php')) {
            require DOCUMENT_ROOT . "/page/info/club_info.php";   
		}
		else {
		    require DOCUMENT_ROOT . "/page/system/file_not_exists.php";
		}        
		break;
}


?>
