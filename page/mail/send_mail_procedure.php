<?php
session_start();

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/mail.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//if (isset($_POST['new_club_name'])) {
if($_SERVER["REQUEST_METHOD"] == "POST") {   //check the page is request by method "POST"
    
	$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);
	$s_self_team_name = sql_quote($_SESSION['s_self_team_name']);

    $to_id = sql_quote($_POST['to_team_id']);
	
	// The user can not send mail to the system
	if ($to_id == "0") {
		$error_message = "You can not send mail to the system.";
		require_once (DOCUMENT_ROOT . "/page/system/error.php");
		
		echo "<meta http-equiv='refresh' content='2;URL=javascript:history.go(-2)'>";
		exit(0);	
	}
	
	$subject = sql_quote($_POST['subject']);
	$content = sql_quote($_POST['content']);
    $from_id = $s_primary_team_id;
	$from_name = $s_self_team_name;
	$type = 2;
	
	$returnValue = sendMail($db, $from_id, $from_name, $to_id, $subject, $content, $type); 
									  
    if ($returnValue < 0) {
	    $error_message = "send mail error";
		require_once (DOCUMENT_ROOT . "/page/system/error.php");
		
		echo "<meta http-equiv='refresh' content='2;URL=javascript:history.go(-1)'>";
		exit(0);
	}
	else {
		$error_message = "Message sent success";
		require_once (DOCUMENT_ROOT . "/page/system/error.php");
		
		echo "<meta http-equiv='refresh' content='2;URL=javascript:history.go(-2)'>";
		exit(0);
	}						  

}

?>

