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
$tpl->loadTemplatefile("put_to_transfer.tpl.php", true, true); 

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
			" p.transfer_flag " . 
			" FROM player p, team t " . 
			" WHERE p.player_id='%s' AND p.team_id=t.team_id " ,
			$player_id);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database error.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
}
else {
    if ($rs->RecordCount() > 0) {
		
		
		$tpl->setVariable("TRANSFER_FLAG", $rs->fields['transfer_flag']) ;
		$tpl->setVariable("TEAM_ID", $s_primary_team_id) ;
		$tpl->setVariable("TEAM_NAME", $s_team_name) ;
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
		$price = $worth * 0.4;
		$tpl->setVariable("PRICE", $price) ;
		
    }
}		


//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();


?>


