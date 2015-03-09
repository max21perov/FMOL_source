<?php

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------

$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile('reply_price.tpl.php', true, true); 

$mail_id 			= sql_quote($_GET["mail_id"]);
$subject 			= sql_quote($_GET["subject"]);
$apply_team_id 		= sql_quote($_GET["apply_team_id"]);
$player_id 			= sql_quote($_GET["player_id"]);
$player_name 		= sql_quote($_GET["player_name"]);
$worth 				= sql_quote($_GET["worth"]);
$given_price 		= sql_quote($_GET["given_price"]);
$have_given_price 	= sql_quote($_GET["have_given_price"]);

$tpl->setVariable("MAIL_ID", $mail_id) ; 
$tpl->setVariable("SUBJECT", $subject) ; 
$tpl->setVariable("APPLY_TEAM_ID", $apply_team_id) ; 
$tpl->setVariable("PLAYER_ID", $player_id) ; 
$tpl->setVariable("PLAYER_NAME", $player_name) ; 
$tpl->setVariable("GIVEN_PRICE", $given_price) ; 
$tpl->setVariable("WORTH", $worth) ; 
$tpl->setVariable("HAVE_GIVEN_PRICE", $have_given_price) ; 
$reply_price = intval(intval($worth) * 1.2);
$tpl->setVariable("REPLY_PRICE", $reply_price) ;
	
	
// print the output
$tpl->show();


?>


