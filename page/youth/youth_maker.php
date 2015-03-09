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
$tpl->loadTemplatefile("youth.tpl.php", true, true); 


//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$team_id = sql_quote($_SESSION['s_primary_team_id']);

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$player_id = sql_quote($_GET['player_id']);

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------
// common variables
$position_arr = array(
	"0"=>"GK", "1"=>"DC", "2"=>"DL", "3"=>"DR", "4"=>"DMC", 
	"5"=>"DML", "6"=>"DMR", "7"=>"MC", "8"=>"ML", "9"=>"MR",
	"10"=>"AMC", "11"=>"AML", "12"=>"AMR", "13"=>"F");
$prefer_foot_arr = array( "-2" => "Left Only",
						  "-1" => "Left",
						  "0" => "Either",
						  "1" => "Right",
						  "2" => "Right Only");


// -------------------------------------
// get the youth training level of team
$youth_training_arr = GetYouthTrainingArr($db, $team_id);

// display youth training
DisplayYouthTraining($tpl, $youth_training_arr);

// -------------------------------------
// get trial training new player of team
$trial_training_player_arr = GetTrialTrainingPlayerArr($db, $team_id);

if (count($trial_training_player_arr) > 0) {
	// get selected player index
	$selected_player_index = DisplayTrialTrainingPlayers($tpl, $trial_training_player_arr, $player_id);
	
	
	// -------------------------------------
	// get selected player ability
	$selected_player_id = $trial_training_player_arr[$selected_player_index]["player_id"];
	$original_player_ability = GetSelectedPlayerOriginalAbility($db, $selected_player_id);
	$correct_player_ability = GetSelectedPlayerCorrectAbility($db, $selected_player_id);
	
	// diaplay selected player ability
	DisplaySelectedPlayerAbility($tpl, $TPL_PATCH, 
								 $original_player_ability, $correct_player_ability,
								 $position_arr, $prefer_foot_arr);

}

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// set the SPACE in order to guarantee the display of this page
$tpl->setVariable("SPACE", " ");
$tpl->show();




//----------------------------------------------------------------------------	
// common functions
//----------------------------------------------------------------------------	


	
/**
 * Get Trial Training Player Arr
 *
 * @param [db]			db
 * @param [team_id]		team_id
 *
 * @return player_arr
 */			
function GetTrialTrainingPlayerArr($db, $team_id)
{
	$player_arr = array();
	
	$query = sprintf(
			" SELECT nptt.player_id, p.custom_given_name as given_name, " .
			" p.custom_family_name as family_name " . 
			" FROM new_player_trial_training nptt, team t, player p " .
			" WHERE nptt.team_id='%s' AND nptt.team_id=t.team_id " .
			" AND t.week_elevate_num=0 AND t.season_elevate_num<3 " .
			" AND nptt.player_id=p.player_id " .
			" ORDER BY p.custom_given_name "  ,
			$team_id
			);

	$rs = &$db->Execute($query);

	if (!$rs) {
		print "Database error.";
		exit(0);
	}
	else {
		for (; !$rs->EOF; $rs->MoveNext()) {
			$entity = array();
			
			$given_name = $rs->fields['given_name'];
			$full_name = "";
			if ($given_name == "") {
				$full_name = $rs->fields['family_name'];
			}
			else {
				$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
			}
						
			$entity["player_name"] = $full_name;
			$entity["player_id"] = $rs->fields["player_id"];;
						
			$player_arr[count($player_arr)] = $entity;						
		}		
	}
	
	return $player_arr;
}


/**
 * get the youth training level of team
 *
 * @param [db]			db
 * @param [team_id]		team_id
 *
 * @return youth_training_arr
 */			
function GetYouthTrainingArr($db, $team_id)
{
	$youth_training_arr = array();
	
	$query = sprintf(
			" SELECT t.youth_training_level as level, " .
			" c.youth_training_season_invest as season_invest, " .
			" c.youth_training_cur_invest as cur_invest " .
			" FROM club c, team t " .
			" WHERE t.team_id='%s' AND c.club_id=t.club_id " ,
			$team_id
			);

	$rs = &$db->Execute($query);

	if (!$rs) {
		print "Database error.";
		exit(0);
	}
	else {
		if ($rs->RecordCount() > 0) {
						
			$youth_training_arr["level"] = $rs->fields['level'];
			$youth_training_arr["season_invest"] = $rs->fields['season_invest'];
			$youth_training_arr["cur_invest"] = $rs->fields['cur_invest'];
								
		}		
	}
	
	return $youth_training_arr;
	
}


/**
 * display youth training
 *
 * @param [db]			db
 * @param [youth_training_arr]		youth_training_arr
 *
 * @return void
 */
function DisplayYouthTraining($tpl, $youth_training_arr)
{

	// display the youth training level
	$tpl->setVariable("YOUTH_TRAINING_LEVEL", $youth_training_arr["level"]);
	$tpl->setVariable("YOUTH_TRAINING_CUR_INVEST", intval($youth_training_arr["cur_invest"]));  // display int value
	$tpl->setVariable("YOUTH_TRAINING_NEXT_LEVEL", intval($youth_training_arr["level"])+1);
	
	// display the increase_num_select
	$season_invest_rest = 10 - doubleval($youth_training_arr["season_invest"]);
	for ($i=1; $i<=$season_invest_rest; ++$i)
	{
		$tpl->setCurrentBlock("increase_num_select") ;
		
		$tpl->setVariable("OPTION_VALUE", $i);
		$tpl->setVariable("OPTION_TEXT", $i . " m");
		
		$tpl->parseCurrentBlock("increase_num_select") ;
	}
	
	if ($season_invest_rest == 0) {
		$tpl->setVariable("INCREASE_DISPLAY", "none");
	}

}
	
	
/**
 * Display Trial Training Players
 *
 * @param [tpl]			tpl
 * @param [trial_training_player_arr]		trial_training_player_arr
 * @param [player_id]	player_id
 *
 * @return  $dipslay_player_index
 */			
function DisplayTrialTrainingPlayers($tpl, $trial_training_player_arr, $player_id)
{
	$selected_player_index = 0;
	
	$len = count($trial_training_player_arr);
	for ($i=0; $i<$len; ++$i) {
		$player_entity = $trial_training_player_arr[$i];
		
		$tpl->setCurrentBlock("new_player") ;
		
		$tpl->setVariable("PLAYER_ID", $player_entity["player_id"]);
		$tpl->setVariable("PLAYER_NAME", $player_entity["player_name"]);
		
		if ($player_entity["player_id"] == $player_id) {
			$tpl->setVariable("NEW_PLAYER_CLASS", "gSGRowYellow");	
			$selected_player_index = $i;
		}
		
		$tpl->parseCurrentBlock("new_player") ;
			
	}
			
	return $selected_player_index;
}


/**
 * get selected player original ability
 *
 * @param [db]			db
 * @param [player_id]		player_id
 *
 * @return original_player_ability
 */	
function GetSelectedPlayerOriginalAbility($db, $player_id)
{
	$player_ability = array();
	
	$query = sprintf(
			" SELECT custom_given_name AS given_name, custom_family_name AS family_name, " . 
			" position, prefer_foot, player_or_gk, cloth_number, age, height, " .  
			" pace, power, stamina, " . 
			" finishing, passing, crossing, ball_control, tackling, heading, " . 
			" play_making, off_awareness, def_awareness, " .
			" agility, reflex, " . 
			" handing, rushing_out, positioning, aerial_ability, " . 
			" judgment " . 
			" FROM player " .
			" WHERE player_id='%s' " ,
			$player_id
			);

	$rs = &$db->Execute($query);

	if (!$rs) {
		print "Database error.";
		exit(0);
	}
	else {
		if ($rs->RecordCount() > 0) {
			$given_name = $rs->fields['given_name'];
			$full_name = "";
			if ($given_name == "") {
				$full_name = $rs->fields['family_name'];
			}
			else {
				$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
			}
				
			$player_ability = $rs->fields;		
			$player_ability["player_id"] = $player_id;
			$player_ability["player_name"] = $full_name;
								
		}		
	}
	
	return $player_ability;	
}


/**
 * get selected player correct ability
 *
 * @param [db]			db
 * @param [player_id]		player_id
 *
 * @return correct_player_ability
 */
function GetSelectedPlayerCorrectAbility($db, $player_id)
{
	
	$player_ability = array();
	
	$query = sprintf(
			" SELECT pace, power, stamina, " . 
			" finishing, passing, crossing, ball_control, tackling, heading, " . 
			" play_making, off_awareness, def_awareness, " .
			" agility, reflex, " . 
			" handing, rushing_out, positioning, aerial_ability, " . 
			" judgment " . 
			" FROM new_player_trial_training " .
			" WHERE player_id='%s' " ,
			$player_id
			);

	$rs = &$db->Execute($query);

	if (!$rs) {
		print "Database error.";
		exit(0);
	}
	else {
		if ($rs->RecordCount() > 0) {
			
			$player_ability = $rs->fields;	
								
		}		
	}
	
	return $player_ability;	
}


/**
 * display selected player ability
 *
 * @param [db]			db
 * @param [player_id]	player_id
 *
 * @return no
 */
function DisplaySelectedPlayerAbility($tpl, $TPL_PATCH, 
						$original_ability, $correct_bility, 
						$position_arr, $prefer_foot_arr)
{
	
	// ability
	$tpl_ability = new HTML_Template_ITX($TPL_PATCH); 
	if ($original_ability["player_or_gk"] == "0") {
		$tpl_ability->loadTemplatefile('player_ability.tpl.php', true, true); 
		
		$tpl_ability->setVariable("PLAYER_OR_GK", "normal player");
		
		// Physical
		$tpl_ability->setVariable("PACE", GetCorrectValue($original_ability['pace'], $correct_ability['pace']));
		$tpl_ability->setVariable("POWER", GetCorrectValue($original_ability['power'], $correct_ability['power']));
		$tpl_ability->setVariable("STAMINA", GetCorrectValue($original_ability['stamina'], $correct_ability['stamina']));
		// Technical
		$tpl_ability->setVariable("FINISHING", GetCorrectValue($original_ability['finishing'], $correct_ability['finishing']));
		$tpl_ability->setVariable("PASSING", GetCorrectValue($original_ability['passing'], $correct_ability['passing']));
		$tpl_ability->setVariable("CROSSING", GetCorrectValue($original_ability['crossing'], $correct_ability['crossing']));
		$tpl_ability->setVariable("BALL_CONTROL", GetCorrectValue($original_ability['ball_control'], $correct_ability['ball_control']));
		$tpl_ability->setVariable("TACKLING", GetCorrectValue($original_ability['tackling'], $correct_ability['tackling']));
		$tpl_ability->setVariable("HEADING", GetCorrectValue($original_ability['heading'], $correct_ability['heading']));
		// Mental
		$tpl_ability->setVariable("PLAY_MAKING", GetCorrectValue($original_ability['play_making'], $correct_ability['play_making']));
		$tpl_ability->setVariable("OFF_AWARENESS", GetCorrectValue($original_ability['off_awareness'], $correct_ability['off_awareness']));
		$tpl_ability->setVariable("DEF_AWARENESS", GetCorrectValue($original_ability['def_awareness'], $correct_ability['def_awareness']));
		
	}
	else {
		$tpl_ability->loadTemplatefile('gk_ability.tpl.php', true, true); 
		
		$tpl_ability->setVariable("PLAYER_OR_GK", "goal keeper");
		// Physical
		$tpl_ability->setVariable("AGILITY", GetCorrectValue($original_ability['agility'], $correct_ability['agility']));
		$tpl_ability->setVariable("REFLEX", GetCorrectValue($original_ability['reflex'], $correct_ability['reflex']));  
		// Technical
		$tpl_ability->setVariable("HANDING", GetCorrectValue($original_ability['handing'], $correct_ability['handing']));
		$tpl_ability->setVariable("RUSHING_OUT", GetCorrectValue($original_ability['rushing_out'], $correct_ability['rushing_out']));
		$tpl_ability->setVariable("POSITIONING", GetCorrectValue($original_ability['positioning'], $correct_ability['positioning']));
		$tpl_ability->setVariable("AERIAL_ABILITY", GetCorrectValue($original_ability['aerial_ability'], $correct_ability['aerial_ability']));
		// Mental
		$tpl_ability->setVariable("JUDGMENT", GetCorrectValue($original_ability['judgment'], $correct_ability['judgment']));
	}
	
	// common
	$tpl_ability->setVariable("PLAYER_ID", $original_ability["player_id"]);
	$tpl_ability->setVariable("PLAYER_NAME", $original_ability["player_name"]);
	$tpl_ability->setVariable("POSITION", $position_arr[$original_ability['position']]);
	$tpl_ability->setVariable("PREFER_FOOT", $prefer_foot_arr[$original_ability['prefer_foot']]);	
	$tpl_ability->setVariable("CLOTH_NUMBER", $original_ability['cloth_number']);	
	$tpl_ability->setVariable("AGE", $original_ability['age']);	
	$tpl_ability->setVariable("HEIGHT", $original_ability['height']);
	
	
	// 
	$tpl->setVariable("PLAYER_ABILITY_CONTENT", $tpl_ability->get());
	
}

// get correct ability value
function GetCorrectValue($original_value, $correct_value)
{
	$new_value = intval($original_value) + intval($correct_value);
	
	$new_value_str = "<6";
	
	if ($new_value >= 18) {
		$new_value_str = "S";	
	}
	else if ($new_value >= 15) {
		$new_value_str = "A";	
	}
	else if ($new_value >= 12) {
		$new_value_str = "B";	
	}
	else if ($new_value >= 9) {
		$new_value_str = "C";	
	}
	else if ($new_value >= 6) {
		$new_value_str = "D";	
	}
	
	return $new_value_str;
	
}



?>

