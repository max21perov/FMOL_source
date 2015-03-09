<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("mail_main.tpl.php", true, true); 
// pager
$tpl_pager = new HTML_Template_ITX($TPL_PATCH); 
$tpl_pager->loadTemplatefile('pager.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']); 

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$mail_id = sql_quote($_GET["mail_id"]); 
$mail_filter = sql_quote($_GET["mail_filter"]); 

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * pager
 */
$num_of_rows_per_page = 10;  // 每页显示的记录数
$curr_page = "";
if (isset($_GET['next_page']))
        $curr_page = sql_quote($_GET['next_page']);
if ($curr_page == "") $curr_page = 1; // at first page


/**
 * show the mail count
 */
$query = sprintf(
			" SELECT count(1) as count " . 
			" FROM mail " . 
			" WHERE to_id='%s' AND status<>'2' " , 
			$s_primary_team_id);
			
if ($mail_filter != "")
	$query .= sprintf(" AND type='%s' ", $mail_filter);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database Error!"; // $db->ErrorMsg();
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit;
}
else {
   if ($rs->RecordCount() > 0 ){

		$tpl->setVariable("MAIL_COUNT", $rs->fields['count']) ;
		$count = intval($rs->fields['count']);
//		$height = 0;
//		if ($count == 0) {
//			//$height = 100;
//		}
//		else if ($count > 8) {
//			$height = 200;
//		}
//		else {
//			$height = 21 * $count + 15;
//		}
//		$tpl->setVariable("MAIL_LIST_DIV_HEIGHT", $height) ;
		
    }
}	
	
/**
 * show the simple mail list
 */
$query  = " SELECT id AS mail_id, from_id, from_name, ";
$query .= " subject, time AS full_time, date_format( time, '%a %c.%e %H:%i' ) AS time, status, type ";
$query .= " FROM mail ";
$query .= sprintf(" WHERE to_id='%s' AND status<>'2' ", $s_primary_team_id);

if ($mail_filter != "")
	$query .= sprintf(" AND type='%s' ", $mail_filter);
$query .= " ORDER BY full_time DESC ";

$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page);

if (!$rs) {
	$error_message = "Database Error!"; // $db->ErrorMsg();
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit;
}
else {
    $index = 1;
    for (; !$rs->EOF; $rs->MoveNext(), $index++) {
		
		$tpl->setCurrentBlock("mail") ;
		if ($index == 1)
			$tpl->setVariable("MAIL_LIST_SEPARATOR", 'none') ;
		$tpl->setVariable("MAIL_TR_CLASS", 'gSGRowOdd');
		
		
		// set the status img
		if ($rs->fields['status'] == '0') {
			$tpl->setVariable("STATUS_IMG", "mail_new.gif") ;
		}
		else { 
			$tpl->setVariable("STATUS_IMG", "mail_opened.gif") ;
		}
		$full_from_name = $rs->fields['from_name'];
		$full_subject = $rs->fields['subject'];
		$short_from_name = (strlen($full_from_name)>10) ? (substr($full_from_name, 0, 8)."...") : $full_from_name;
		$short_subject = (strlen($full_subject)>33) ? (substr($full_subject, 0, 30)."...") : $full_subject;
		$tpl->setVariable("FULL_FROM_NAME", $full_from_name) ;
		$tpl->setVariable("FULL_SUBJECT", $full_subject) ;
		$tpl->setVariable("SHORT_FROM_NAME", $short_from_name) ;
		$tpl->setVariable("SHORT_SUBJECT", $short_subject) ;
		$tpl->setVariable("FULL_DATE", $rs->fields['full_time']) ;
		$tpl->setVariable("DATE", $rs->fields['time']) ;
		$tpl->setVariable("MAIL_ID", $rs->fields['mail_id']) ;
		$tpl->setVariable("CHECK_BOX_ID", $index) ;
		$tpl->setVariable("CHECK_BOX_VALUE", $rs->fields['mail_id']) ;
		$tpl->setVariable("NEXT_PAGE", $rs->AbsolutePage()) ;   // 对翻页有用
		$tpl->setVariable("MAIL_FILTER", $mail_filter) ;   // 对翻页有用
		
		$tpl->parseCurrentBlock("mail") ;
		
    }
}

/**
 * handle the filter of mail type
 */
if ($mail_filter != "") {
	$mail_filter = intval($mail_filter);
	
	switch($mail_filter) {
	case 0: 
		$tpl->setVariable("SYSTEM_NEWS_SELECTED", "selected") ;
		break;
	case 1:
		$tpl->setVariable("GAME_NEWS_SELECTED", "selected") ;
		break;
	case 2:
		$tpl->setVariable("USER_MESSAGES_SELECTED", "selected") ;
		break;
	default:
		$tpl->setVariable("ALL_NEWS_SELECTED", "selected") ;
		break;
	}
	
}

if ($mail_id != "") {
	$tpl->setVariable("LOCATION", "/fmol/page/mail/mail_info_maker.php?mail_id=$mail_id") ;
}
else {
	$tpl->setVariable("LOCATION", "/fmol/page/mail/welcome_to_mailbox.php") ;
}



/**
 * pager
 */
$tpl_pager->setVariable("FORM_ACTION", "/fmol/page/mail/mail.php") ;
$tpl_pager->setVariable("FILTER_NAME", "mail_filter"); 
$tpl_pager->setVariable("FILTER_VALUE", $mail_filter);
$tpl_pager->setVariable("CURRENT_PAGE_NUM", $rs->AbsolutePage()) ;
$tpl_pager->setVariable("TOTAL_PAGE_NUM", ($rs->LastPageNo(false)=="-1") ? "1" : $rs->LastPageNo(false)) ;
$tpl_pager->setVariable("TOTAL_RECORD_NUM", $rs->MaxRecordCount()) ;
// pre page
if (!$rs->AtFirstPage()) {
	$tpl_pager->setVariable("PRE_PAGE_URL", "<a href=$PHPSELF?next_page=". ($rs->AbsolutePage() - 1). "&mail_filter=$mail_filter>pre</a>") ;
}
else if ($rs->AtFirstPage()) {
	$tpl_pager->setVariable("PRE_PAGE_URL", "") ;
}
// next page
if (!$rs->AtLastPage()) {
	$tpl_pager->setVariable("NEXT_PAGE_URL", "<a href=$PHPSELF?next_page=". ($rs->AbsolutePage() + 1). "&mail_filter=$mail_filter>next</a>") ;
}
else if ($rs->AtLastPage()) {
	$tpl_pager->setVariable("NEXT_PAGE_URL", "") ;
}

$tpl->setVariable("PAGER_TOOLBAR", $tpl_pager->get());


//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();


?>

