<?php
session_start();

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once(DOCUMENT_ROOT . "/lib/Validate.class.php");

//if (isset($_POST['change_passwd'])) {
if($_SERVER["REQUEST_METHOD"] == "POST")   //check the page is request by method "POST"
{   
	$s_primary_user_id = sql_quote($_SESSION['s_primary_user_id']);
	
	$old_passwd = sql_quote($_POST['old_passwd']);
	$md5_old_passwd = md5($old_passwd);
    
	$query = sprintf(" SELECT 1 FROM user_info WHERE user_id='%s' AND passwd='%s' ",
          			 $s_primary_user_id, $md5_old_passwd);
	$rs = &$db->Execute($query);
	if (!$rs) {
	    $error_message = "Database Error.";
		require_once (DOCUMENT_ROOT . "/page/system/change_passwd.php");
		exit (0);
	}
	else if ($rs->RecordCount() <= 0) {
	    $error_message = "The old passwd is not true.";
		require_once (DOCUMENT_ROOT . "/page/system/change_passwd.php");
		exit (0);
	}
	
	$new_passwd = sql_quote($_POST['new_passwd']);
	$confirm_new_passwd = sql_quote($_POST['confirm_new_passwd']);
	if ($new_passwd != $confirm_new_passwd) {
		$error_message = "The new passwd and the confirm new passwd is not the same!";
		require_once (DOCUMENT_ROOT . "/page/system/change_passwd.php");
		exit (0);
	}
	
	$md5_new_passwd = md5($new_passwd);
	
	// change the passwd	
	$query = sprintf(" UPDATE user_info SET passwd='%s', o_passwd='%s' " . 
					 " WHERE user_id='%s' ",
          			 $md5_new_passwd, $new_passwd, $s_primary_user_id);
					 
	$rs = &$db->Execute($query);		  
    if (!$rs) {
	    $error_message = "Database Error.";
		require_once (DOCUMENT_ROOT . "/page/system/change_passwd.php");
		exit (0);
	}
	else if ($rs->RecordCount() < 0) {
	    $error_message = "The user does no exist.";
		require_once (DOCUMENT_ROOT . "/page/system/change_passwd.php");
		exit (0);
	}
	else {
	    $error_message = "Password change success.";
		require_once (DOCUMENT_ROOT . "/page/system/error.php");
	}
	
	// go to the page "club_info.php"
	echo "<meta http-equiv='refresh' content='2;URL=/fmol/page/info/club_info.php'>";
	exit (0);
	
}

?>

