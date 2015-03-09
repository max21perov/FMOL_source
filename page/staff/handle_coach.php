<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");



$myaction = sql_quote($_GET["myaction"]); 
if ("releaseCoach" == $myaction) {
	performReleaseCoach($db, DOCUMENT_ROOT);
	exit(0);
}
else {

	goToPageInTime(0, "/fmol/page/staff/coach_list.php");

}



//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
/**
	 * release coach of team
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performReleaseCoach($db, $document_root)
{
	$coach_id = sql_quote($_GET["coach_id"]);
	$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);
	$s_self_team_name = sql_quote($_SESSION['s_self_team_name']);
	
	// whether can release
	$returnValue = whetherCanRelease($db, $coach_id, $s_primary_team_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// whether can release
	$coach_name = "";
	$returnValue = getCoachNameById($db, $coach_id, $coach_name);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	//
	$db->BeginTrans();

	// release coach from team
	$returnValue = releaseCoachFromTeam($db, $coach_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	

	
	// 151 mail
	$from_name 	= "system";
	$to_id 		= $s_primary_team_id;
	$subject 	= "151 release coach";
	$content 	= "$coach_name agree to be released, no longer be the coach of $s_self_team_name";
	$status 	= 0;  // 0 - new;
	$type 		= 1;  // 1 - GAME NEWS;
	$small_type = 0;  // 0 - GAME NEWS-TRANSFER - DO NOT HAVE TO HANDLE;
	$returnValue = insertIntoMail($db, $from_name, $to_id, $subject, $content, $type, $small_type);
	if ($returnValue != "0") {
		$db->RollbackTrans();
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	
	// commit
	$db->CommitTrans();
	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
	
	
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, "/fmol/page/staff/coach_list.php");	
	
}



//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------


/**
 * whether can release
 *
 * @param [db]					database
 * @param [coach_id]			coach_id
 *
 * @return return 0, -1, -2
 */	
function whetherCanRelease($db, $coach_id, $s_primary_team_id)
{    
	$query = sprintf (
				" SELECT team_id " . 
				" FROM coach " .
				" WHERE coach_id='%s' " ,
				$coach_id);

	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() <= 0 ){
		return "There is not this right record in the database.";
	}
	else {
		$owner_team_id = $rs->fields['team_id'];
		if ($owner_team_id != $s_primary_team_id) {
			return "The coach is not yours.";
		}
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
function releaseCoachFromTeam($db, $coach_id)
{    
	$query = sprintf (
				" UPDATE coach SET " . 
				" team_id='0', release_flag='1' " .
				" WHERE coach_id='%s' " ,
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
 * get coach name by coach_id
 *
 * @param [db]					database
 * @param [coach_id]			coach_id
 *
 * @return return 0, -1, -2
 */	
function getCoachNameById($db, $coach_id, & $coach_name)
{    
	$query = sprintf (
				" SELECT custom_given_name as given_name, custom_family_name as family_name " . 
				" FROM coach " .
				" WHERE coach_id='%s' " ,
				$coach_id);

	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() <= 0 ){
		return "There is not this right record in the database.";
	}
	else {
		$given_name = $rs->fields['given_name'];
		$full_name = "";
		if ($given_name == "") {
			$full_name = $rs->fields['family_name'];
		}
		else {
			$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
		}
		
		$coach_name = $full_name;
		
		return "0";		
	}
		
	return "0";
}


/**
 * insert a record into mail_buffer (have not the player_id)
 *
 * @param [db]					database
 * @param [from_name]			from_name
 * @param [to_id]				to_id	
 * @param [subject]				subject	
 * @param [content]				content	
 * @param [type]				type	
 * @param [small_type]			small_type, default: 0 - GAME NEWS-TRANSFER - DO NOT HAVE TO HANDLE;	
 *
 * @return return "0", or other error msg
 */	
function insertIntoMail($db, $from_name, $to_id, $subject, $content, $type, $small_type="0")
{
	$query = sprintf(
				" INSERT INTO mail (from_name, to_id, subject, content, time, status, type, small_type) " .
				" VALUES ('%s', '%s', '%s', '%s', now(), '0', '%s', '%s') " ,
				$from_name, $to_id, $subject, $content, $type, $small_type);
				print $query ;
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
