<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/mail.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");


if($_SERVER["REQUEST_METHOD"] != "POST")    //check the page is request by method "POST"
{	
	// go back to the page "transfer_market.php"
	goToPageInTime(0, "/fmol/page/transfer/transfer_market.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("putToTransfer" == $myaction) {
	performPutToTransfer($db, DOCUMENT_ROOT);
}
else if ("givePrice" == $myaction) {
	performGivePrice($db, DOCUMENT_ROOT);
}
else if ("askPrice" == $myaction) {
	performAskPrice($db, DOCUMENT_ROOT, $TPL_PATCH);
}
else if ("acceptGivePriceByMail" == $myaction) {
	performAcceptGivePriceByMail($db, DOCUMENT_ROOT, $TPL_PATCH);
}
else if ("giveAnotherPriceByMail" == $myaction) {
	performGiveAnotherPriceByMail($db, DOCUMENT_ROOT, $TPL_PATCH);
}
else if ("declineGivePriceByMail" == $myaction) {
	performDeclineGivePriceByMail($db, DOCUMENT_ROOT, $TPL_PATCH);
}
else if ("acceptThePriceByMail" == $myaction) {
	performAcceptThePriceByMail($db, DOCUMENT_ROOT, $TPL_PATCH);
}
else if ("declineThePriceByMail" == $myaction) {
	performDeclineThePriceByMail($db, DOCUMENT_ROOT, $TPL_PATCH);
}
else if ("replyPriceByMail" == $myaction) {
	performReplyPriceByMail($db, DOCUMENT_ROOT, $TPL_PATCH);
}
else {
	goToPageInTime(0, "/fmol/page/transfer/transfer_market.php");
}

//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
/**
 * 将球员挂牌
 */
function performPutToTransfer($db, $document_root)
{
	$club_id 	= sql_quote($_SESSION["s_primary_club_id"]);
	$team_id 	= sql_quote($_SESSION["s_primary_team_id"]);
	$player_id 	= sql_quote($_POST["player_id"]);
	$price		= sql_quote($_POST["price"]);
	$start_price_percent = sql_quote($_POST["start_price_percent"]);
	$start_time = date("Y-m-d H:i:s");
	
	// 判断该球员是否能被挂牌
	$returnValue = canPutToTransfer($db, $club_id, $team_id, $player_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$db->BeginTrans();
	
	$returnValue = insertIntoTransfer_buffer($db, $team_id, $player_id, $start_time, $price, $start_price_percent);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = reduceActionPoint($db, $club_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = updateIntendingPlayerNum($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// commit
	$db->CommitTrans();
	$error_message = "Put to transfer market Success.";
	require ("$document_root/page/system/error.php");
	 
	// go back to the page "transfer_market.php"
	goToPageInTime(2, "/fmol/page/transfer/transfer_market.php");
}

	/**
	 * 对转会市场中的球员竞价
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performGivePrice($db, $document_root)
{
	// 在这里根据player是否在挂牌或者在转会来决定是“竞价”还是“询价”
	// ？？？
	$club_id = sql_quote($_SESSION["s_primary_club_id"]);
	$team_id = sql_quote($_SESSION["s_primary_team_id"]);
	$player_id = sql_quote($_POST["player_id"]);
	$price = sql_quote($_POST["price"]);
	$price_percent = sql_quote($_POST["price_percent"]);
	$contract_seasons = sql_quote($_POST["contract_seasons"]);
	$time = date("Y-m-d H:i:s");
	
	// 判断该用户是否能出价
	$returnValue = canGivePrice($db, $club_id, $team_id, $price);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$db->BeginTrans();
	
	$returnValue = updateTheBids($db, $team_id, $player_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue . "2";
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = insertIntoTransfer_detail($db, $team_id, $player_id, $time, $price, $price_percent, $contract_seasons);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue . "3";
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = reduceActionPoint($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue . "4";
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = putToHotList($db, $team_id, $player_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue . "5";
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// commit
	$db->CommitTrans();
	
	$error_message = "give price Success.";
	require ("$document_root/page/system/error.php"); 
	
	// go back to the page "transfer_market.php"
	goToPageInTime(2, "/fmol/page/transfer/transfer_market.php");
}

	/**
	 * 对不在转会市场中的球员“问价”或者“出价”
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performAskPrice($db, $document_root, $TPL_PATCH)
{
	$action_type = sql_quote($_POST["action_type"]);
	$price = sql_quote($_POST["price"]);
	$worth = sql_quote($_POST["worth"]);
	$owner_team_id = sql_quote($_POST["owner_team_id"]);
	$player_id = sql_quote($_POST["player_id"]);
	$player_name = sql_quote($_POST["player_name"]);
	$apply_team_name = sql_quote($_POST["team_name"]);
	$apply_team_id = sql_quote($_SESSION["s_primary_team_id"]);
	$apply_club_id = sql_quote($_SESSION["s_primary_club_id"]);
	$Mail_subject = "";
	$mail_content = "";
	
	// 判断该用户是否能出价
	$returnValue = canGivePriceByMail($db, $apply_club_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
			
		goBackInTime(3500, -1); 
	}
	
	if ($action_type == "0") { 
		// $action_type == "0" 表示 “问价”
		$Mail_subject = "$apply_team_name asked for the price of player: $player_name";
		$mail_content = getMailContentOfAskPrice($db,  $TPL_PATCH, 
					$apply_team_name, $apply_team_id, $player_name, $player_id, $worth);
	}
	else {
		// $action_type == "1" 表示 “出价”
		$Mail_subject = "$apply_team_name give a price for the player: $player_name";
		$mail_content = getMailContentOfGivePrice($db, $TPL_PATCH, 
				 $apply_team_name, $apply_team_id, $player_name, $player_id, $price, $worth);
	}
	
	$from_id = $apply_team_id;
	$from_name = $apply_team_name;
	$to_id = $owner_team_id;
	$type = 1;  // GAME NEWS
	$small_type = 1; // GAME NEWS-TRANSFER - HAVE TO HANDLE
	
	$db->BeginTrans();
	
	$returnValue = insertIntoMail_buffer2($db, $player_id, $from_id, $from_name, $to_id, $Mail_subject, $mail_content, $type, $small_type);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = reduceActionPoint($db, $apply_team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = putToHotList($db, $apply_team_id, $player_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// commit
	$db->CommitTrans();
	
	$error_message = "Ask price Success.";
	require ("$document_root/page/system/error.php"); 
	
	// go back to the page "transfer_hotlist.php"
	goToPageInTime(2, "/fmol/page/transfer/transfer_hotlist.php");
}

	/**
	 * 在邮件中同意返回某一个球员的价格
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 * @param [TPL_PATCH]		TPL_PATCH
	 *
	 * @return return true or false
	 */	
function performAcceptGivePriceByMail($db, $document_root, $TPL_PATCH)
{
	$club_id = sql_quote($_SESSION['s_primary_club_id']);
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	// 判断玩家是否有足够的AP
	$returnValue = haveEnoughAP($db, $club_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
			
		goBackInTime(3500, -1); 
	}
	
	$mail_id 			= sql_quote($_POST["mail_id"]);
	$subject 			= sql_quote($_POST["subject"]);
	$apply_team_id 		= sql_quote($_POST["apply_team_id"]);
	$player_id 			= sql_quote($_POST["player_id"]);
	$player_name 		= sql_quote($_POST["player_name"]);
	$worth 				= sql_quote($_POST["worth"]);
	$given_price 		= 0;
	$have_given_price 	= sql_quote($_POST["have_given_price"]);
	
	$pageURL  = "/fmol/page/transfer/reply_price.php?mail_id=$mail_id";
	$pageURL .= "&subject=$subject";
	$pageURL .= "&apply_team_id=$apply_team_id";
	$pageURL .= "&player_id=$player_id";
	$pageURL .= "&player_name=$player_name";
	$pageURL .= "&worth=$worth";
	$pageURL .= "&given_price=$given_price";
	$pageURL .= "&have_given_price=$have_given_price";
	goToPageInTime(0, $pageURL);
}

	/**
	 * 在邮件中对某一个球员进行还价
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 * @param [TPL_PATCH]		TPL_PATCH
	 *
	 * @return return true or false
	 */	
function performGiveAnotherPriceByMail($db, $document_root, $TPL_PATCH)
{
	$club_id = sql_quote($_SESSION['s_primary_club_id']);
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	// 判断玩家是否有足够的AP
	$returnValue = haveEnoughAP($db, $club_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
			
		goBackInTime(3500, -1); 
	}
	
	$mail_id 			= sql_quote($_POST["mail_id"]);
	$subject 			= sql_quote($_POST["subject"]);
	$apply_team_id 		= sql_quote($_POST["apply_team_id"]);
	$player_id 			= sql_quote($_POST["player_id"]);
	$player_name 		= sql_quote($_POST["player_name"]);
	$worth 				= sql_quote($_POST["worth"]);
	$given_price 		= sql_quote($_POST["price"]);
	$have_given_price 	= sql_quote($_POST["have_given_price"]);
	
	$pageURL  = "/fmol/page/transfer/reply_price.php?mail_id=$mail_id";
	$pageURL .= "&subject=$subject";
	$pageURL .= "&apply_team_id=$apply_team_id";
	$pageURL .= "&player_id=$player_id";
	$pageURL .= "&player_name=$player_name";
	$pageURL .= "&worth=$worth";
	$pageURL .= "&given_price=$given_price";
	$pageURL .= "&have_given_price=$have_given_price";
	goToPageInTime(0, $pageURL);
}
	/**
	 * 在邮件中拒绝返回某一个球员的价格
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 * @param [TPL_PATCH]		TPL_PATCH
	 *
	 * @return return true or false
	 */	
function performDeclineGivePriceByMail($db, $document_root, $TPL_PATCH)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$club_id = sql_quote($_SESSION['s_primary_club_id']);
	// 判断玩家是否有足够的AP
	$returnValue = haveEnoughAP($db, $club_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
			
		goBackInTime(3500, -1); 
	}
	
	// BeginTrans
	$db->BeginTrans();
	
	$mail_id = sql_quote($_POST["mail_id"]);
	$player_name = sql_quote($_POST["player_name"]);
	$mail_content = "You have declined to give price of player: $player_name!";
	// 更新邮件的内容
	$returnValue = updateMailContent($db, $mail_id, $mail_content);
	if ($returnValue != "0") {
		$db->RollbackTrans();
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$from_id 	= sql_quote($_SESSION['s_primary_team_id']);
	$from_name 	= sql_quote($_SESSION['s_self_team_name']);
	$to_id 		= sql_quote($_POST["apply_team_id"]);
	$o_subject 	= sql_quote($_POST['subject']);
	$subject    = "Re: " . $o_subject;
	$content 	= "<span class=\"BlackText\"><a href=\"/fmol/page/info/club_info.php?team_id=$from_id\" target=\"_parent\">$from_name</a></span>";
	$content   .= " has declined to give the price of player: $player_name.";
	$type 		= 1;  // 1 - GAME NEWS;
	$small_type = 0;  // 0 - GAME NEWS-TRANSFER - DO NOT HAVE TO HANDLE; 
	$returnValue = insertIntoMail_buffer($db, $from_id, $from_name, $to_id, $subject, $content, $type, $small_type);
	if ($returnValue != "0") {
		$db->RollbackTrans();
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// 将AP - 5
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$returnValue = reduceActionPoint($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// commit
	$db->CommitTrans();
	$error_message = "Decline success.";
	require ("$document_root/page/system/error.php");
	
	// go back to the page "mail.php"
	goToPageInTime(2, "/fmol/page/mail/mail.php?mail_id=$mail_id");
}

	/**
	 * 在邮件中接收买方对某一个球员的出价（报价）
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 * @param [TPL_PATCH]		TPL_PATCH
	 *
	 * @return return true or false
	 */	
function performAcceptThePriceByMail($db, $document_root, $TPL_PATCH)
{
	$club_id = sql_quote($_SESSION['s_primary_club_id']);
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	// 判断玩家是否有足够的AP
	$returnValue = haveEnoughAP($db, $club_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
			
		goBackInTime(3500, -1); 
	}
	
	// BeginTrans
	$db->BeginTrans();
	
	$mail_id = sql_quote($_POST["mail_id"]);
	$player_name = sql_quote($_POST["player_name"]);
	$mail_content = "You have accepted the given price of player: $player_name!";
	// 更新邮件的内容
	$returnValue = updateMailContent($db, $mail_id, $mail_content);
	if ($returnValue != "0") {
		$db->RollbackTrans();
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// 将球员放入transfer_buffer中，“下次更新”时将判断球员交易是否能成功
	$price 			= sql_quote($_POST["price"]);
	$player_id 		= sql_quote($_POST["player_id"]);
	$apply_team_id 	= sql_quote($_POST["apply_team_id"]);
	$price_percent 	= "";
	$start_time 	= date("Y-m-d H:i:s");
	$type	 		= 1;  // 1表示transfer_buffer的该纪录为问价制接受报价
	$returnValue = insertIntoTransfer_buffer($db, $apply_team_id, $player_id, $start_time, $price, $price_percent, $type);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// 发邮件告诉出价者，球员的母队已经同意了他的出价
	$from_id 		= sql_quote($_SESSION['s_primary_team_id']);
	$from_name 		= sql_quote($_SESSION['s_self_team_name']);
	$to_id 			= sql_quote($_POST["apply_team_id"]);
	$o_subject 		= sql_quote($_POST['subject']);
	$subject    	= "Re: " . $o_subject;
	$content 		= "<span class=\"BlackText\"><a href=\"/fmol/page/info/club_info.php?team_id=$from_id\" target=\"_parent\">$from_name</a></span>";
	$content   	   .= " has accepted your given price of player: $player_name.";
	$type 			= 1;  // 1 - GAME NEWS;
	$small_type 	= 0;  // 0 - GAME NEWS-TRANSFER - DO NOT HAVE TO HANDLE; 
	$returnValue = insertIntoMail_buffer($db, $from_id, $from_name, $to_id, $subject, $content, $type, $small_type);
	if ($returnValue != "0") {
		$db->RollbackTrans();
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// 将AP - 5
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$returnValue = reduceActionPoint($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// commit
	$db->CommitTrans();
	$error_message = "Accept success.";
	require ("$document_root/page/system/error.php");
	
	// go back to the page "mail.php"
	goToPageInTime(2, "/fmol/page/mail/mail.php?mail_id=$mail_id");
}

	/**
	 * 在邮件中,由于不满意买方对某一个球员的出价（报价），所以给买方还一个价
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 * @param [TPL_PATCH]		TPL_PATCH
	 *
	 * @return return true or false
	 */	
function performReplyPriceByMail($db, $document_root, $TPL_PATCH)
{
	$club_id = sql_quote($_SESSION['s_primary_club_id']);
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	// 判断玩家是否有足够的AP
	$returnValue = haveEnoughAP($db, $club_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
			
		goBackInTime(3500, -1); 
	}
	
	// BeginTrans
	$db->BeginTrans();
	
	$mail_id 		= sql_quote($_POST["mail_id"]);
	$player_name 	= sql_quote($_POST["player_name"]);
	$reply_price 	= sql_quote($_POST["reply_price"]);
	$mail_content 	= "You have reply the price of player: $player_name!<br>";
	$mail_content  .= "Your replied price is: $reply_price.";
	// 更新邮件的内容
	$returnValue = updateMailContent($db, $mail_id, $mail_content);
	if ($returnValue != "0") {
		$db->RollbackTrans();
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$from_id 		= sql_quote($_SESSION['s_primary_team_id']);
	$from_name 		= sql_quote($_SESSION['s_self_team_name']);
	$to_id 			= sql_quote($_POST["apply_team_id"]);
	$reply_price 	= sql_quote($_POST["reply_price"]);
	$o_subject 		= sql_quote($_POST['subject']);
	$subject    	= "Re: " . $o_subject;
	$content 		= "<span class=\"BlackText\"><a href=\"/fmol/page/info/club_info.php?team_id=$from_id\" target=\"_parent\">$from_name</a></span>";
	$content   	   .= " has replied the price of player: $player_name.<br>";
	$content   	   .= " The replied price is: $reply_price.";
	$type 			= 1;  // 1 - GAME NEWS;
	$small_type 	= 0;  // 0 - GAME NEWS-TRANSFER - DO NOT HAVE TO HANDLE; 
	$returnValue = insertIntoMail_buffer($db, $from_id, $from_name, $to_id, $subject, $content, $type, $small_type);
	if ($returnValue != "0") {
		$db->RollbackTrans();
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// 将AP - 5
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$returnValue = reduceActionPoint($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// commit
	$db->CommitTrans();
	$error_message = "Reply success.";
	require ("$document_root/page/system/error.php");
	
	// go back to the page "mail.php"
	goToPageInTime(2, "/fmol/page/mail/mail.php?mail_id=$mail_id");
}

	/**
	 * 在邮件中，拒绝买方对某一个球员的出价（报价）
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 * @param [TPL_PATCH]		TPL_PATCH
	 *
	 * @return return true or false
	 */	
function performDeclineThePriceByMail($db, $document_root, $TPL_PATCH)
{
	$club_id = sql_quote($_SESSION['s_primary_club_id']);
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	// 判断玩家是否有足够的AP
	$returnValue = haveEnoughAP($db, $club_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
			
		goBackInTime(3500, -1); 
	}
	
	// BeginTrans
	//$db->BeginTrans();
	
	$mail_id 		= sql_quote($_POST["mail_id"]);
	$player_name	= sql_quote($_POST["player_name"]);
	$price 			= sql_quote($_POST["price"]);
	$mail_content 	= "You have declined to the given price of player: $player_name!<br>";
	$mail_content  .= "The given price is: $price!";
	// 更新邮件的内容
	$returnValue = updateMailContent($db, $mail_id, $mail_content);
	if ($returnValue != "0") {
		//$db->RollbackTrans();
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$from_id 	= sql_quote($_SESSION['s_primary_team_id']);
	$from_name 	= sql_quote($_SESSION['s_self_team_name']);
	$to_id 		= sql_quote($_POST["apply_team_id"]);
	$o_subject 	= sql_quote($_POST['subject']);
	$subject    = "Re: " . $o_subject;
	$content 	= "<span class=\"BlackText\"><a href=\"/fmol/page/info/club_info.php?team_id=$from_id\" target=\"_parent\">$from_name</a></span>";
	$content   .= " has declined to your given price of player: $player_name.";
	$content   .= " Your given price is: $price.";
	$type 		= 1;  // 1 - GAME NEWS;
	$small_type = 0;  // 0 - GAME NEWS-TRANSFER - DO NOT HAVE TO HANDLE; 
	$returnValue = insertIntoMail_buffer($db, $from_id, $from_name, $to_id, $subject, $content, $type, $small_type);
	if ($returnValue != "0") {
		//$db->RollbackTrans();
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// 将AP - 5
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$returnValue = reduceActionPoint($db, $team_id);
	if ($returnValue != "0") {
		//$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}

	
	// commit
	//$db->CommitTrans();
	$error_message = "Decline success.";
	require ("$document_root/page/system/error.php");
	
	// go back to the page "mail.php"
	goToPageInTime(2, "/fmol/page/mail/mail.php?mail_id=$mail_id");
}


//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------
	/**
	 * check whether the player can be put into transfer
	 *
	 * @param [db]						database
	 * @param [club_id]					club_id
	 * @param [team_id]					team_id
	 * @param [player_id]				player_id
	 *
	 * @return return 0, other error message
	 */	
function canPutToTransfer($db, $club_id, $team_id, $player_id)
{
	// 
	$query = sprintf("SELECT 1 FROM transfer_buffer where player_id='%s'", $player_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0 ){
		return "The player has been put to transfer.";  // 该球员已经挂牌了
	}
	
	// 
	$query  = sprintf("SELECT 1 FROM transfer_list where player_id='%s'", $player_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0 ) {
		return "The player has been put to transfer.";  // 该球员已经在转会市场中了
	}
/*	
	// 
	$query  = sprintf("SELECT intending_player_num FROM team where team_id='%s'", $team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}

	$intending_player_num = intval($rs->fields['intending_player_num']);
	if ($intending_player_num <= 18) {
		return "The intending_player_num must be more than 18.";
	}

	// 
	$query  = sprintf("SELECT activity_point_num FROM club where club_id='%s'", $club_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	
	$activity_point_num = intval($rs->fields['activity_point_num']);	
	if ($activity_point_num <= 5) {
		return "The activity_point_num is only $activity_point_num, it must be more than 5.";
	}
*/		
	return "0";
}

	/**
	 * insert a record into transfer_buffer
	 *
	 * @param [db]						database
	 * @param [team_id]					team_id
	 * @param [player_id]				player_id
	 * @param [start_time]				start_time	
	 * @param [price]					price	
	 * @param [start_price_percent]		start_price_percent	
	 * @param [type]					放在transfer_buffer中记录的类型，0-挂牌申请; 1-问价制接受报价	
	 *
	 * @return return 0, -1, -2
	 */	
function insertIntoTransfer_buffer($db, $team_id, $player_id, $start_time, $price, $start_price_percent, $type="0") 
{
	$query = sprintf(
				" INSERT INTO transfer_buffer (team_id, player_id, start_time, start_price, start_price_percent, type) " .
				" VALUES ('%s', '%s', '%s', '%s', '%s', '%s') " ,
				$team_id, $player_id, $start_time, $price, $start_price_percent, $type);
	
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
	 * insert a record into mail_buffer (have not the player_id)
	 *
	 * @param [db]					database
	 * @param [from_id]				from_id
	 * @param [from_name]			from_name
	 * @param [to_id]				to_id	
	 * @param [subject]				subject	
	 * @param [content]				content	
	 * @param [type]				type	
	 * @param [small_type]			small_type, default: 0 - GAME NEWS-TRANSFER - DO NOT HAVE TO HANDLE;	
	 *
	 * @return return "0", or other error msg
	 */	
function insertIntoMail_buffer($db, $from_id, $from_name, $to_id, $subject, $content, $type, $small_type="0")
{
	$query = sprintf(
				" INSERT INTO mail_buffer (from_id, from_name, to_id, subject, content, time, status, type, small_type) " .
				" VALUES ('%s', '%s', '%s', '%s', '%s', now(), '0', '%s', '%s') " ,
				$from_id, $from_name, $to_id, $subject, $content, $type, $small_type);
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
	 * insert a record into mail_buffer (have the player_id)
	 *
	 * @param [db]					database
	 * @param [from_id]				from_id
	 * @param [from_name]			from_name
	 * @param [to_id]				to_id	
	 * @param [subject]				subject	
	 * @param [content]				content	
	 * @param [type]				type	
	 * @param [small_type]			small_type	
	 *
	 * @return return "0", or other error msg
	 */	
function insertIntoMail_buffer2($db, $player_id, $from_id, $from_name, $to_id, $subject, $content, $type, $small_type="0")
{
	$query = sprintf(
				" INSERT INTO mail_buffer (from_id, from_name, to_id, subject, content, time, status, type, small_type, player_id) " .
				" VALUES ('%s', '%s', '%s', '%s', '%s', now(), '0', '%s', '%s', '%s') " ,
				$from_id, $from_name, $to_id, $subject, $content, $type, $small_type, $player_id);
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
	 * AP = AP - 5
	 *
	 * @param [db]						database
	 * @param [club_id]					club_id
	 *
	 * @return return 0, error msg
	 */	
function reduceActionPoint($db, $team_id)
{
	$query = sprintf(
				" UPDATE club c, team t SET " .
				" c.activity_point_num=c.activity_point_num-5 " .
				" WHERE c.club_id=t.club_id AND t.team_id='%s' " ,
				$team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	
	return "0";
}

	/**
	 * 重新计算球队的“预计最低人数”
	 *
	 * @param [db]						database
	 * @param [team_id]					team_id
	 *
	 * @return return "0", error msg
	 */	
function updateIntendingPlayerNum($db, $team_id)
{
	// 取球队中球员的总数
	$query  = sprintf(
				"SELECT count(1) AS player_num FROM player WHERE team_id='%s' " ,
				$team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	$player_num = intval($rs->fields['player_num']);
	
	// 取球队在 transfer_buffer 中的球员的总数
	$query  = sprintf(
				"SELECT count(1) AS num_in_Tbuffer FROM transfer_buffer WHERE team_id='%s'" ,
				$team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	$num_in_Tbuffer = intval($rs->fields['num_in_Tbuffer']);
	
	// 取球队在 transfer_list 中的球员的总数
	$query  = sprintf(
				"SELECT count(1) AS num_in_Tlist FROM transfer_list WHERE team_id='%s' " ,
				$team_id );
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	$num_in_Tlist = intval($rs->fields['num_in_Tlist']);
	
	// 计算球队的“预计最低人数”，并更新到team中
	$intending_player_num = $player_num - ($num_in_Tbuffer + $num_in_Tlist);

	$query  = sprintf(
				"UPDATE team SET intending_player_num='%s' WHERE team_id='%s' " ,
				$intending_player_num, $team_id);
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
	 * check whether the player can be put into transfer
	 *
	 * @param [db]						database
	 * @param [club_id]					club_id
	 * @param [team_id]					team_id
	 * @param [price]					the given price
	 *
	 * @return return 0, other error message
	 */	
function canGivePrice($db, $club_id, $team_id, $price)
{
	$query  = sprintf(
				" SELECT f.total_transfer_fee " . 
				" FROM finance f, team t " .
				" WHERE f.club_id=t.club_id and t.team_id='%s' " ,
				$team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	
	$total_transfer_fee = intval($rs->fields['total_transfer_fee']);
	if ($total_transfer_fee < intval($price)) {
		return "At present, your total transfer fee is not enough for your given price.";
	}
	
	// 
	$query  = sprintf(
				"SELECT activity_point_num FROM club where club_id='%s'" ,
				$club_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	
	$activity_point_num = intval($rs->fields['activity_point_num']);
	if ($activity_point_num <= 5) {
		return "The activity_point_num is only $activity_point_num, it must be more than 5.";
	}
	
	return "0";
}

	/**
	 * 检查买家是否能通过邮件出价
	 *
	 * @param [db]						database
	 * @param [club_id]					club_id
	 *
	 * @return return 0, other error message
	 */	
function canGivePriceByMail($db, $club_id)
{
	$query  = sprintf(
				"SELECT activity_point_num FROM club where club_id='%s'" ,
				$club_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	
	$activity_point_num = intval($rs->fields['activity_point_num']);
	if ($activity_point_num <= 5) {
		return "The activity_point_num is only $activity_point_num, it must be more than 5.";
	}
	
	return "0";
}


	/**
	 * 检查买家的AP是否足够
	 *
	 * @param [db]						database
	 * @param [club_id]					club_id
	 *
	 * @return return 0, other error message
	 */	
function haveEnoughAP($db, $club_id)
{
	$query  = sprintf(
				"SELECT activity_point_num FROM club where club_id='%s' " ,
				$club_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	
	$activity_point_num = intval($rs->fields['activity_point_num']);
	if ($activity_point_num <= 5) {
		return "The activity_point_num is only $activity_point_num, it must be more than 5.";
	}
	
	return "0";
}

	/**
	 * update the "bids" in the transfer_list
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [player_id]			player_id
	 *
	 * @return return 0, -1, -2
	 */	
function updateTheBids($db, $team_id, $player_id) 
{
	$query = sprintf(
				" SELECT 1 FROM transfer_detail WHERE player_id='%s' and team_id='%s' " ,
				$player_id, $team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() == 0){ 
		// update the bids=bids+1
		$query = sprintf(
					" UPDATE transfer_list SET bids=bids+1 WHERE player_id='%s' " ,
					$player_id);
		$rs = &$db->Execute($query);
		if (!$rs) {
			return "Database error.";
		}
		else if ($rs->RecordCount() < 0 ){
			return "There is not this right record in the database.";
		}
	}
	
	return "0";
}


	/**
	 * insert a record into transfer_detail
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [player_id]			player_id
	 * @param [time]				time	
	 * @param [price]				price	
	 *
	 * @return return 0, -1, -2
	 */	
function insertIntoTransfer_detail($db, $team_id, $player_id, $time, $price, $price_percent, $contract_seasons) 
{
	$query = sprintf(
				" INSERT INTO transfer_detail (team_id, player_id, time, price, price_percent, contract_seasons) " .
				" VALUES ('%s', '%s', '%s', '%s', '%s', '%s') " ,
				$team_id, $player_id, $time, $price, $price_percent, $contract_seasons);
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
	 * put the player into hot_list
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [player_id]			player_id
	 *
	 * @return return 0, -1, -2
	 */	
function putToHotList($db, $team_id, $player_id)
{
	$query = sprintf(
				" SELECT 1 FROM hot_list WHERE player_id='%s' and team_id='%s' " ,
				$player_id, $team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() == 0){ 
		// update the bids=bids+1
		$query = sprintf(
					" INSERT INTO hot_list ( team_id, player_id ) VALUES('%s', '%s') " ,
					$team_id, $player_id);
		$rs = &$db->Execute($query);
		if (!$rs) {
			return "Database error.";
		}
		else if ($rs->RecordCount() < 0 ){
			return "There is not this right record in the database.";
		}
	}
	
	return "0";
}

	/**
	 * "问价"的邮件内容
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [player_id]			player_id
	 *
	 * @return return the mail content of ask price
	 */	
function getMailContentOfAskPrice($db, $TPL_PATCH, 
			$apply_team_name, $apply_team_id, $player_name, $player_id, $worth) 
{
	$tpl = new HTML_Template_ITX($TPL_PATCH); 
	$tpl->loadTemplatefile('mail_ask_price.tpl.php', true, true); 
	
	$tpl->setVariable("APPLY_TEAM_ID", $apply_team_id) ; 
	$tpl->setVariable("APPLY_TEAM_NAME", $apply_team_name) ; 
	$tpl->setVariable("PLAYER_ID", $player_id) ; 
	$tpl->setVariable("PLAYER_NAME", $player_name) ; 
	$tpl->setVariable("WORTH", $worth) ; 
	
	$content = $tpl->get(); 
	
	return $content;
}

	/**
	 * "出价"的邮件内容
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [player_id]			player_id
	 *
	 * @return return the mail content of give price
	 */	
function getMailContentOfGivePrice($db, $TPL_PATCH, 
				 $apply_team_name, $apply_team_id, $player_name, $player_id, $price, $worth)
{
	$tpl = new HTML_Template_ITX($TPL_PATCH); 
	$tpl->loadTemplatefile('mail_give_price.tpl.php', true, true); 
	
	$tpl->setVariable("APPLY_TEAM_ID", $apply_team_id) ; 
	$tpl->setVariable("APPLY_TEAM_NAME", $apply_team_name) ; 
	$tpl->setVariable("PLAYER_ID", $player_id) ; 
	$tpl->setVariable("PLAYER_NAME", $player_name) ; 
	$tpl->setVariable("PRICE", $price) ; 
	$tpl->setVariable("WORTH", $worth) ; 
	
	$content = $tpl->get(); 
	
	return $content;		 	
}	



?>

