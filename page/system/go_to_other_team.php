<?php
/**
 * after seach other team, go to the page of the team choosed 
 */
 
session_start();

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

if (isset($_GET['primary_team_id'])) {
  
	$primary_team_id = sql_quote($_GET['primary_team_id']); 
	// config the session
	$_SESSION['s_primary_team_id'] = $primary_team_id;
	$_SESSION['s_opponent_primary_team_id'] = $primary_team_id;
	
	$query = sprintf(
				" SELECT u.user_id AS primary_user_id, c.club_id AS primary_club_id " . 
				" FROM user_info u, club c, team t " . 
				" WHERE t.team_id='%s' " . 
				" AND u.user_id = c.user_id " . 
				" AND c.club_id = t.club_id " ,
				$primary_team_id);
	
	$rs = &$db->Execute($query);
	if ($rs) {
		if ($rs->recordCount() > 0) {
			// config the session
			$_SESSION['s_primary_user_id'] = $rs->fields['primary_user_id'];
			$_SESSION['s_primary_club_id'] = $rs->fields['primary_club_id'];
		}
	}  
	
	$query = sprintf(
				"select division.div_id as primary_div_id " . 
				" from team_in_div tid, division " . 
				" where tid.team_id='%s' " . 
				" and tid.div_id=division.div_id " ,
				$primary_team_id);
	$rs = &$db->Execute($query);
	if ($rs) {
		if ($rs->recordCount() > 0) {
			// config the session
			$_SESSION['s_primary_div_id'] = $rs->fields['primary_div_id'];

		}
	}

}

// go to the club_info page
if (file_exists(DOCUMENT_ROOT . "/page/info/club_info.php")) {
	require_once (DOCUMENT_ROOT . "/page/info/club_info.php");   
}
else {
	require_once (DOCUMENT_ROOT . "/page/system/file_not_exists.php");
} 


?>
