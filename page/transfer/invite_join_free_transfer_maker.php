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
$tpl->loadTemplatefile("invite_join_free_transfer.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']); 


//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$player_id = sql_quote($_GET["player_id"]);


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * get the player info
 */
$query = sprintf(
			" SELECT p.custom_given_name AS given_name, p.custom_family_name AS family_name, p.highest_tsp, " .
			" p.age, p.player_value, p.player_or_gk " . 
			" FROM player p " . 
			" WHERE p.player_id='%s' " ,
			$player_id);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database error.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit (0);
}
else {
    if ($rs->RecordCount() > 0) {
		
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
		
		
		
		$full_salary = doubleval($rs->fields['player_value'])*0.2;
		$salary_percent = 1.0;
		
		$contract_salary = $full_salary * $salary_percent;
		$tpl->setVariable("FULL_SALARY", $full_salary);
		$tpl->setVariable("CONTRACT_SALARY", $contract_salary);
		$tpl->setVariable("SALARY_PERCENT", ($salary_percent * 100) . "%");
		
		
		// set return_page_url
		$player_or_gk = $rs->fields['player_or_gk'];
		$tpl->setVariable("RETURN_PAGE_URL", "/fmol/page/transfer/contract_being_full.php");
				
    }
}		






//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();


?>


