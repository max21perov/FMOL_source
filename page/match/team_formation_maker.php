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
$tpl->loadTemplatefile("team_formation.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = $_SESSION['s_primary_team_id'];

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$team_id = sql_quote($_GET['team_id']);


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	


			

/**
 * produces the prompt divs
 */	
produce_prompt_divs($tpl);

/**
 * player value
 */

$place_width = 45 + 30;
$player_name_width = 95;
$left_coordinate = 2;  // 控制 player_list 的x轴开始坐标
$place_left_coordinate = $left_coordinate + 2; // place 层的x轴坐标
$player_name_left_coordinate = $place_left_coordinate + $place_width + 5; // player_name 层的x轴坐标
$o_top_coordinate  = 23;
$field_left_coordinate = 0; 
$field_top_coordinate = 0;
$info_left_coordinate = 50;
$info_top_coordinate = 100;
$position_arr = array(
	"0"=>"GK", "1"=>"DC", "2"=>"DL", "3"=>"DR", "4"=>"DMC", 
	"5"=>"DML", "6"=>"DMR", "7"=>"MC", "8"=>"ML", "9"=>"MR",
	"10"=>"AMC", "11"=>"AML", "12"=>"AMR", "13"=>"F");
$prefer_foot_arr = array( "-2" => "Left Only",
						  "-1" => "Left",
						  "0" => "Either",
						  "1" => "Right",
						  "2" => "Right Only");


/**
 * tactics data
 */
$p_tactics_id = getPTacticsId($db, $team_id);

$return_arr = getPlayerPropertyScript($db, $team_id, $p_tactics_id);
$player_property_script = $return_arr["player_property_script"];
$players_number = $return_arr["players_number"];
$tactics_data = $return_arr["tactics_data"];	
$player_id_list = $return_arr["player_id_list"];	

$script_code .= $player_property_script;

// 221 = 219 + 2 + 2
// 219 is the right coordinate of the player list; 2 is the separate between the player list and the field
$script_code .= "
  //init_tactics(ft, fl, fx, fy, gw,  gh,  sw, sh, pw, ph, iw,  ih, md, mm, mf, with_count, top_coordinate, left_coordinate, place_left_coordinate, player_name_left_coordinate)
    init_tactics(0,  0,  10, 11, 260, 381, 22, 21, 20, 20, 182, 342, 2,  2, 1,  true, $o_top_coordinate, $left_coordinate, $place_left_coordinate, $player_name_left_coordinate); 
";

$tpl->setVariable("PLAYERS_VALUE", $player_id_list);
$tpl->setVariable("TACTICS_DATA", $tactics_data);
$tpl->setVariable("SCRIPT_CODE", $script_code);
$tpl->setVariable("PLAYERS_NUMBER", $players_number);
$tpl->setVariable("P_TACTICS_ID", $p_tactics_id);

$tpl->setVariable("FIELD_LEFT_COORDINATE", $field_left_coordinate);
$tpl->setVariable("FIELD_TOP_COORDINATE", $field_top_coordinate);
// set the team name
$tpl->setVariable("TEAM_NAME", $team_name);



//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------
// add a space in the page to show the page any time
$tpl->setVariable("SPACE", " ");

$tpl->show();


//----------------------------------------------------------------------------	
// functions
//----------------------------------------------------------------------------
/**
 * produces the prompt divs
 *
 * @param [tpl]		tpl
 *
 * @return  none
 */	
function produce_prompt_divs($tpl)
{
	/**
	 * produces the prompt divs
	 */
	$prompt_img_width_value = 20;
	$prompt_img_height_value = 20;
	for ($r=0; $r<5; ++$r) {
		for ($c=0; $c<5; ++$c) {	    
			$tpl->setCurrentBlock('prompt_divs') ;
			$tpl->setVariable('PROMPT_DIV_NAME', 'prompt_' . $r . '_' . $c);
			$tpl->setVariable('PROMPT_PLACE_NOTMOVE_DIV_NAME', 'prompt_p_n_' . $r . '_' . $c);
			$tpl->setVariable('PROMPT_PLACE_MOVE_DIV_NAME', 'prompt_p_' . $r . '_' . $c);
			$tpl->setVariable('PLAYER_DIV_NAME', 'player_div_' . $r . '_' . $c);
			$tpl->setVariable('PRMOPT_IMG_WIDTH_VALUE', $prompt_img_width_value);
			$tpl->setVariable('PROMPT_IMG_HEIGHT_VALUE', $prompt_img_height_value);
			switch ($r) {
			case 0:    
				$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_F_small.gif');
				break;
			case 1:
				$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_AM_small.gif');
				break;
			case 2:
				$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_M_small.gif');
				break;
			case 3:
				$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_DM_small.gif');
				break;
			case 4:
				$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_D_small.gif');
				break;			
			}
			$tpl->parseCurrentBlock('prompt_divs') ;
		}
	}
	
	$tpl->setCurrentBlock('prompt_divs') ;
	$tpl->setVariable('PROMPT_DIV_NAME', 'prompt_5_0');
	$tpl->setVariable('PROMPT_PLACE_NOTMOVE_DIV_NAME', 'prompt_p_n_5_0');
	$tpl->setVariable('PROMPT_PLACE_MOVE_DIV_NAME', 'prompt_p_5_0');
	$tpl->setVariable('PLAYER_DIV_NAME', 'player_div_5_0');
	$tpl->setVariable('PRMOPT_IMG_WIDTH_VALUE', $prompt_img_width_value);
	$tpl->setVariable('PROMPT_IMG_HEIGHT_VALUE', $prompt_img_height_value);
	$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_GK_small.gif');
	$tpl->parseCurrentBlock('prompt_divs') ;
	
}

/**
 * get primary tactics id
 *
 * @param [db]			db
 * @param [team_id]		team_id
 *
 * @return  p_tactics_id
 */	
function getPTacticsId($db, $team_id)
{
	$p_tactics_id = "-1";
	// tactics 
	$query = sprintf(
			" SELECT t.id as p_tactics_id " . 
			" FROM tactics t, team_tactics tt " .
			" WHERE tt.team_id='%s' " .
			" AND t.id=tt.cur_tactics_id " ,
			$team_id
			);

	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error.";
		exit(0);
	}
	else {
		if ($rs->RecordCount() > 0) {
			$p_tactics_id = $rs->fields['p_tactics_id'];
		}
	}
	
	return $p_tactics_id;
}

/**
 * get primary tactics id
 *
 * @param [db]				db
 * @param [team_id]			team_id
 * @param [p_tactics_id]	p_tactics_id
 *
 * @return  $player_property_script
 */	
function getPlayerPropertyScript($db, $team_id, $p_tactics_id)
{
	$return_arr = array();
	$players_number = 0;
	$player_property_script = "";
	
	// tactics_detail
	$query = sprintf(
			" SELECT t.position_place, p.player_id, p.custom_given_name AS given_name, " . 
			" p.custom_family_name AS family_name, p.position, p.prefer_foot, " . 
			" p.cloth_number, p.age, " . 
			" p.pace, p.power, p.stamina, p.height, " . 
			" p.finishing, p.passing, p.crossing, p.ball_control, p.tackling, p.heading, " . 
			" p.play_making, p.off_awareness, p.def_awareness, p.experience, " .  
			" p.agility, p.reflex, " . 
			" p.handing, p.rushing_out, p.positioning, p.aerial_ability, " .  
			" p.judgment, " .  
			" p.form, p.condition, p.morale, p.happiness " . 
			" FROM player p left join tactics_detail t on p.player_id=t.player_id AND t.tactics_id='%s' " .
			" WHERE p.team_id='%s' " .
			" ORDER BY t.position_place ASC " ,
			$p_tactics_id, $team_id
			);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error.";
		return $return_arr;
	}
	else {
		$players_number = $rs->RecordCount();
		 		
		// loop
		// the players on the ground
		$index = 0;
		for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext()) {
			$position_place = $rs->fields['position_place'];
			if ($position_place == null) {
				continue;
			}
			
			if ($position_place >= 100) {
				continue;
			}
			
			// form the $tactics_data		
			$position_place = $position_place - 25;
			if ($position_place < 0) $position_place = 0 - $position_place;
			$row = intval($position_place / 5);
			$col = $position_place % 5;
			if ($in_field_index == 0) {
				++ $in_field_index;
			}
			else {
				$tactics_data .= '&' ;
			}
			
			$tactics_data .= $rs->fields['player_id'] . '_' ;
			$tactics_data .= $row . '_' . $col;
			
			
			// form the "$player_id_list"
			if ($index != 0) {
				$player_id_list .= ',';
			}
			$player_id_list .= $rs->fields['player_id'];
		
			
			$player_property_script .= formPlayerPropertyScript($rs);
			
			++$index;
			
			if ($index >= 25) break; // the number of players is less then 25 or equals to 25
		}
		
	}
	
	$return_arr["players_number"] = $players_number;
	$return_arr["player_property_script"] = $player_property_script;	
	$return_arr["tactics_data"] = $tactics_data;	
	$return_arr["player_id_list"] = $player_id_list;	
	
	return $return_arr;
}


/**
 * form the player property script from result set
 *
 * @param [rs]		the result set
 *
 * @return  $script
 */	
function formPlayerPropertyScript($rs)
{
	$script = "";
	$script = "ppTable". $rs->fields['player_id']. " = new Array( \"".
        	$rs->fields['given_name']. "\", \"". $rs->fields['family_name']. "\", ". intval($rs->fields['position']). ", ". 
        	intval($rs->fields['prefer_foot']). ", ". intval($rs->fields['cloth_number']). ", ". intval($rs->fields['age']). ", ". 
        	intval($rs->fields['pace']). ", ". intval($rs->fields['power']). ", ". 
        	intval($rs->fields['stamina']). ", ". intval($rs->fields['height']). ", ". intval($rs->fields['finishing']). ", ". 
        	intval($rs->fields['passing']). ", ". intval($rs->fields['crossing']). ", ". intval($rs->fields['ball_control']). ", ". 
        	intval($rs->fields['tackling']). ", ". intval($rs->fields['heading']). ", ". intval($rs->fields['play_making']). ", ". 
        	intval($rs->fields['off_awareness']). ", ". intval($rs->fields['def_awareness']). ", ". intval($rs->fields['experience']). ", ". 
        	intval($rs->fields['agility']). ", ". intval($rs->fields['reflex']). ", ". intval($rs->fields['handing']). ", ". 
        	intval($rs->fields['rushing_out']). ", ". intval($rs->fields['positioning']). ", ". intval($rs->fields['aerial_ability']). ", ". 
        	intval($rs->fields['judgment']). ", ". intval($rs->fields['form']). ", ". intval($rs->fields['condition']). ", ". 
        	intval($rs->fields['morale']). ", ". intval($rs->fields['happiness']). 
        	" );
        ";	

	return $script;
}

?>


