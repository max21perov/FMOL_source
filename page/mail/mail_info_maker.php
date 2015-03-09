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
$tpl->loadTemplatefile("mail_info.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$mail_id = sql_quote($_GET['mail_id']); 

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * update the mail status
 */
// there is some problem in this place

$query = sprintf(
			" UPDATE mail set status='1' " .
			" WHERE id='%s' and status='0' " ,
			$mail_id);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database error.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit (0);
}

else if ($rs->RecordCount() < 0 ){
	$error_message = "There is not this right record in the database.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit (0);
}


/**
 * show the mail content
 */
$query = sprintf(
			" SELECT from_id, subject, content, time " .
			" FROM mail " .
			" WHERE id='%s' " ,
			$mail_id);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database Error!"; // $db->ErrorMsg();
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit (0);
}
else {
    if ($rs->RecordCount() > 0) {
		$time = $rs->fields['time'];
		$subject = $rs->fields['subject'];
		$content = $rs->fields['content'];
		
		$tpl->setVariable("MAIL_ID", $mail_id) ;
	    $tpl->setVariable("FROM_TEAM_ID", $rs->fields['from_id']) ;
		$tpl->setVariable("CUR_MAIL_TIME", $time) ;
		$tpl->setVariable("MAIL_SUBJECT", $subject) ;
		$tpl->setVariable("MAIL_CONTENT", $content) ;
    }
}



// print the output
$tpl->show();




?>

