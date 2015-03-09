<?php
session_start();
//$document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once (DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/mail.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);


//----------------------------------------------------------------------------	
// handle the friendly list or the friendly pool
//----------------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] != "POST")    //check the page is request by method "POST"
{
	// go back to the page "friendly_list.php"
	goToPageInTime(0, "/fmol/page/friendly/friendly_list.php");
}

$myaction = sql_quote($_GET["myaction"]); 
if ("acceptRspFriendlyByMail" == $myaction) {
	performAcceptRspFriendlyByMail($db, DOCUMENT_ROOT);
}
else if ("declineRspFriendlyByMail" == $myaction) {
	performDeclineRspFriendlyByMail($db, DOCUMENT_ROOT);
}
else if ("cancelAppFriendly" == $myaction) {
	performCancelAppFriendly($db, DOCUMENT_ROOT);
}
else if ("acceptRspFriendly" == $myaction) {
	performAcceptRspFriendly($db, DOCUMENT_ROOT);
}
else if ("cancelWaitFriendly" == $myaction) {
	performCancelWaitFriendly($db, DOCUMENT_ROOT);
}
else if ("joinFriendlyPool" == $myaction) {
	performJoinFriendlyPool($db, DOCUMENT_ROOT);
}
else if ("friendlyArrange" == $myaction) {
	performFriendlyArrange($db, DOCUMENT_ROOT, $TPL_PATCH);
}
else {
	// go back to the page "friendly_list.php"
	goToPageInTime(0, "/fmol/page/friendly/friendly_list.php");
}




//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
/**
 * 在邮件中直接 accept 友谊赛
 */
function performAcceptRspFriendlyByMail($db, $document_root)
{
	$mail_id = sql_quote($_POST["mail_id"]);
	$friendly_id = sql_quote($_POST["friendly_id"]);
	$mail_content = "You have accepted this friendly match!";
	
	$db->BeginTrans();
	
	// updateMailContent
	$returnValue = updateMailContent($db, $mail_id, $mail_content);
	if ($returnValue < 0) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");
	}
	
	// acceptRspFriendly
	$returnValue = acceptRspFriendly($db, $document_root, $friendly_id);
	if ($returnValue < 0) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");		
	}
	
	// send one mail back to from_id
	$from_id 	= $_SESSION['s_primary_team_id'];
	$from_name 	= $_SESSION['s_self_team_name'];
    $to_id 		= sql_quote($_POST["from_team_id"]);
	$o_subject 	= sql_quote($_POST['subject']);
	$subject    = "Re: " . $o_subject;
	$content 	= "<span class=\"BlackText\"><a href=\"/fmol/page/info/club_info.php?team_id=$from_id\" target=\"_parent\">$from_name</a></span>";
	$content   .= " has accepted the friendly match: \"$o_subject\".";
	$type 		= 2;
	$returnValue = sendMail($db, $from_id, $from_name, $to_id, $subject, $content, $type);
	if ($returnValue < 0) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");		
	}
	else {
		// commit
		$db->CommitTrans();
		$error_message = "Accept success.";
		require ("$document_root/page/system/error.php");
	}
	
	// go back to the page "mail.php"
	goToPageInTime(2, "/fmol/page/mail/mail.php?mail_id=$mail_id");
}

/**
 * 在邮件中直接 decline 友谊赛
 */
function performDeclineRspFriendlyByMail($db, $document_root)
{
	$mail_id = sql_quote($_POST["mail_id"]);   
	$friendly_id = sql_quote($_POST["friendly_id"]);
	$mail_content = "You have declined this friendly match!";
	
	$db->BeginTrans();
	
	// updateMailContent
	$returnValue = updateMailContent($db, $mail_id, $mail_content);
	if ($returnValue < 0) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");
	}
	
	// declineRspFriendly
	$returnValue = declineRspFriendly($db, $document_root, $friendly_id);
	if ($returnValue < 0) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");		
	}
	
	// send one mail back to from_id
	$from_id 	= $_SESSION['s_primary_team_id'];
	$from_name 	= $_SESSION['s_self_team_name'];
    $to_id 		= sql_quote($_POST["from_team_id"]);
	$o_subject 	= sql_quote($_POST['subject']);
	$subject    = "Re: " . $o_subject;
	$content 	= "<span class=\"BlackText\"><a href=\"/fmol/page/info/club_info.php?team_id=$from_id\" target=\"_parent\">$from_name</a></span>";
	$content   .= " has declined the friendly match: \"$o_subject\".";
	$type 		= 2;
	$returnValue = sendMail($db, $from_id, $from_name, $to_id, $subject, $content, $type);
	if ($returnValue < 0) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");		
	}
	else {
		// commit
		$db->CommitTrans();
		$error_message = "Decline success.";
		require ("$document_root/page/system/error.php");
	}
	
	// go back to the page "mail.php"
	goToPageInTime(2, "/fmol/page/mail/mail.php?mail_id=$mail_id");	
}

/**
 * cancel the "app" friendly by handle the table "friendly"
 */
function performCancelAppFriendly($db, $document_root)
{
	$friendly_id = sql_quote($_POST['friendly_id']);
	$friendly_filter = sql_quote($_GET['friendly_filter']);
	$next_page = sql_quote($_GET['next_page']);
	
	$returnValue = cancelAppFriendly($db, $document_root, $friendly_id);
	if ($returnValue < 0) {
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");
		// go back to the page "friendly_list.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_list.php?next_page=$next_page&friendly_filter=$friendly_filter");
	}
	
	// send one mail back to opponent_id
	$from_id 	= $_SESSION['s_primary_team_id'];
	$from_name 	= $_SESSION['s_self_team_name'];
    $to_id 		= sql_quote($_POST['opponent_id']);
	$subject 	= $from_name . " has canceled the app friendly match ";
	$content 	= "<span class=\"BlackText\"><a href=\"/fmol/page/info/club_info.php?team_id=$from_id\" target=\"_parent\">$from_name</a></span>";
	$content   .= " has canceled the app friendly match.";
	$type 		= 2; // user message
	$returnValue = sendMail($db, $from_id, $from_name, $to_id, $subject, $content, $type);
	if ($returnValue < 0) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");	
		// go back to the page "friendly_list.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_list.php?next_page=$next_page&friendly_filter=$friendly_filter");	
	}
	
	// show success flag
	$db->CommitTrans();
	$error_message = "Cancel app success.";
	require ("$document_root/page/system/error.php");
	
	// go back to the page "friendly_list.php"
	goToPageInTime(2, "/fmol/page/friendly/friendly_list.php?next_page=$next_page&friendly_filter=$friendly_filter");		
}

/**
 * accept the "rsp" friendly by handle the table "friendly"
 */
function performAcceptRspFriendly($db, $document_root)
{
	$friendly_id = sql_quote($_POST['friendly_id']);
	$friendly_filter = sql_quote($_GET['friendly_filter']);
	$next_page = sql_quote($_GET['next_page']);
	
	$returnValue = acceptRspFriendly($db, $document_root, $friendly_id);
	if ($returnValue < 0) {
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");
		// go back to the page "friendly_list.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_list.php?next_page=$next_page&friendly_filter=$friendly_filter");
	}
	
	// send one mail back to opponent_id
	$from_id 	= $_SESSION['s_primary_team_id'];
	$from_name 	= $_SESSION['s_self_team_name'];
    $to_id 		= sql_quote($_POST['opponent_id']);
	$subject 	= $from_name . " has accepted the rsp friendly match ";
	$content 	= "<span class=\"BlackText\"><a href=\"/fmol/page/info/club_info.php?team_id=$from_id\" target=\"_parent\">$from_name</a></span>";
	$content   .= " has accepted the rsp friendly match.";
	$type 		= 2; // user message
	$returnValue = sendMail($db, $from_id, $from_name, $to_id, $subject, $content, $type);
	if ($returnValue < 0) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");	
		// go back to the page "friendly_list.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_list.php?next_page=$next_page&friendly_filter=$friendly_filter");	
	}
	
	// show success flag
	$error_message = "Accept rsp success.";
	require ("$document_root/page/system/error.php");
	
	// go back to the page "friendly_list.php"
	goToPageInTime(2, "/fmol/page/friendly/friendly_list.php?next_page=$next_page&friendly_filter=$friendly_filter");
}

/**
 * cancel the "wait" friendly by handle the table "friendly_pool"
 */
function performCancelWaitFriendly($db, $document_root)
{
	$friendly_pool_id = sql_quote($_POST['friendly_pool_id']);
	$friendly_filter = sql_quote($_GET['friendly_filter']);
	$next_page = sql_quote($_GET['next_page']);
	
	$returnValue = cancelWaitFriendly($db, $document_root, $friendly_pool_id);
	if ($returnValue < 0) {
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");
	}
	else {
		$error_message = "Cancel wait success.";
		require ("$document_root/page/system/error.php");
	}
	
	// go back to the page "friendly_list.php"
	goToPageInTime(2, "/fmol/page/friendly/friendly_list.php?next_page=$next_page&friendly_filter=$friendly_filter");
}

/**
 * join the friendly pool 
 * by handle the table "friendly_pool" and "friendly"
 */
function performJoinFriendlyPool($db, $document_root)
{
	$s_primary_team_id 	= $_SESSION['s_primary_team_id'];
	$friendly_pool_id 	= sql_quote($_POST['friendly_pool_id']);
	$owner_team_id 		= sql_quote($_POST['owner_team_id']);  
	$home_or_away 		= sql_quote($_POST['home_or_away']);
	$o_time 			= sql_quote($_POST['o_time']);
	$friendly_filter 	= sql_quote($_GET['friendly_filter']);
	
	joinFriendlyPool($db, $document_root, $s_primary_team_id, $friendly_pool_id, $owner_team_id, $home_or_away, $o_time);
}

/**
 * arrange the friendly match
 */
function performFriendlyArrange($db, $document_root, $TPL_PATCH)
{
	$s_primary_team_id = $_SESSION['s_primary_team_id'];
	$home_or_away = sql_quote($_POST['home_or_away']);
	$friendly_type = sql_quote($_POST['friendly_type']);
	
	friendlyArrange($db, $document_root, $TPL_PATCH, $s_primary_team_id, $home_or_away, $friendly_type);
}

//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------
function cancelAppFriendly($db, $document_root, $friendly_id)
{
	$query =" DELETE FROM friendly ";
    $query .=" WHERE id = '$friendly_id' "; 
    $query .=" AND status<>'2' ";
	
	$rs = &$db->Execute($query);

	if (!$rs) {
		return -1;
	}
	else if ($rs->RecordCount() < 0 ){
	    return -1;
	}
	else {
	    return 1;
	}
}

function acceptRspFriendly($db, $document_root, $friendly_id)
{
	$query =" UPDATE friendly ";
	$query .= " SET status = '2' ";
    $query .=" WHERE id = '$friendly_id' ";
	
	$rs = &$db->Execute($query);

	if (!$rs) {
		return -1;
	}
	else if ($rs->RecordCount() < 0 ){
		return -1;
	}
	else {
		return 1;
	}
		
}

function declineRspFriendly($db, $document_root, $friendly_id)
{
	$query =" DELETE FROM friendly ";
    $query .=" WHERE id = '$friendly_id' ";
	
	$rs = &$db->Execute($query);

	if (!$rs) {
		return -1;
	}
	else if ($rs->RecordCount() < 0 ){
		return -1;
	}
	else {
		return 1;
	}
		
}

function cancelWaitFriendly($db, $document_root, $friendly_pool_id)
{
	$query =" DELETE FROM friendly_pool ";
    $query .=" WHERE id = '$friendly_pool_id' ";
	
	$rs = &$db->Execute($query);

	if (!$rs) {
		return -1;
	}
	else if ($rs->RecordCount() < 0 ){
	    return -1;
	}
	else {
	    return 1;
	}
}

function joinFriendlyPool($db, $document_root, $s_primary_team_id, $friendly_pool_id, $owner_team_id, $home_or_away, $o_time)
{
	// step 1: 自己不能加入自己建立的 friendly_pool
	if($s_primary_team_id == $owner_team_id) {
	    $error_message = "Sorry, the owner can not join his own game.";
		require ("$document_root/page/system/error.php");
		// go back to the page "friendly_pool.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_pool.php");
	}
	
	$db->BeginTrans();
	
	$home_id = '';
	$away_id = '';
	if ($home_or_away == 'A') {
	    $home_id = $s_primary_team_id;
		$away_id = $owner_team_id;
	}
	else {
	    $home_id = $owner_team_id;
		$away_id = $s_primary_team_id;
	}
	
	// setp 2: 往 friendly 表中插入该友谊赛，表示用户已经加入了该 friendly_pool
	$query  = " INSERT INTO friendly ";
	$query .= " (time, home_id, away_id, status) ";
	$query .= " VALUES('$o_time', '$home_id', '$away_id', '2') "; 
	
	$rs = &$db->Execute($query);

	if (!$rs) {
		$db->RollbackTrans(); 
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");
		// go back to the page "friendly_pool.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_pool.php");
		
	}
	else if ($rs->RecordCount() < 0 ){
		$db->RollbackTrans();
	    $error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error.php");
		// go back to the page "friendly_pool.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_pool.php");
	}
	
	// setp 3: 因为 friendly_pool 只接收一个参与者，所以当用户加入以后，要删除该 friendly_pool 记录
	$query  =" DELETE FROM friendly_pool ";
    $query .=" WHERE id = '$friendly_pool_id' ";
	
	$rs = &$db->Execute($query);

	if (!$rs) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");
		// go back to the page "friendly_pool.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_pool.php");
	}
	else if ($rs->RecordCount() < 0 ){
		$db->RollbackTrans();
	    $error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error.php");
		// go back to the page "friendly_pool.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_pool.php");
	}
	
	// setp 3: 向该 friendly_pool 记录的创建者发送消息，告诉他用户已经加入了该 friendly_pool
	$from_id 	= $_SESSION['s_primary_team_id'];
	$from_name 	= $_SESSION['s_self_team_name'];
    $to_id 		= sql_quote($_POST['owner_team_id']);
	$subject 	= $from_name . " has joined your friendly pool ";
	$content 	= "<span class=\"BlackText\"><a href=\"/fmol/page/info/club_info.php?team_id=$from_id\" target=\"_parent\">$from_name</a></span>";
	$content   .= " has joined your friendly pool ($o_time).";
	$type 		= 2; // user message
	$returnValue = sendMail($db, $from_id, $from_name, $to_id, $subject, $content, $type);
	if ($returnValue < 0) {
		$db->RollbackTrans();
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");	
		// go back to the page "friendly_list.php"
		goToPageInTime(2, "/fmol/page/friendly/friendly_pool.php");	
	}
	
	// show success flag
	$db->CommitTrans();
	$error_message = "Join success.";
	require ("$document_root/page/system/error.php");
	
	// go back to the page "friendly_pool.php"
	goToPageInTime(2, "/fmol/page/friendly/friendly_pool.php");
}

function friendlyArrange($db, $document_root, $TPL_PATCH, $s_primary_team_id, $home_or_away, $friendly_type)
{
	if ($friendly_type == 'pool') {
	    // friendly pool
		$home_or_away_id = ($home_or_away=='home') ? 0 : 1 ;
		
		$query = " INSERT INTO friendly_pool ";
		$query .= " (time, team_id, home_away) ";
		$query .= " VALUES(now(), '$s_primary_team_id', '$home_or_away_id') ";
		
		$rs = &$db->Execute($query);

		if (!$rs) {
			$error_message = "Database error.";
			require ("$document_root/page/system/error.php");
			// back to the page "friendly_arrange.php"
			goBackInTime(2500, -1);
		}
		else if ($rs->RecordCount() < 0 ){
			$error_message = "Can not insert this record in the database.";
			require ("$document_root/page/system/error.php");
			// back to the page "friendly_arrange.php"
			goBackInTime(2500, -1);
		}
		else {
			$error_message = "Arrange success.";
			require ("$document_root/page/system/error.php");
			// go back to the page "friendly_pool.php"
			goToPageInTime(2, "/fmol/page/friendly/friendly_pool.php");
		}
	} //if ($friendly_type == 'pool') 
	else {
	    if (empty($_POST['opponent_team_id'])) {
		    $error_message = "The team textfield can not be empty.";
			require ("$document_root/page/system/error.php");
			// back to the page "friendly_arrange.php"
			goBackInTime(2500, -1);			
		}	
		
		if ($_POST['opponent_team_id'] == $s_primary_team_id) {
		    $error_message = "Your opponent can not be self team.";
			require ("$document_root/page/system/error.php");
			// back to the page "friendly_arrange.php"
			goBackInTime(2500, -1);			
		}	
	    // app friendly
		$home_id = '';
		$away_id = '';
		$status = '';
		if ($home_or_away == 'away') {
			$home_id = sql_quote($_POST['opponent_team_id']);
			$away_id = $s_primary_team_id;
			$status = '1'; // to the home team, the status is 'rsp'
		}
		else {
			$home_id = $s_primary_team_id;
			$away_id = sql_quote($_POST['opponent_team_id']);
			$status = '0'; // to the home team, the status is 'app'
		}
	
		$NowDate = date("Y-m-d H:i:s"); //取得当前时间	
		$query  = " INSERT INTO friendly ";
		$query .= " (time, home_id, away_id, status) ";
		$query .= " VALUES('$NowDate', '$home_id', '$away_id', '$status') ";
		
		$rs = &$db->Execute($query);

		if (!$rs) {
			$error_message = "Database error.";
			require ("$document_root/page/system/error.php");
			// back to the page "friendly_arrange.php"
			goBackInTime(2500, -1);			
		}
		else if ($rs->RecordCount() < 0 ){
			$error_message = "Can not insert this record in the database.";
			require ("$document_root/page/system/error.php");
			// back to the page "friendly_arrange.php"
			goBackInTime(2500, -1);			
		}
		else {
			$query  = " SELECT id AS friendly_id from friendly ";
			$query .= " WHERE time='$NowDate' ";
			$query .= " AND home_id='$home_id' AND away_id='$away_id' ";
			$rs = &$db->Execute($query);
			$friendly_id = "";
			if (!$rs) {
				$error_message = "Database error.";
				require ("$document_root/page/system/error.php");
				// back to the page "friendly_arrange.php"
				goBackInTime(2500, -1);			
			}
			else if ($rs->RecordCount() > 0 ){
				$friendly_id = $rs->fields['friendly_id'];
			}
			
			$from_id = $s_primary_team_id;
			$from_name = $_SESSION['s_self_team_name'];
			$to_id = sql_quote($_POST['opponent_team_id']);
			$subject = "friendly match invitational letter";
			
			$tpl = new HTML_Template_ITX($TPL_PATCH); 
			$tpl->loadTemplatefile('mail_apply_friendly.tpl.php', true, true); 
			$tpl->setVariable("FRIENDLY_ID", $friendly_id) ; 
			$tpl->setVariable("FROM_ID", $from_id) ; 
			$tpl->setVariable("FROM_NAME", $from_name) ; 
			$content = $tpl->get(); 
			$type = 2; 
			$returnValue = sendMail($db, $from_id, $from_name, $to_id, $subject, $content, $type); 
			if ($returnValue == -1) {
				$welcome_info = "send mail error";
				require("$document_root/page/system/welcome.php");
				print "<a href='javascript:history.go(-1)'>back</a>";
				exit;
			} //if ($returnValue == -1)
			
			$error_message = "Arrange success.";
			require ("$document_root/page/system/error.php");
			// go back to the page "friendly_list.php"
			goToPageInTime(2, "/fmol/page/friendly/friendly_list.php");		
			
		}
	}	
}

?>
