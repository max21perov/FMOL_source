<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once("player_functions.php");


//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("player_info.tpl.php", true, true); 


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
// $position_arr = array("DC", "DL", "DR", "DMC", "DML", "DMR", "MC", "ML", "MR", "AMC", "AML", "AMR", "F");
$position_arr = array(
	"0"=>"GK", "1"=>"DC", "2"=>"DL", "3"=>"DR", "4"=>"DMC", 
	"5"=>"DML", "6"=>"DMR", "7"=>"MC", "8"=>"ML", "9"=>"MR",
	"10"=>"AMC", "11"=>"AML", "12"=>"AMR", "13"=>"F");
$prefer_foot_arr = array( "-2" => "Left Only",
						  "-1" => "Left",
						  "0" => "Either",
						  "1" => "Right",
						  "2" => "Right Only");


// get the injure dict from DB						
$player_or_gk = 0;  // normal player
$injure_dict_arr = getInjureDict($db, $player_or_gk);

					  
/**
 * show the player list
 */
// search all properties from player
$query = sprintf(
			" SELECT player_id, custom_given_name AS given_name, custom_family_name AS family_name, " . 
			" position, prefer_foot, player_or_gk, cloth_number, age, potential_point, governable_potential_point, " . 
			" suspend_match_num, red_card_totality, yellow_card_totality, injure_id, rest_day_num, " . 
			" character_status, perform, prestige, " .
			" pace, power, stamina, height, " . 
			" finishing, passing, crossing, ball_control, tackling, heading, " . 
			" play_making, off_awareness, def_awareness, experience, " . 
			" character_style_on_pitch, character_style_off_pitch, growth_career, current_growth, " . 
			" form, condition, morale, happiness, " . 
			" season_remains, player_value, salary, contract_negotiating " . 
			" FROM player " . 
			" WHERE player_id='%s' " ,
			$player_id);

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
		$tpl->setVariable("PLAYER_NAME", $full_name);
		$tpl->setVariable("POSITION", $position_arr[$rs->fields['position']]);
		$tpl->setVariable("PREFER_FOOT", $prefer_foot_arr[$rs->fields['prefer_foot']]);
		$tpl->setVariable("PLAYER_OR_GK", "normal player");
		$tpl->setVariable("CLOTH_NUMBER", $rs->fields['cloth_number']);	
		$tpl->setVariable("AGE", $rs->fields['age']);	
		$tpl->setVariable("POTENTIAL_POINT", $rs->fields['potential_point']);
		$tpl->setVariable("GOVERNABLE_POTENTIAL_POINT", $rs->fields['governable_potential_point']);
		
		$tpl->setVariable("CHARACTER_STATUS", $rs->fields['character_status']);
		$tpl->setVariable("PERFORM", $rs->fields['perform']);
		$tpl->setVariable("PRESTIGE", $rs->fields['prestige']);

		
		// Suspend
		$tpl->setVariable("SUSPEND_MATCH_NUM", $rs->fields['suspend_match_num']);
		$tpl->setVariable("RED_CARD_TOTALITY", $rs->fields['red_card_totality']);
		$tpl->setVariable("YELLOW_CARD_TOTALITY", $rs->fields['yellow_card_totality']); 
		// Injure
		if (intval($rs->fields['rest_day_num']) > 0) {
			$tpl->setVariable("INJURE_TYPE", $injure_dict_arr[($rs->fields['injure_id'])]['injure_type']);
			$tpl->setVariable("INJURE_NAME", $injure_dict_arr[($rs->fields['injure_id'])]['injure_name']);
			$tpl->setVariable("INJURE_GRADE", $injure_dict_arr[($rs->fields['injure_id'])]['injure_grade']);		
		}
		else {
			$tpl->setVariable("INJURE_TYPE", " - ");
			$tpl->setVariable("INJURE_NAME", " - ");
			$tpl->setVariable("INJURE_GRADE", " - ");
		}
		$tpl->setVariable("REST_DAY_NUM", $rs->fields['rest_day_num']);
		// Physical
		$tpl->setVariable("PACE", $rs->fields['pace']);
		$tpl->setVariable("POWER", $rs->fields['power']);
		$tpl->setVariable("STAMINA", $rs->fields['stamina']);
		$tpl->setVariable("HEIGHT", $rs->fields['height']);
		// Technical
		$tpl->setVariable("FINISHING", $rs->fields['finishing']);
		$tpl->setVariable("PASSING", $rs->fields['passing']);
		$tpl->setVariable("CROSSING", $rs->fields['crossing']);
		$tpl->setVariable("BALL_CONTROL", $rs->fields['ball_control']);
		$tpl->setVariable("TACKLING", $rs->fields['tackling']);
		$tpl->setVariable("HEADING", $rs->fields['heading']);
		// Mental
		$tpl->setVariable("PLAY_MAKING", $rs->fields['play_making']);
		$tpl->setVariable("OFF_AWARENESS", $rs->fields['off_awareness']);
		$tpl->setVariable("DEF_AWARENESS", $rs->fields['def_awareness']);
		$tpl->setVariable("EXPERIENCE", $rs->fields['experience']);
		// Character
		$tpl->setVariable("CHARACTER_STYLE_ON_PITCH", $rs->fields['character_style_on_pitch']);	
		$tpl->setVariable("CHARACTER_STYLE_OFF_PITCH", $rs->fields['character_style_off_pitch']);	
		$tpl->setVariable("GROWTH_CAREER", $rs->fields['growth_career']);	
		$tpl->setVariable("CURRENT_GROWTH", $rs->fields['current_growth']);	
		// Routine
		$tpl->setVariable("FORM", $rs->fields['form']);	
		$tpl->setVariable("CONDITION", $rs->fields['condition']);	
		$tpl->setVariable("MORALE", $rs->fields['morale']);	
		$tpl->setVariable("HAPPINESS", $rs->fields['happiness']);
		// Contract
		$tpl->setVariable("SEASON_REMAINS", $rs->fields['season_remains']);	
		$tpl->setVariable("PLAYER_VALUE", $rs->fields['player_value']);	
		$tpl->setVariable("SALARY", $rs->fields['salary']);	
				
		if ($rs->fields['contract_negotiating'] == "0") {
			$tpl->setVariable("EXTEND_CONTRACT_STR", iconv("GBK", "UTF-8", "无"));
			$tpl->setVariable("EXTEND_CONTRACT_STR_COLOR", "black");
		}
		else {
			$tpl->setVariable("EXTEND_CONTRACT_STR", iconv("GBK", "UTF-8", "合约谈判中"));
			$tpl->setVariable("EXTEND_CONTRACT_STR_COLOR", "red");
		}
    }
}		

// get opinion of player
$opinion_arr = getPlayerOpinionArr($db, $player_id);
// display opinion of player
displayPlayerOpinion($tpl, $opinion_arr);



/**
 * send the player_id, player_name to cookie
 */
$cookie_value = $HTTP_COOKIE_VARS["team_player_cookie"];
$is_exist = false;
if ($cookie_value != null && $cookie_value != "") {
	$big_arr = explode("|", $cookie_value);
	$big_len = count($big_arr);
	for ($i=0; $i<$big_len; ++$i) {
		$item_value = $big_arr[$i];
		$little_arr = explode(":", $item_value);
		
		if (count($little_arr)==3 && $little_arr[0]=="1" && $little_arr[1]==$player_id) {
			$is_exist = true;
			break;
		}
	}
	
	if (!$is_exist) {
		$temp_value = "1" . ":" . $player_id . ":" . $full_name;
		$cookie_value .= "|" . $temp_value;
	}
}
else {
	$temp_value = "1" . ":" . $player_id . ":" . $full_name;
	$cookie_value = $temp_value;
}
setcookie("team_player_cookie", $cookie_value, time()+3600*24, "/fmol");

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// set the SPACE in order to guarantee the display of this page
$tpl->setVariable("SPACE", " ");
$tpl->show();







?>

