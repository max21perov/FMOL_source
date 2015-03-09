<?php

session_start();

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once("tactics_functions.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile('adv_tactics_self.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$team_id = sql_quote($_GET['team_id']);


// get the can_tactics_id_1 from team_tactics
$p_tactics_id = getCur_tactics_id($db, $team_id);



$tpl->setVariable("RETURN_PAGE_URL", "/fmol/page/tactics/adv_tactics.php");

//----------------------------------------------------------------------------	
// require the component
//----------------------------------------------------------------------------
require_once("adv_tactics_maker_component.php");


//----------------------------------------------------------------------------	
// 自定义函数部分  
//----------------------------------------------------------------------------
/**
 * get cur_tactics_id from team_tactics
 *
 * @param [rs]			the result set
 * @param [team_id]		team_id
 *
 * @return  $p_tactics_id
 */	
function getCur_tactics_id($db, $team_id)
{
	$p_tactics_id = "0";
	
	// std_formation
	$query = sprintf( 
			 	" SELECT cur_tactics_id as p_tactics_id " .
			 	" FROM team_tactics " .
			 	" WHERE team_id='%s' " , 
			 	$team_id
			 	);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		$error_message = "Database error.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() < 0 ){
		$error_message = "Database error.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0) {
		$p_tactics_id = $rs->fields['p_tactics_id'];
	}
		
	return $p_tactics_id;
}


?>

