<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");


// update the div_id 
// when a season finishes and the team's div_id may be changed
require_once(DOCUMENT_ROOT . "/page/system/update_div_id.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile('extend_contract.tpl.php', true, true); 
		


//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$player_id = sql_quote($_GET['player_id']);


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

// get player info
$player_info_arr = GetPlayerInfo($db, $player_id);

// display player info
DisplayPlayerInfo($tpl, $player_info_arr);


// set return_page_url
$player_or_gk = $player_info_arr["player_or_gk"];
$tpl->setVariable("RETURN_PAGE_URL", 
		"/fmol/page/players/player_info.php?player_id=$player_id&player_or_gk=$player_or_gk");



$tpl->setVariable("ATTENTION_STR", iconv("GBK", "UTF-8", "1．	IF当前薪金>续约薪金（即当前身价的20％），不会成功。"));


//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");
$tpl->show();



//----------------------------------------------------------------------------	
// common functions
//----------------------------------------------------------------------------	
/**
  * get player info
  *
  * @param [db]				database
  * @param [player_id]		player 的 id
  *
  * @return player info
  */
function GetPlayerInfo($db, $player_id)
{
	$player_info_arr = array();
	
	$query = sprintf(
				" select custom_given_name as given_name, custom_family_name as family_name, " . 
				" player_or_gk, age, player_value, salary " .
				" from player " .
				" where player_id='%s' ",
				$player_id);
				
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error.";  // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		if ($rs->RecordCount() >= 1) {
			$given_name = $rs->fields['given_name'];
			$family_name = $rs->fields['family_name'];
			$full_name = "";
			if ($given_name == "") {
				$full_name = $family_name;
			}
			else {
				$full_name = $given_name . " . " . $family_name;
			}
			
			$player_info_arr["player_id"] 	= $player_id;
			$player_info_arr["player_name"] = $full_name;
			$player_info_arr["player_or_gk"]= $rs->fields['player_or_gk'];
			$player_info_arr["age"] 		= $rs->fields['age'];
			$player_info_arr["player_value"]= $rs->fields['player_value'];
			$player_info_arr["salary"] 		= $rs->fields['salary'];
			
		}
	}		
	
	return $player_info_arr;
}


/**
  * display player info
  *
  * @param [db]					database
  * @param [player_info_arr]	player_info_arr
  *
  * @return void
  */
function DisplayPlayerInfo($tpl, $player_info_arr)
{
	$tpl->setVariable("PLAYER_ID", $player_info_arr['player_id']);	
	$tpl->setVariable("PLAYER_VALUE", $player_info_arr['player_value']);
	$tpl->setVariable("CUR_SALARY", $player_info_arr['salary']);
	
	$new_salary = doubleval($player_info_arr['player_value']) * 0.2;
	$tpl->setVariable("NEW_SALARY", $new_salary);
	
	$age = intval($player_info_arr['age']);
	if ($age < 28) {
		$tpl->setCurrentBlock("extend_seasons_select") ;

		$tpl->setVariable("OPTION_VALUE", "2");
		$tpl->setVariable("OPTION_TEXT", "2");
		$tpl->parseCurrentBlock("extend_seasons_select") ;
		
		
		$tpl->setCurrentBlock("extend_seasons_select") ;

		$tpl->setVariable("OPTION_VALUE", "3");
		$tpl->setVariable("OPTION_TEXT", "3");
		$tpl->parseCurrentBlock("extend_seasons_select") ;
	}
	else {
		$tpl->setCurrentBlock("extend_seasons_select") ;

		$tpl->setVariable("OPTION_VALUE", "2");
		$tpl->setVariable("OPTION_TEXT", "2");
		$tpl->parseCurrentBlock("extend_seasons_select") ;
	}
		
}


?>

