<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");



//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("coach_info.tpl.php", true, true); 


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	




					  
/**
 * show the player list
 */
// search all properties from player
$query = sprintf(
			" SELECT custom_given_name AS given_name, custom_family_name AS family_name, " . 
			" age, salary, " . 
			" gk_training, attacking, defending, fitness, tactics, technical, youth " . 
			" FROM coach " . 
			" WHERE coach_id='%s' " ,
			$coach_id);

$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database Error!"; // $db->ErrorMsg();
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit (0);
}
else {
    if ($rs->RecordCount() > 0) {
	
		// set the detail of player
		$given_name = $rs->fields['given_name'];
		$full_name = "";
		if ($given_name == "") {
			$full_name = $rs->fields['family_name'];
		}
		else {
			$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
		}
		$tpl->setVariable("COACH_NAME", $full_name);
		
		$tpl->setVariable("AGE", $rs->fields['age']);	
		
		$tpl->setVariable("SALARY", $rs->fields['salary']);
		
		// ability
		$tpl->setVariable("GK_TRAINING", $rs->fields['gk_training']);
		$tpl->setVariable("ATTACKING", $rs->fields['attacking']);
		$tpl->setVariable("DEFENDING", $rs->fields['defending']);
		$tpl->setVariable("FITNESS", $rs->fields['fitness']);
		$tpl->setVariable("TACTICS", $rs->fields['tactics']);
		$tpl->setVariable("TECHNICAL", $rs->fields['technical']);
		$tpl->setVariable("YOUTH", $rs->fields['youth']);
		
    }
}		



//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// set the SPACE in order to guarantee the display of this page
$tpl->setVariable("SPACE", " ");
$tpl->show();







?>

