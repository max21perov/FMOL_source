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
$tpl->loadTemplatefile("player_list.tpl.php", true, true); 


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
$position_arr = array(
	"0"=>"GK", "1"=>"DC", "2"=>"DL", "3"=>"DR", "4"=>"DMC", 
	"5"=>"DML", "6"=>"DMR", "7"=>"MC", "8"=>"ML", "9"=>"MR",
	"10"=>"AMC", "11"=>"AML", "12"=>"AMR", "13"=>"F");
/**
 * show the player list
 */
$query = sprintf(
			" SELECT player_id AS primary_player_id, custom_given_name AS given_name, " . 
			" custom_family_name AS family_name, position, player_or_gk, " .  
			" suspend_match_num, rest_day_num " . 
			" FROM player " .  
			" WHERE team_id='%s' " . 
			" ORDER BY position ASC " ,
			$team_id);


$rs = &$db->Execute($query);

if (!$rs) {
	$error_message = "Database Error!"; // $db->ErrorMsg();
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit(0);
}
else {
    $index = 1;
    while (!$rs->EOF) {

		$tpl->setCurrentBlock("player_list") ;
		//if ($index % 2 != 0 )
			//$tpl->setVariable("FIXTURES_TR_CLASS", 'gSGRowEven') ;
		//else 
			$tpl->setVariable("FIXTURES_TR_CLASS", 'gSGRowOdd_input') ;
		$index++;	
		$tpl->setVariable("TEAM_ID", $team_id);
		$tpl->setVariable("P_PLAYER_ID", $rs->fields['primary_player_id']);
		$given_name = $rs->fields['given_name'];
		$full_name = "";
		if (empty($given_name)) {
			$full_name = $rs->fields['family_name'];
		}
		else {
			$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
		}
		$tpl->setVariable("PLAYER_NAME", $full_name);
		$tpl->setVariable("POSITION", $position_arr[$rs->fields['position']]);
		$tpl->setVariable("PLAYER_OR_GK", $rs->fields['player_or_gk']);
		
		if (intval($rs->fields['suspend_match_num']) == 0) {
			$tpl->setVariable("SUSPEND_DISPLAY", "none");
		}
		if (intval($rs->fields['rest_day_num']) == 0) {
			$tpl->setVariable("INJURE_DISPLAY", "none");
		}
		
		$tpl->parseCurrentBlock("player_list") ;
		
		$rs->MoveNext(); 
    }
}		

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------		
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");	

$tpl->show();


?>

