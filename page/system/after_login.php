<?php
session_start();

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/Session.class.php");


//----------------------------------------------------------------------------==================	
// get the data from session
//----------------------------------------------------------------------------==================
$s_primary_user_id = sql_quote($_SESSION['s_primary_user_id']);

//----------------------------------------------------------------------------==================	
// get the data from database
//----------------------------------------------------------------------------==================	

/**
 * update the session
 */
updateTheSession_al($db, $s_primary_user_id);




/**
 * get the club and team info
 */
$query = sprintf(
			"select c.club_id as primary_club_id, " . 
			" c.name as club_name, t.name as team_name, " . 
			" t.team_id as primary_team_id " . 
			" from user_info u, club c, team t " . 
			" where u.user_id='%s' " . 
			" and c.user_id=u.user_id " . 
			" and t.club_id=c.club_id " ,
			$s_primary_user_id);

$rs = &$db->Execute($query);
if ($rs) {
    if ($rs->recordCount() > 0) {
		// config the session
        $_SESSION['s_primary_club_id'] = $rs->fields['primary_club_id'];
        $_SESSION['s_primary_team_id'] = $rs->fields['primary_team_id'];
        $_SESSION['s_club_name'] = $rs->fields['club_name'];
        $_SESSION['s_team_name'] = $rs->fields['team_name'];
		
        $_SESSION['s_self_primary_club_id'] = $rs->fields['primary_club_id'];
        $_SESSION['s_self_primary_team_id'] = $rs->fields['primary_team_id'];
        $_SESSION['s_self_club_name'] = $rs->fields['club_name'];
        $_SESSION['s_self_team_name'] = $rs->fields['team_name'];
        
    }
}


/**
 * get the div info
 */

$s_primary_team_id = '';
if(isset($_SESSION['s_primary_team_id']))
    $s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);
$query = sprintf(
			"select d.div_id as primary_div_id, d.name as div_name, d.season as cur_season " . 
			" from team_in_div tid, division d " . 
			" where tid.team_id='%s' " . 
			" and tid.div_id=d.div_id " ,
			$s_primary_team_id);
$rs = &$db->Execute($query);
if ($rs) {
    if ($rs->recordCount() > 0) {
		// config the session
        $_SESSION['s_primary_div_id'] = $rs->fields['primary_div_id'];
        $_SESSION['s_self_primary_div_id'] = $rs->fields['primary_div_id'];
        $_SESSION['s_div_name'] = $rs->fields['div_name'];
        $_SESSION['s_self_div_name'] = $rs->fields['div_name'];
		$_SESSION['s_cur_season'] = $rs->fields['cur_season'];
    }
}



/*echo "<script>window.location =\"club_info.php\";</script>";*/
echo "<meta http-equiv='refresh' content='0;URL=/fmol/page/info/club_info.php'>";
exit (0);
 
 
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
function updateTheSession_al($db, $user_id)
{
	$session 	= new Session($db); 
	$cur_time 	= time(); 
	$life_time 	= 600; //1800;  // half an hour
	$ip_address = substr($_SERVER['REMOTE_ADDR'], 0, 50);
	$con = sprintf( 
			" user_id='%s' OR ip_address='%s' " , 
			$user_id, $ip_address );
	$returnValue = $session->get_from_condition($con);
	if ($returnValue != -1 && $returnValue != -2) {
		if ($returnValue->RecordCount() == 0) {
			//$last_activity = time()+3600;
			$last_activity = $cur_time;
			$returnValue = $session->insert($user_id, $ip_address, $last_activity);
		}
		else if ($returnValue->RecordCount() > 0) {
			
			$rs = $returnValue;
			$cannot_login = false;	
			for (; !$rs->EOF; $rs->MoveNext()) {  
				$exist_session_id = $rs->fields["id"];
				$exist_user_id = $rs->fields["user_id"];
				$exist_ip_address = $rs->fields["ip_address"];
				$exist_last_activity = $rs->fields["last_activity"];
				// the exist_user is valid
				if (($cur_time - $exist_last_activity) < $life_time) {  
					if ($exist_user_id == $user_id) {  
						// the same user	
						
						if ($exist_ip_address == $ip_address) {   
							// the same user, the same ip_address
							$last_activity = $cur_time;
							$returnValue = $session->update($user_id, $ip_address, $last_activity);
							
							break;
						}
						else {   
							// the same user, different ip_address
							$cannot_login = true;
							
							session_destroy();
							
							$errorMessage = 'The same user have already logined.' ;			  		  
						    //require(DOCUMENT_ROOT . "/index.php");
						    
						    echo "<meta http-equiv='refresh' content='0;URL=/fmol/index.php?errorMessage=$errorMessage'>";
							
						    exit (1);
					    }
					    					    
					}
					/*
					else if ($exist_ip_address == $ip_address) {
						// the same ip adderss
						$cannot_login = true;
						
						session_destroy();  
						
						$errorMessage = 'The same ip address can not login two users.' ;					  
					    //require_once(DOCUMENT_ROOT . "/index.php");
					    
						echo "<meta http-equiv='refresh' content='0;URL=/fmol/index.php?errorMessage=$errorMessage'>";
							
						exit (1);	
					    		
					}
					*/
				}
				else {
					$con = sprintf(" id='%s' ", $exist_session_id);
					$rv = $session->delete($con);  
				}
			
			}
			
			if (!$cannot_login) {
				$last_activity = $cur_time;
				$returnValue = $session->insert($user_id, $ip_address, $last_activity);
			}
		}
	} 
}


?>
