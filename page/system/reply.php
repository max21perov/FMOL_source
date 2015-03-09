<?php
session_start();

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/Email.class.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

$get_str = sql_quote($_POST['get_str']); 

$str_array = split('&', $get_str);

$passwd = $str_array[0]; 
$user_id = $str_array[1]; 

$query = sprintf(
			" select user_id as primary_user_id, name as user_name, o_passwd, email " .
			" from user_info " .
			" where user_id='%s' and passwd='%s' " ,
			$user_id, $passwd);
$rs = &$db->Execute($query);
if (!$rs) {
	$errorMessage = 'Database error'; 
}
else if ($rs->RecordCount() <= 0) { 
    $errorMessage = "sorry, the user: '$user_id' is not exist";
}
else {
    $primary_user_id = $rs->fields['primary_user_id'];
    $user_name = $rs->fields['user_name'];
    $o_passwd = $rs->fields['o_passwd'];
    $email = $rs->fields['email'];
    
	/**
	 * to see whether the user have activate
	 * YES -> do nothing
	 * NO -> continue to activate the user 
	 */
	$query = sprintf(
				" select 1 from user_info " . 
				" where enable='0' " . 
				" and user_id='%s' " ,
				$primary_user_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		$errorMessage = 'Database error'; 
		require_once (DOCUMENT_ROOT . "/index.php");
		exit;
	}
	else if ($rs->RecordCount() <= 0) {
	    require_once (DOCUMENT_ROOT . "/index.php");
		exit;
	}
	
	$db->BeginTrans();
	
	/**
	 * activate the user by set the column 'enable' to 1
	 */
	$query = sprintf(
				" update user_info " . 
				" set enable='1' " . 
				" where user_id='%s' " ,
				$primary_user_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
	    $db->RollbackTrans();
		$errorMessage = 'Database error'; 
		require_once (DOCUMENT_ROOT . "/index.php");
		exit;
	}
	else if ($rs->RecordCount() < 0) {
		$db->RollbackTrans();
	    require_once (DOCUMENT_ROOT . "/index.php");
		exit;
	}

	/**
	 * allocate a team to the user
	 */ 
	$query = sprintf(	
				" select c.club_id AS primary_club_id " . 
				" from div_tree dt, team_in_div tid, team t, club c " . 
				" where dt.upper_id='1' " . 
				" and dt.lower_id=tid.div_id " . 
				" and tid.team_id=t.team_id " . 
				" and t.club_id=c.club_id " . 	
				" and c.user_id is null ");
	$rs = &$db->Execute($query);
	if (!$rs) {
		$db->RollbackTrans();
		$errorMessage = 'Database error'; 
		require_once (DOCUMENT_ROOT . "/index.php");
		exit;
	}
	else if ($rs->RecordCount() <= 0) {
		$db->RollbackTrans();
	    require_once (DOCUMENT_ROOT . "/index.php");
		exit;
	}
	else {
		$count = $rs->RecordCount();
		// product rand number(0 ~ $count-1)
		// step 1:
		$seedarray =microtime(); 
		$seedstr =split(" ",$seedarray,5); 
		$seed =$seedstr[0]*10000; 
		// step 2:
		srand($seed); 
		// setp 3:
		$index = rand(0, $count-1);
		
		$rs->Move($index);
		
		$primary_club_id = $rs->fields['primary_club_id'];
		
		// connect the club and user
		$query = sprintf(
					" update club set user_id='%s' " .
					" WHERE club_id='%s' " ,
					$primary_user_id, $primary_club_id);
		$rs = &$db->Execute($query);
		if (!$rs) {
			$db->RollbackTrans();
			$errorMessage = 'Database error'; 
			require_once (DOCUMENT_ROOT . "/index.php");
			exit;
		}
		else if ($rs->RecordCount() < 0) {
			$db->RollbackTrans();
			require_once (DOCUMENT_ROOT . "/index.php");
			exit;
		}
	}
	
	/**
	 * insert the user into the "coach" table
	 */
	// first, check whether the user is already in the coach table
	$query = sprintf(
				" SELECT FROM coach " . 
				" WHERE coach_id='%s' " ,
				$primary_user_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
	    $db->RollbackTrans();
		$errorMessage = 'Database error'; 
		require_once (DOCUMENT_ROOT . "/index.php");
		exit;
	}
	else if ($rs->RecordCount() < 1) {
		// only the user has not been put into the coach table, 
		// then insert the user into the coach table
		
		$coach_info_arr = getCoachInfo($db, $primary_user_id, $primary_club_id);
		$query = sprintf(
					" INSERT INTO coach " . 
					" (coach_id, team_id, nation_id, given_name, " . 
					" custom_given_name, gk_training, attacking, defending, fitness, " . 
					" tactics, technical, youth ) " ,
					$primary_user_id, $coach_info_arr["team_id"],
					$coach_info_arr["nation_id"], $coach_info_arr["name"], $coach_info_arr["name"],
					$coach_info_arr["gk_training"], $coach_info_arr["attacking"], $coach_info_arr["defending"], 
					$coach_info_arr["fitness"], $coach_info_arr["tactics"], $coach_info_arr["technical"],
					$coach_info_arr["youth"]);
		$rs = &$db->Execute($query);
		if (!$rs) {
		    $db->RollbackTrans();
			$errorMessage = 'Database error'; 
			require_once (DOCUMENT_ROOT . "/index.php");
			exit;
		}
		else if ($rs->RecordCount() < 0) {
			$db->RollbackTrans();
		    require_once (DOCUMENT_ROOT . "/index.php");
			exit;
		}
	}
	
	
	$db->CommitTrans();
	
	$mail = new Email();
	$mail->setTo($email); // the address to receive the email
	$mail->setFrom("fmol@fmol.cn");// the email source
	$mail->setSubject("your account and password from FMOL") ; // the subject 
	$mail->setText("user_name: $user_name  passwd: $o_passwd ") ;
	if ( $mail->send() ) {
		// $welcome_info is a php variable in the file "welcome.php"
		$welcome_info = "The user name and password has been sent to your email, <br>";
		$welcome_info .= "please receive your email and get them.<br>"; 
		require_once (DOCUMENT_ROOT . "/page/system/welcome.php");
	}
	else {
		$errorMessage = 'fail to send the email';
	}

}




// require the index page 
require_once (DOCUMENT_ROOT . "/index.php");


//----------------------------------------------------------------------------	
// common functions
//----------------------------------------------------------------------------	

/**
  * get the coach information
  *
  * @param [db]				database
  * @param [club_id]		club µÄ id
  *
  * @return fans basic info
  */
function getCoachInfo($db, $user_id, $club_id)
{
	$user_info_arr = getUserInfo($db, $user_id);
	
	$team_info_arr = getTeamInfo($club_id);
	
	$coach_ability_arr = getCoachAbility();
	
	$coach_info_arr = array_merge($user_info_arr, $coach_ability_arr);
	$coach_info_arr["team_id"] = $team_info_arr["team_id"];
	
	return $coach_info_arr;
}

function getUserInfo($db, $user_id)
{
	$user_info_arr = array();
	
	$query = sprintf(
				" SELECT user_id, name, nation_id " . 
				" FROM user_info " . 
				" WHERE user_id='%s' " ,
				$user_id);
				  
	$rs = &$db->Execute($query);
	if (!$rs) {		
		$error_message = "Database error.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() < 0){
		$error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() > 0) {
		$user_info_arr["primary_user_id"] = $rs->fields["user_id"];
		$user_info_arr["user_id"] = $rs->fields["user_id"];
		$user_info_arr["name"] = $rs->fields["name"];
		$user_info_arr["nation_id"] = $rs->fields["nation_id"];
	}
	
	return $user_info_arr;	
}

function getTeamInfo($club_id)
{
	$team_info_arr = array();
	
	$query = sprintf(
				" SELECT team_id " . 
				" FROM team " . 
				" WHERE club_id='%s' " ,
				$club_id);
				  
	$rs = &$db->Execute($query);
	if (!$rs) {		
		$error_message = "Database error.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() < 0){
		$error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() > 0) {
		$team_info_arr["team_id"] = $rs->fields["team_id"];
	}
	
	return $team_info_arr;	
}



function getCoachAbility()
{
	// 
	$coach_ability_arr = array();
	$coach_ability_arr["attacking"] = 12;  // default 12	
	$coach_ability_arr["defending"] = 12;	
	$coach_ability_arr["fitnesss"] = 12;	
	$coach_ability_arr["tactics"] = 12;	
	$coach_ability_arr["technical"] = 12;	
	$coach_ability_arr["youth"] = 12;	
	$coach_ability_arr["gk_training"] = 12;	
	
	return $coach_ability_arr;
}



?>
