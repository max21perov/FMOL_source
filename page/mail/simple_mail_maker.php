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
$tpl->loadTemplatefile("simple_mail.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']); 

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * show the simple mail list
 */
$query = sprintf(
			" SELECT id AS mail_id, from_id, from_name, " . 
			"  subject, time, status, type " . 
			" FROM mail " . 
			" WHERE to_id='%s' AND status='0' " . 
			" ORDER BY time DESC " ,
			$s_primary_team_id);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database Error!"; // $db->ErrorMsg();
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
}
else {
    $index = 1; 
    for (; !$rs->EOF; $rs->MoveNext(), $index++) {

		$tpl->setCurrentBlock("mail") ;
		if ($index == 1)
			$tpl->setVariable("MAIL_LIST_SEPARATOR", 'none') ;
		$tpl->setVariable("MAIL_TR_CLASS", 'gSGRowOdd') ;
		/*
		if ($index % 2 != 0 )
			$tpl->setVariable("MAIL_TR_CLASS", 'gSGRowEven') ;
		else 
			$tpl->setVariable("MAIL_TR_CLASS", 'gSGRowOdd') ;
			*/
		
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
		$tpl->setVariable("MAIL_ID", $rs->fields['mail_id']) ;
		$tpl->setVariable("FULL_FROM_NAME", $full_from_name) ;
		$tpl->setVariable("FULL_SUBJECT", $full_subject) ;
		$tpl->setVariable("SHORT_FROM_NAME", $short_from_name) ;
		$tpl->setVariable("SHORT_SUBJECT", $short_subject) ;
		$tpl->setVariable("DATE", $rs->fields['time']) ;
		$tpl->parseCurrentBlock("mail") ;
		
		
    }
}		



//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();


?>

