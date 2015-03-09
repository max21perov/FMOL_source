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
$tpl->loadTemplatefile("coach_list.tpl.php", true, true); 


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * show the coach list
 */
$query = sprintf(
			" SELECT coach_id AS primary_coach_id, custom_given_name AS given_name, " . 
			" custom_family_name AS family_name  " . 
			" FROM coach " .  
			" WHERE team_id='%s' " ,
			$team_id);


$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database Error!"; // $db->ErrorMsg();
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit(0);
}
else {
    $index = 1;
    for (; !$rs->EOF; $rs->MoveNext()) {

		$tpl->setCurrentBlock("coach_list") ;
		//if ($index % 2 != 0 )
			//$tpl->setVariable("FIXTURES_TR_CLASS", 'gSGRowEven') ;
		//else 
			$tpl->setVariable("FIXTURES_TR_CLASS", 'gSGRowOdd_input') ;
		$index++;	
		$tpl->setVariable("TEAM_ID", $team_id);
		$tpl->setVariable("P_COACH_ID", $rs->fields['primary_coach_id']);
		$given_name = $rs->fields['given_name'];
		$full_name = "";
		if (empty($given_name)) {
			$full_name = $rs->fields['family_name'];
		}
		else {
			$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
		}
		$tpl->setVariable("COACH_NAME", $full_name);
		

		
		$tpl->parseCurrentBlock("coach_list") ;
		
		
    }
}		

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------		
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");	

$tpl->show();


?>

