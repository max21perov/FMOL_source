<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/mail.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);


//----------------------------------------------------------------------------	
// handle the mail
//----------------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] != "POST")    //check the page is request by method "POST"
{
	// go back to the page "mail.php"
	echo "<meta http-equiv='refresh' content='0;URL=/fmol/page/mail/mail.php'>";
	exit (0);
}

$myaction = sql_quote($_GET["myaction"]); 

if ("deleteMail" == $myaction) {
	performDeleteMail($db, DOCUMENT_ROOT);
}
else if ("replyMail" == $myaction) { 
	performReplyMail($db, DOCUMENT_ROOT);
}
else if ("deleteAllMails" == $myaction) {
	performDeleteAllMails($db, DOCUMENT_ROOT, $s_primary_team_id);

}
else if ("deleteSelectedMails" == $myaction) {
	performDeleteSelectedMails($db, DOCUMENT_ROOT);
	
}
else if ("sendMail" == $myaction) {
	performSendMail($db, DOCUMENT_ROOT);

	
}
else {
	// default: go back to the page "mail.php"
	echo "<meta http-equiv='refresh' content='0;URL=/fmol/page/mail/mail.php'>";
	exit (0);	
}


//----------------------------------------------------------------------------	
// ʹ�õ���(perform)����
//----------------------------------------------------------------------------

/**
 * delete the very mail (GET)
 */
function performDeleteMail($db, $document_root)
{
	$mail_id = sql_quote($_GET["mail_id"]);
	$next_page = sql_quote($_GET["next_page"]);
	$mail_filter = sql_quote($_POST["mail_filter"]);
	deleteOneMail($db, $document_root, $mail_id);
	
	// ���� $mail_filter ��ֵ�ض��� mail.php ҳ��
	redirectToMailPage($next_page, $mail_filter);
}

/**
 * reply the mail
 */
function performReplyMail($db, $document_root)
{
	$to_team_id = sql_quote($_POST["from_team_id"]); 
	$subject = sql_quote($_POST["subject"]);
	$subject = "Re: " . $subject;
	//$content = $_POST["content"];
	//$content = "\n\n\n\n" . "------------------\n" . $content;
	
	require_once(DOCUMENT_ROOT . "/page/mail/send_mail_reply.php");
	exit(0);
}

/**
 * delete all mails
 */
function performDeleteAllMails($db, $document_root, $team_id)
{
	$next_page = sql_quote($_GET["next_page"]);
	$mail_filter = sql_quote($_POST["mail_filter"]);  
	deleteAllMails($db, DOCUMENT_ROOT, $team_id);	
	
	// ���� $mail_filter ��ֵ�ض��� mail.php ҳ��
	redirectToMailPage($next_page, $mail_filter);
}

/**
 * delete selected mails
 */
function performDeleteSelectedMails($db, $document_root)
{
	$selected_mail_ids = $_POST["checkbox"]; 
	$next_page = sql_quote($_GET["next_page"]);
	$mail_filter = sql_quote($_POST["mail_filter"]);
		
	deleteSelectedMails($db, DOCUMENT_ROOT, $selected_mail_ids);
	
	// ���� $mail_filter ��ֵ�ض��� mail.php ҳ��
	redirectToMailPage($next_page, $mail_filter);	
}

/**
 * send mail
 */
function performSendMail($db, $document_root)
{
	$next_page = sql_quote($_GET["next_page"]);
	$mail_filter = sql_quote($_POST["mail_filter"]);
	$from_id 	= sql_quote($_SESSION['s_primary_team_id']);
	$from_name 	= sql_quote($_SESSION['s_self_team_name']);
    $to_id 		= sql_quote($_POST['to_team_id']);
	$subject 	= sql_quote($_POST['subject']);
	$content 	= sql_quote($_POST['content']);
	$type 		= 2;
	sendOneMail($db, DOCUMENT_ROOT, $from_id, $from_name, $to_id, $subject, $content, $type);
	
	// ���� $mail_filter ��ֵ�ض��� mail.php ҳ��
	redirectToMailPage($next_page, $mail_filter);		
}


//----------------------------------------------------------------------------	
// ʹ�õ��ĺ���
//----------------------------------------------------------------------------
/**
  * ɾ��ָ�����ʼ�
  **/
function deleteOneMail($db, $document_root, $mail_id) 
{
	$query = sprintf(
				" UPDATE mail SET status='2' " .
				" WHERE id='%s' " ,
				$mail_id);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");
	}
	else if ($rs->RecordCount() < 0 ){
		$error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error.php");
	}
	else {
		$error_message = "delete mail success.";
		require_once ("$document_root/page/system/error.php");
	}	
}

/**
  * ɾ�������ʼ�
 **/
function deleteAllMails($db, $document_root, $team_id)
{
	$query = sprintf(
				" UPDATE mail SET status='2' " .
				" WHERE to_id='%s' " ,
				$team_id);

	$rs = &$db->Execute($query);
	
	if (!$rs) {
		$error_message = "Database error.";
		require ("$document_root/page/system/error.php");
	}
	else if ($rs->RecordCount() < 0 ){
		$error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error.php");
	}
	else {
		$error_message = "delete mail success.";
		require_once ("$document_root/page/system/error.php");
	}	
}

/**
  * ɾ���Ѿ�ѡ����ʼ�
 **/
function deleteSelectedMails($db, $document_root, $selected_mail_ids)
{
		$success_flag = true;
		
		$db->BeginTrans();
		foreach ($selected_mail_ids as $mail_id) {
			$query = sprintf(
						" UPDATE mail SET status='2' " .
						" WHERE id='%s' " ,
						$mail_id);
			$rs = &$db->Execute($query);
	
			if (!$rs) {
				$db->RollbackTrans();
				$error_message = "Database error.";
				require ("$document_root/page/system/error.php");
				$success_flag = false;
				break;
			}
			else if ($rs->RecordCount() < 0 ){
				$db->RollbackTrans();
				$error_message = "There is not this right record in the database.";
				require ("$document_root/page/system/error.php");
				$success_flag = false;
				break;
			}
		}
		if ($success_flag == true) {
			$db->CommitTrans();
			$error_message = "delete mail success.";
			require_once ("$document_root/page/system/error.php");
		}
}

/**
  * ���� $mail_filter ��ֵ�ض��� mail.php ҳ��
 **/
function redirectToMailPage($next_page, $mail_filter)
{
	// go back to the page "mail.php"
	if ($mail_filter != "") {
		echo "<meta http-equiv='refresh' content='2;URL=/fmol/page/mail/mail.php?next_page=$next_page&mail_filter=$mail_filter'>";
		exit(0);
	}
	else {
		echo "<meta http-equiv='refresh' content='2;URL=/fmol/page/mail/mail.php'>";
		exit(0);
	}
}

/**
 * �����ʼ�
 **/
function sendOneMail($db, $document_root, $from_id, $from_name, $to_id, $subject, $content, $type)
{
	$returnValue = sendMail($db, $from_id, $from_name, $to_id, $subject, $content, $type); 
									  
    if ($returnValue < 0) {
	    $error_message = "send mail error";
		require_once ("$document_root/page/system/error.php");
		
		echo "<meta http-equiv='refresh' content='2;URL=javascript:history.go(-1)'>";
		exit(0);
	}
	else {
		$error_message = "Message sent success";
		require_once ("$document_root/page/system/error.php");
		
		echo "<meta http-equiv='refresh' content='2;URL=javascript:history.go(-2)'>";
		exit(0);
	}
}

?>

