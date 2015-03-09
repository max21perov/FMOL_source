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
$tpl->loadTemplatefile("give_price.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']); 
$s_team_name = sql_quote($_SESSION['s_team_name']);

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * get the player info
 */
$query = sprintf(
			" SELECT p.team_id, t.name AS team_name, " . 
			" p.custom_given_name AS given_name, p.custom_family_name AS family_name, p.highest_tsp, " .
			" p.age, p.player_value " . 
			" FROM player p, team t " . 
			" WHERE p.player_id='%s' AND p.team_id=t.team_id " ,
			$player_id);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database error.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit (0);
}
else {
    if ($rs->RecordCount() > 0) {
		
		$tpl->setVariable("TEAM_ID", $s_primary_team_id) ;
		$tpl->setVariable("TEAM_NAME", $s_team_name) ;
		$tpl->setVariable("OWNER_TEAM_ID", $rs->fields['team_id']) ;
		$tpl->setVariable("PLAYER_ID", $player_id) ;
		
		$full_name = "";
		if ($rs->fields['given_name'] == "") {
			$full_name = $rs->fields['family_name'];
		}
		else {
			$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
		}
		$tpl->setVariable("PLAYER_NAME", $full_name) ;
		
		$h_tsp = doubleval($rs->fields['highest_tsp']);
		$worth = $h_tsp * 1000000;
		$tpl->setVariable("WORTH", $worth) ;
		$tpl->setVariable("PRICE", intval($worth * 0.4)) ;
		
		// contract 
		$age = intval($rs->fields['age']);
		if ($age < 28) {
			$tpl->setCurrentBlock("contract_seasons_select") ;
	
			$tpl->setVariable("OPTION_VALUE", "2");
			$tpl->setVariable("OPTION_TEXT", "2");
			$tpl->parseCurrentBlock("contract_seasons_select") ;
			
			
			$tpl->setCurrentBlock("contract_seasons_select") ;
	
			$tpl->setVariable("OPTION_VALUE", "3");
			$tpl->setVariable("OPTION_TEXT", "3");
			$tpl->parseCurrentBlock("contract_seasons_select") ;
		}
		else {
			$tpl->setCurrentBlock("contract_seasons_select") ;
	
			$tpl->setVariable("OPTION_VALUE", "2");
			$tpl->setVariable("OPTION_TEXT", "2");
			$tpl->parseCurrentBlock("contract_seasons_select") ;
		}
		
		$tpl->setVariable("CONTRACT_SALARY", (doubleval($rs->fields['player_value'])*0.2) );
		
    }
}		

/**
 * get the start price from transfer_list
 */
$query = sprintf(
			" SELECT start_price, start_price_percent " . 
			" FROM transfer_list " . 
			" WHERE player_id='%s' " ,
			$player_id);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database error.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit (0);
}
else {
    if ($rs->RecordCount() > 0) {
		
		$tpl->setVariable("START_PRICE", $rs->fields['start_price']) ;
		$tpl->setVariable("START_PRICE_PERCENT", ($rs->fields['start_price_percent'] * 100)) ;
		
		
    }
}		

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();


?>


