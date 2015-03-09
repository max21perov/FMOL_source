<?php // access_control.php
session_start();

//$document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/Session.class.php");
require_once(DOCUMENT_ROOT . "/lib/check_post_and_get.php");


if(!isset($_SESSION['s_self_primary_user_id'])) {
    $errorMessage = 'please login again'; 
    /*echo "<script>window.location =\"index.php\";</script>";*/
	require_once(DOCUMENT_ROOT . "/index.php");
	exit (1);
}
else {
     
	$s_self_primary_user_id = $_SESSION['s_self_primary_user_id'];

	$query = sprintf(
			" SELECT 1 FROM user_info " .
			" WHERE id='%s' " ,
			$s_self_primary_user_id );
			
	$rs = &$db->Execute($query);
	if (!$rs) {
		//print $db->ErrorMsg(); // Displays the error message if no results could be returned
		$errorMessage = 'A database error occurred while checking your ' .
          'login details.\\nIf this error persists, please ' .
          'contact master@fmol.cn. ' ;
		  
	    require_once(DOCUMENT_ROOT . "/index.php");
	    exit (1);
	}
	else {
		if ($rs->RecordCount() == 0) {
			$errorMessage = 'The user or passwd is incorrect'; 
			
	        require_once(DOCUMENT_ROOT . "/index.php");
	        exit (1);
		}
		else if ($rs->RecordCount() > 0) { 
			/**
			 * the user is exist
			 * then update the session
			 **/
			$user_id = $_SESSION['s_primary_user_id'];
			updateTheSession_ac($db, $user_id);
			
		}
	}

}

//----------------------------------------------------------------------------	
// common functions
//----------------------------------------------------------------------------	
/**
 * update the session
 *
 * @param [db]		db
 *
 * @return  no
 */	
function updateTheSession_ac($db, $user_id)
{
	$session 	= new Session($db); 
	$cur_time 	= time(); 
	$life_time 	= 600; // 1800;  // half an hour
	$ip_address = substr($_SERVER['REMOTE_ADDR'], 0, 50);
	$con = sprintf( 
			" user_id='%s' AND ip_address='%s' " , 
			$user_id, $ip_address );
			
	$returnValue = $session->get_from_condition($con);  
	if ($returnValue != -1 && $returnValue != -2) {
		if ($returnValue->RecordCount() == 0) {
			
			session_destroy();
						
			$errorMessage = 'please login again'; 	  
			//require_once(DOCUMENT_ROOT . "/index.php");
			
			echo "<meta http-equiv='refresh' content='0;URL=/fmol/index.php?errorMessage=$errorMessage'>";
			exit (1);
		}
		else if ($returnValue->RecordCount() > 0) {
			
			$rs = $returnValue;
			$login_again = true;
			for (; !$rs->EOF; $rs->MoveNext()) {
				$exist_session_id = $rs->fields["id"];
				$exist_last_activity = $rs->fields["last_activity"];
				if (($cur_time - $exist_last_activity) < $life_time) {
					$last_activity = $cur_time;
					$returnValue = $session->update($user_id, $ip_address, $last_activity);
					
					$login_again = false;
					
					break;
				}	
				else { 
					$con = sprintf(" id='%s' ", $exist_session_id);
					$session->delete($con);					
				}
			}
			
			if ($login_again) {
				session_destroy();
						
				$errorMessage = 'You have not moved for more than 30mins, please login again. '; 	  
				//require_once(DOCUMENT_ROOT . "/index.php");
				
				echo "<meta http-equiv='refresh' content='0;URL=/fmol/index.php?errorMessage=$errorMessage'>";
				exit (1);
			}
						
		}
	} 
}

?>
