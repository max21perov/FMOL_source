<?php

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");


// update the div_id 
// when a season finishes and the team's div_id may be changed
require_once(DOCUMENT_ROOT . "/page/system/update_div_id.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile('friendly_arrange.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from POST and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
$opponent_team_id = sql_quote($_GET['opponent_team_id']);

$tpl->setVariable("OPPONENT_TEAM_ID_VALUE", $opponent_team_id) ;

// in order to set at least one variable of the template
$tpl->setVariable("EMPTY_VALUE", ' ') ; 

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();

?>

