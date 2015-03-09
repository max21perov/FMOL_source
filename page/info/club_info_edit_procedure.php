<?php
session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//if (isset($_POST['new_club_name'])) {
if($_SERVER["REQUEST_METHOD"] == "POST") {   //check the page is request by method "POST"
    
	$s_primary_club_id = sql_quote($_SESSION['s_primary_club_id']);
	
	$new_club_name = sql_quote($_POST['new_club_name']);
    
	$db->BeginTrans();
	
	// change the club name
	$query = sprintf(
				" update club set name='%s' " . 
				" where club_id=$s_primary_club_id " , 
				$new_club_name);
	
	$rs = &$db->Execute($query);

									  
    if (!$rs) {
	    $db->RollbackTrans();
	    print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else if ($rs->RecordCount() < 0) {
	    $db->RollbackTrans();
	    print 'the club does not exist';
	}
	
	// change the team name
	$query = sprintf(
				" update team set name='%s' " .
				" where club_id=$s_primary_club_id " ,
				$new_club_name);
	
	$rs = &$db->Execute($query);

									  
    if (!$rs) {
	    $db->RollbackTrans();
	    print "Database Error!"; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else if ($rs->RecordCount() < 0) {
	    $db->RollbackTrans();
	    print 'the club does not exist';
	}
	else {
	    $db->CommitTrans();
		
		// change the "s_self_club_name" in the session
		$_SESSION['s_self_club_name'] = $new_club_name;
		
        /*echo "<script>window.location =\"club_info.php\";</script>";*/
		echo "<meta http-equiv='refresh' content='0;URL=/fmol/page/info/club_info.php'>";
	}													  

}

?>

