<?php




//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
$place_options = array();
$place_options_sub = array();
$place_options_rest = array();

$team_instructions_arr = array();
// id => name of player
$tpop_player_id_name_arr = array();
$tpop_player_id_name_arr["-1"] = "please select";  // insert the default row into $tpop_player_id_name_arr

/**
 * script code
 */
$script_code = "
";

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
/**
 * player value
 */

$place_width = 45 + 30;
$player_name_width = 95;
$left_coordinate = 2;  // 控制 player_list 的x轴开始坐标
$place_left_coordinate = $left_coordinate + 2; // place 层的x轴坐标
$player_name_left_coordinate = $place_left_coordinate + $place_width + 5; // player_name 层的x轴坐标
$o_top_coordinate  = 23;
$field_left_coordinate = 0 + 3;  // 0 is the left coordinate of the field
$field_top_coordinate = 2;
$info_left_coordinate = 50;
$info_top_coordinate = 100;
$position_arr = array(
	"0"=>"GK", "1"=>"DC", "2"=>"DL", "3"=>"DR", "4"=>"DMC", 
	"5"=>"DML", "6"=>"DMR", "7"=>"MC", "8"=>"ML", "9"=>"MR",
	"10"=>"AMC", "11"=>"AML", "12"=>"AMR", "13"=>"F");
	
$position_place_arr = array(
	"0"=>"GK", "1"=>"DR", "2"=>"DC", "3"=>"DC", "4"=>"DC", 
	"5"=>"DL", "6"=>"DMR", "7"=>"DMC", "8"=>"DMC", "9"=>"DMC",
	"10"=>"DML", "11"=>"MR", "12"=>"MC", "13"=>"MC", "14"=>"MC",
	"15"=>"ML", "16"=>"AMR", "17"=>"AMC", "18"=>"AMC", "19"=>"AMC",
	"20"=>"AML", "21"=>"F", "22"=>"F", "23"=>"F", "24"=>"F", "25"=>"F");
	
$prefer_foot_arr = array( "-2" => "Left Only",
						  "-1" => "Left",
						  "0" => "Either",
						  "1" => "Right",
						  "2" => "Right Only");


/**
 * tactics data
 */
// $tactics_id = 1;
// tactics 
$query = sprintf(
		" SELECT t.id, t.tactics_symbol, t.passing_style, t.mentality, " . 
		" t.off_focus, t.use_key_man, t.use_target_man, " . 
		" t.offside_trip, t.dline_push_up, t.counter_attack, t.pressing, t.tackling, " . 
		" t.tempo " . 
		" FROM tactics t " .
		" WHERE t.id='%s' " ,
		$p_tactics_id
		);

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database error.";
    exit(0);
}
else {
	if ($rs->RecordCount() > 0) {
//        $p_tactics_id = $rs->fields['id'];
        $tactics_symbol = $rs->fields['tactics_symbol'];  
        $team_instructions_arr['passing_style'] = $rs->fields['passing_style'];
        $team_instructions_arr['mentality'] = $rs->fields['mentality'];
        $team_instructions_arr['off_focus'] = $rs->fields['off_focus']; 
		
        $team_instructions_arr['offside_trip'] = $rs->fields['offside_trip']; 
        $team_instructions_arr['dline_push_up'] = $rs->fields['dline_push_up']; 
        $team_instructions_arr['counter_attack'] = $rs->fields['counter_attack']; 
        $team_instructions_arr['pressing'] = $rs->fields['pressing']; 
        $team_instructions_arr['tackling'] = $rs->fields['tackling']; 
        $team_instructions_arr['tempo'] = $rs->fields['tempo']; 
		
        $team_instructions_arr['use_key_man'] = $rs->fields['use_key_man']; 
        $team_instructions_arr['use_target_man'] = $rs->fields['use_target_man']; 
    }
}

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
		" FROM player p " .
		" LEFT JOIN tactics_detail t on p.player_id=t.player_id AND t.tactics_id='%s' " .
		" WHERE p.team_id='%s' " .
		" ORDER BY t.position_place ASC" ,
		$p_tactics_id, $team_id
		);

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database error." . $db->ErrorMsg();
    exit(0);
}
else {
	$players_number = $rs->RecordCount();
	 
	// 第一次遍历 $rs：
	// 统计在所选择的阵型中，球员可以选择的位置
	$place_options_arr = getPlaceOptionsArr($rs, $position_place_arr);
	$place_options = $place_options_arr["place_options"];
	$place_options_sub = $place_options_arr["place_options_sub"];
	$place_options_rest = $place_options_arr["place_options_rest"];
	
	// 第二次遍历 $rs：
	// 选择显示在阵型中的球员以及替补
	$index = 0;
    for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext()) {
	    $position_place = $rs->fields['position_place'];
		if ($position_place == null) {
			continue;
		}
	    
        $player_property_script .= getPlayerPropertyScript($rs);
		
		// produce the place select
		producePlaceSelect($tpl, $place_options, $place_options_sub, $place_options_rest, $position_place);
		
		// produce the player list
		$tpl->setCurrentBlock("player_list") ;
		if ($index % 2 == 0)
		    $tpl->setVariable("PLAYER_DIVS_TR_CLASS", 'gSGRowEven_input');
		else
		    $tpl->setVariable("PLAYER_DIVS_TR_CLASS", 'gSGRowOdd_input');
		
		$tpl->setVariable("PLAYER_ID", $rs->fields['player_id']);
		$tpl->setVariable("INFO_LEFT_COORDINATE", $info_left_coordinate);
		$tpl->setVariable("INFO_TOP_COORDINATE", $info_top_coordinate);
		$player_name = "";
		if ($rs->fields['given_name'] == "") {
			$player_name = $rs->fields['family_name'];
		}
		else {
			$player_name = substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name'];
		}
		$tpl->setVariable("PLAYER_NAME", $player_name);
		$tpl->parseCurrentBlock("player_list") ;
		
		if (intval($position_place)>=1 && intval($position_place)<=25) {
			$tpop_player_id_name_arr[$rs->fields['player_id']] = $player_name;
		}
		++$index;
		
		if ($index > 33) break; // the number of players is less then 33 or equals to 33
	}
	
	// 第三次遍历 $rs：
	// 选择显示不在阵型中的球员	
	$rest_index = 0;
    for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext()) {
	    $position_place = $rs->fields['position_place'];
		if ($position_place != null) {
			continue;
		}
			
        $player_property_script .= getPlayerPropertyScript($rs);
        	
		// produce the place select
		++ $rest_index;
		$position_place = 500 + $rest_index;  
		producePlaceSelect($tpl, $place_options, $place_options_sub, $place_options_rest, $position_place);
		
		// produce the player list
		$tpl->setCurrentBlock("player_list") ;
		if ($index % 2 == 0)
		    $tpl->setVariable("PLAYER_DIVS_TR_CLASS", 'gSGRowEven_input');
		else
		    $tpl->setVariable("PLAYER_DIVS_TR_CLASS", 'gSGRowOdd_input');
		
		$tpl->setVariable("PLAYER_ID", $rs->fields['player_id']);
		$tpl->setVariable("INFO_LEFT_COORDINATE", $info_left_coordinate);
		$tpl->setVariable("INFO_TOP_COORDINATE", $info_top_coordinate);
		$player_name = "";
		if ($rs->fields['given_name'] == "") {
			$player_name = $rs->fields['family_name'];
		}
		else {
			$player_name = substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name'];
		}
		$tpl->setVariable("PLAYER_NAME", $player_name);
		$tpl->parseCurrentBlock("player_list") ;
		
		//$tpop_player_id_name_arr[$rs->fields['player_id']] = $player_name;
		++$index;		
		if ($index >= 33) break; // the number of players is less then 33 or equals to 33
	}
}

// std_formation
$query = sprintf(
		" SELECT DISTINCT tactics_symbol " . 
		" FROM std_formation " . 
		" ORDER BY tactics_symbol "
		);
$rs = &$db->Execute($query);

if (!$rs) {
    print "Database error.";
    exit(0);
}
else {
    while (!$rs->EOF) {
	    $tactics_symbol_other = $rs->fields['tactics_symbol'];
	    
	    // produce the std_formation select
		$tpl->setCurrentBlock("std_formation_select") ;
		
		$tpl->setVariable("STD_FORMATION_OPTION_VALUE", $tactics_symbol_other);
		$tpl->setVariable("STD_FORMATION_OPTION_TEXT", $tactics_symbol_other);
		if ($tactics_symbol_other == $tactics_symbol) {
			$tpl->setVariable("STD_FORMATION_SELECTED", "selected");
		}
		
		$tpl->parseCurrentBlock("std_formation_select") ;
	    
		$rs->MoveNext(); 
	}
}
$script_code .= $player_property_script;
// 221 = 219 + 2 + 2
// 219 is the right coordinate of the player list; 2 is the separate between the player list and the field
$script_code .= "
  //init_tactics(ft, fl, fx, fy, gw,  gh,  sw, sh, pw, ph, iw,  ih, md, mm, mf, with_count, top_coordinate, left_coordinate, place_left_coordinate, player_name_left_coordinate)
    init_tactics(1,  $field_left_coordinate,  10, 11, 260, 381, 22, 21, 20, 20, 182, 342, 2,  2, 1,  true, $o_top_coordinate, $left_coordinate, $place_left_coordinate, $player_name_left_coordinate); 
";

$tpl->setVariable("TACTICS_DATA", $tactics_data);
$tpl->setVariable("SCRIPT_CODE", $script_code);
$tpl->setVariable("PLAYERS_NUMBER", $players_number);
$tpl->setVariable("P_TACTICS_ID", $p_tactics_id);

$tpl->setVariable("FIELD_LEFT_COORDINATE", $field_left_coordinate);
$tpl->setVariable("FIELD_TOP_COORDINATE", $field_top_coordinate);
// set the team name
$tpl->setVariable("TEAM_NAME", $team_name);

/**
 * set the team instruction and player instruction
 */
// transferSelectedIndex2Value($team_instructions_arr);
produceInstructions($db, $tpl, $TPL_PATCH, $p_tactics_id, $team_instructions_arr, $tpop_player_id_name_arr);

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();


//----------------------------------------------------------------------------	
// 自定义函数部分
//----------------------------------------------------------------------------
/**
 * form the place options from result set
 *
 * @param [rs]		the result set
 *
 * @return  $place_options_arr
 * $place_options_arr["place_options"] = $place_options;
 * $place_options_arr["place_options_sub"] = $place_options_sub;
 * $place_options_arr["place_options_rest"] = $place_options_rest;
 */	
function getPlaceOptionsArr($rs, $position_place_arr)
{
	$place_options_arr = array();
	$place_options = array();
	$place_options_sub = array();
	$place_options_rest = array();
	
	
	$player_num = 0;
	$have_GK = false;
	for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext()) {
	    $position_place = $rs->fields['position_place'];
	    if ($position_place != NULL && $position_place == 0) {
	    	// 门将
	    	$place_options[$position_place] = "GK";
	    	++ $player_num;
	    	$have_GK = true;
	    }
	    else if ($position_place>=1 && $position_place<=25) {
	    	
	    	$place_options[$position_place] = $position_place_arr[$position_place];
	    	
	    	++ $player_num;
	    	
	    }
	}	

	//
	$sub_num = 0;
	$rest_num = 0;
	for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext()) {
	    $position_place = $rs->fields['position_place'];
	    if ($position_place>=100) {
	    	++ $player_num;
	    	
	    	if ($player_num <= 11) {
	    		// POF
	    		if (!$have_GK) {
	    			// there is not GK in formation
	    			$position_place = 0;
	    			$place_options[$position_place] = "GK";
	    			$have_GK = true;
	    		}
	    		else {
	    			$position_place = rand(1, 25);
	    	
	    			while(!isPlaceExist($place_options, $position_place)) {
	    				$position_place = rand(1, 25);
	    			} 
	    			
	    			$place_options[$position_place] = $position_place_arr[$position_place];	    			
	    		}		
	    	}
	    	else {
	    		++ $sub_num;
		    	if ($sub_num == 1) {
		    		// 替补门将
		    		$place_options_sub[100] = "subGK";
		    	}
		    	else if ($sub_num <= 5) {
		    		// 5 sub
		    		$sub_select_key = 100 + ($sub_num-1);	
		    		$place_options_sub[$sub_select_key] = "sub" . ($sub_num-1);
		    	}	   
		    	else {
		    		// rest
		    		++ $rest_num;
		    		$rest_select_key = 500 + $rest_num;
		    		$place_options_rest[$rest_select_key] = "rest" . $rest_num;
		    	} 	
	    	}
	    		
	    }
	}	
	
	//
	for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext()) {
	    $position_place = $rs->fields['position_place'];
	    if ($position_place == NULL) {
	    	++ $player_num;
	    	
	    	if ($player_num <= 11) {
	    		// POF	
	    		if (!$have_GK) {
	    			// there is not GK in formation
	    			$position_place = 0;
	    			$place_options[$position_place] = "GK";
	    			$have_GK = true;
	    		}
	    		else {
	    			$position_place = rand(1, 25);
	    			while(!isPlaceExist($place_options, $position_place)) {
	    				$position_place = rand(1, 25);
	    			} 
	    			
	    			$place_options[$position_place] = $position_place_arr[$position_place];	    			
	    		}
	    	}
	    	else {
	    		++ $sub_num;
		    	if ($sub_num == 1) {
		    		// 替补门将
		    		$place_options_sub[100] = "subGK";
		    	}
		    	else if ($sub_num <= 5) {
		    		// 5 sub
		    		$sub_select_key = 100 + ($sub_num-1);	
		    		$place_options_sub[$sub_select_key] = "sub" . ($sub_num-1);
		    	}	   
		    	else {
		    		// rest
		    		++ $rest_num;
		    		$rest_select_key = 500 + $rest_num;
		    		$place_options_rest[$rest_select_key] = "rest" . $rest_num;
		    	} 	
	    	}
	    }	   
	}
	
	
	$place_options_arr["place_options"] = $place_options;
	$place_options_arr["place_options_sub"] = $place_options_sub;
	$place_options_arr["place_options_rest"] = $place_options_rest;
	
	return $place_options_arr;
}


// check whether the $position_place is exist in $place_options
function isPlaceExist($place_options, $position_place)
{
	$is_exist = false;
	
	foreach($place_options as $key=>$text) {
		if ($position_place == $key) {
			$is_exist = true;
			break;
		}	
	}
	
	return is_exist;
}



/**
 * 构造 place_select
 *
 * @param [tpl]						php 模板变量
 * @param [place_options]		 	在阵型中的位置
 * @param [place_options_sub]		替补
 * @param [place_options_rest]		Rest
 * @param [position_place]			位置
 *
 * @return  no
 */	
function producePlaceSelect($tpl, $place_options, $place_options_sub, $place_options_rest, $position_place)
{
	// 首先显示在阵型中的位置
	foreach ($place_options as $key => $value) {
		$tpl->setCurrentBlock("place_select") ;
		
		$tpl->setVariable("PLACE_OPTION_VALUE", $key);
		$tpl->setVariable("PLACE_OPTION_TEXT", $value);
		if ($key == $position_place){
			$tpl->setVariable("PLACE_SELECTED", "selected");
		}
		
		$tpl->parseCurrentBlock("place_select") ;
	}
	// 然后显示替补
	foreach ($place_options_sub as $key => $value){
		$tpl->setCurrentBlock("place_select") ;
		
		$tpl->setVariable("PLACE_OPTION_VALUE", $key);
		$tpl->setVariable("PLACE_OPTION_TEXT", $value);
		if ($key == $position_place){
			$tpl->setVariable("PLACE_SELECTED", "selected");
		}
		
		$tpl->parseCurrentBlock("place_select") ;
	}
	// 然后显示 Rest
	foreach ($place_options_rest as $key => $value){
		$tpl->setCurrentBlock("place_select") ;
		
		$tpl->setVariable("PLACE_OPTION_VALUE", $key);
		$tpl->setVariable("PLACE_OPTION_TEXT", $value);
		if ($key == $position_place){
			$tpl->setVariable("PLACE_SELECTED", "selected");
		}
		
		$tpl->parseCurrentBlock("place_select") ;
	}
}

/**
 * $team_instructions_arr中的key_man, target_man, penalty存放的是selectedIndex，
 * 现在要把这些selectedIndex转化为value，然后重新存放在$team_instructions_arr中
 *
 * @param [team_instructions_arr]			里面有 key_man, target_man, penalty
 *
 * @return  no
 */	
function transferSelectedIndex2Value(&$team_instructions_arr)
{
	$key_man_SI 	= $team_instructions_arr["use_key_man"];
	$target_man_SI 	= $team_instructions_arr["use_target_man"];
	
	
	
	if ($key_man_SI == "1") {
		$team_instructions_arr["use_key_man"] = "1";	
	}
	else {
		$team_instructions_arr["use_key_man"] = "0";	
	}
	
	if ($target_man_SI == "1") {
		$team_instructions_arr["use_target_man"] = "1";	
	}
	else {
		$team_instructions_arr["use_target_man"] = "0";	
	}
		
	
	
}


/**
 * 构造每个 instruction
 *
 * @param [tpl]						php 模板变量
 * @param [tpl_instruction]		 	php 模板变量: tpl_instruction
 *
 * @return  no
 */	
function produceInstructions($db, $tpl, $TPL_PATCH, $p_tactics_id, $team_instructions_arr, $tpop_player_id_name_arr)
{ 	
	// team instruction
	$template_file_name = 'team_instruction_tactics.tpl.php';
	$instruction_id = 'instruction_team';
	$display_flag = '';
	produceTeamInstruction($tpl, $TPL_PATCH, $template_file_name, 
					$instruction_id, $display_flag, $team_instructions_arr, 
					$tpop_player_id_name_arr);
	
	// player instruction
	$player_instruction_select_arr = getPlayerInstructionSelect();
	$player_instruction_arr = getPlayerInstruction($db, $p_tactics_id);	
	foreach ($player_instruction_arr as $pop_id => $player_instruction) {
		
		$template_file_name = 'player_instruction_tactics.tpl.php';
		$display_flag = 'none';
		
		producePlayerInstruction($tpl, $TPL_PATCH, $template_file_name, 
								 $display_flag, $pop_id, $player_instruction, 
								 $player_instruction_select_arr);
	}

}

function produceTeamInstruction($tpl, $TPL_PATCH, $template_file_name, 
								$instruction_id, $display_flag, $team_instructions_arr,
								$tpop_player_id_name_arr)
{ 	
	$team_instructions_select = getTeamInstructionSelect($tpop_player_id_name_arr);
	
	$tpl->setCurrentBlock("instruction_div") ;
	// tpl_instruction
	$tpl_instruction = new HTML_Template_ITX($TPL_PATCH); 
	$tpl_instruction->loadTemplatefile($template_file_name, true, true); 	
	$tpl_instruction->setVariable('SPACE', " "); 	
	foreach($team_instructions_arr as $key=>$value) {
		$block_name = $key . "_select";		
		
		$select_arr = $team_instructions_select[$key]; 
		foreach($select_arr as $select_value => $select_text) {
			$tpl_instruction->setCurrentBlock($block_name) ;
			$tpl_instruction->setVariable("OPTION_VALUE", $select_value) ;
			$tpl_instruction->setVariable("OPTION_TEXT", $select_text) ;
			if ($value == $select_value) {
				$tpl_instruction->setVariable("OPTION_SELECTED", "selected") ;
			}
			$tpl_instruction->parseCurrentBlock($block_name) ;
		}
	}
	
	$tpl->setVariable("INSTRUCTION_DIV_CONTENT", $tpl_instruction->get()) ;	
	$tpl->setVariable("INSTRUCTION_DIV_ID", $instruction_id) ;
	$tpl->setVariable("INSTRUCTION_DIV_DISPLAY", $display_flag) ;
	
	$tpl->parseCurrentBlock("instruction_div") ;
}

function getTeamInstructionSelect($tpop_player_id_name_arr)
{
	// 以后可以通过查询数据库中的静态数据来实现
	
	$team_instructions_select = array();

	$passing_style_select = array (
							"0"=>"Passmixed", "1"=>"Passshort", "2"=>"Passlong", 
							"3"=>"Passdirect"
							);
	$mentality_select = array (
							"-2"=>"UltraDefensive", "-1"=>"Defensive", "0"=>"Normal", 
							"1"=>"Attack", "2"=>"UltraAttack"
							);
	$off_focus_select = array (
							"4"=>"Leftwing", "3"=>"Middle", "2"=>"Rightwing", 
							"1"=>"Bothwing", "0"=>"Sidemixed"
							);
							
	$normal_select = array (
							"0"=>"Never", "25"=>"Seldom", "50"=>"Normal", 
							"75"=>"Much", "100"=>"Always"
							);
	$use_select = array (
							"0"=>"false", "1"=>"true"
							);
							
	$offside_trip_select = $normal_select;
	$dline_push_up_select = $normal_select;
	$counter_attack_select = $normal_select;
	$pressing_select = array (
							"25"=>"Own Area", "50"=>"Own Half", "75"=>"All Over"
							);
	$tackling_select = array (
							"25"=>"Own Area", "50"=>"Own Half", "75"=>"All Over"
							);
	$tempo_select = array (
							"0"=>"Quick", "1"=>"Normal", "2"=>"Slow"
							);
							
							
	$use_key_man_select = $use_select;
	$use_target_man_select = $use_select;
	
	$team_instructions_select["passing_style"] = $passing_style_select;
	$team_instructions_select["mentality"] = $mentality_select;
	$team_instructions_select["off_focus"] = $off_focus_select;
	
	$team_instructions_select["offside_trip"] = $offside_trip_select;
	$team_instructions_select["dline_push_up"] = $dline_push_up_select;
	$team_instructions_select["counter_attack"] = $counter_attack_select;
	$team_instructions_select["pressing"] = $pressing_select;
	$team_instructions_select["tackling"] = $tackling_select;
	$team_instructions_select["tempo"] = $tempo_select;
	
	$team_instructions_select["use_key_man"] = $use_key_man_select;
	$team_instructions_select["use_target_man"] = $use_target_man_select;	
	
	return $team_instructions_select;
}

function producePlayerInstruction($tpl, $TPL_PATCH, $template_file_name, 
					$display_flag, $pop_id, $player_instruction, 
					$player_instruction_select_arr)
{ 
	$instruction_id = 'instruction_' . $pop_id;
	
	$tpl->setCurrentBlock("instruction_div") ;
	// tpl_instruction
	$tpl_instruction = new HTML_Template_ITX($TPL_PATCH); 
	$tpl_instruction->loadTemplatefile($template_file_name, true, true); 	
	$tpl_instruction->setVariable('SPACE', " "); 	
	$tpl_instruction->setVariable("POP_INDEX", $pop_id) ;
	// 在这里对其他select的初始化
	foreach($player_instruction as $key => $value) {
		$block_name = $key . "_select";		
		
		$select_arr = array();
		if ($key == "pressing") {
			$select_arr = $player_instruction_select_arr["pressing_select_arr"];
		}
		else if ($key == "tackling") {
			$select_arr = $player_instruction_select_arr["tackling_select_arr"];
		}
		else if ($key == "passing_style") {
			$select_arr = $player_instruction_select_arr["passing_style_select_arr"];
		}
		else {
			$select_arr = $player_instruction_select_arr["common_select_arr"];
		}	
		
		foreach($select_arr as $select_value => $select_text) {
			$tpl_instruction->setCurrentBlock($block_name) ;
			$tpl_instruction->setVariable("OPTION_VALUE", $select_value) ;
			$tpl_instruction->setVariable("OPTION_TEXT", $select_text) ;
			if ($value == $select_value) {
				$tpl_instruction->setVariable("OPTION_SELECTED", "selected") ;
			}
			$tpl_instruction->parseCurrentBlock($block_name) ;
		}	
	}
	
	// init the "reset_select"
	$reset_select_arr = $player_instruction_select_arr["reset_select_arr"];
	foreach($reset_select_arr as $select_value => $select_text) {
		$tpl_instruction->setCurrentBlock("reset_select") ;
		$tpl_instruction->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_instruction->setVariable("OPTION_TEXT", $select_text) ;
		if ($pop_id == $select_value) {
			$tpl_instruction->setVariable("OPTION_SELECTED", "selected") ;
		}
		$tpl_instruction->parseCurrentBlock("reset_select") ;
	}
	$tpl->setVariable("INSTRUCTION_DIV_CONTENT", $tpl_instruction->get()) ;	
	$tpl->setVariable("INSTRUCTION_DIV_ID", $instruction_id) ;
	$tpl->setVariable("INSTRUCTION_DIV_DISPLAY", $display_flag) ;
	
	$tpl->parseCurrentBlock("instruction_div") ;
	
}

// get the player instruction from database
function getPlayerInstruction($db, $p_tactics_id)
{
	$instruction_arr = array();
	$instruction = array();
	
	// 
	$query = sprintf( 
			 	" SELECT pop_id, mx, my, forward_run, run_with_ball, long_shot, hold_the_ball, " .
			 	" through_pass, crossing, pressing, tackling, passing_style " .
			 	" FROM player_instruction " .
			 	" WHERE tactics_id='%s' " .
			 	" ORDER BY pop_id DESC " , 
			 	$p_tactics_id
			 	);

	$rs = &$db->Execute($query);
	
	if (!$rs) {
	    print "Database error.";
	    exit(0);
	}
	else for (; !$rs->EOF; $rs->MoveNext()) {
		$instruction['mx'] = $rs->fields['mx'];
		$instruction['my'] = $rs->fields['my'];
		
		$instruction['forward_run'] = $rs->fields['forward_run'];
		$instruction['run_with_ball'] = $rs->fields['run_with_ball'];
		$instruction['long_shot'] = $rs->fields['long_shot'];
		$instruction['hold_the_ball'] = $rs->fields['hold_the_ball'];
		$instruction['through_pass'] = $rs->fields['through_pass'];
		$instruction['crossing'] = $rs->fields['crossing'];
		
		$instruction['pressing'] = $rs->fields['pressing'];
		$instruction['tackling'] = $rs->fields['tackling'];
		$instruction['passing_style'] = $rs->fields['passing_style'];
		   
		$instruction_arr[$rs->fields['pop_id']] = $instruction;
	}	
	
	return $instruction_arr;
}

// get player instruction select (value, text)
function getPlayerInstructionSelect()
{
	// 以后可以通过查询数据库中的静态数据来实现
	
	$player_instructions_select = array();
	
	$common_select_arr = 
				array( "0" => "never",
					   "25" => "seldom",
					   "50" => "normal",
					   "75" => "much");
					   
	$pressing_select_arr = array (
							  "-1"=>"As Team", "25"=>"Own Area", 
							  "50"=>"Own Half", "75"=>"All Over");
							  
	$tackling_select_arr = array (
							  "-1"=>"As Team", "25"=>"Own Area", 
							  "50"=>"Own Half", "75"=>"All Over");
	$passing_style_select_arr = array (
							"-1"=>"As Team", "0"=>"Passmixed", 
							"1"=>"Passshort", "2"=>"Passlong", 
							"3"=>"Passdirect");
	$reset_select_arr = array (
							  "F"=>"F", "S"=>"S", "W"=>"W",
							  "AM"=>"AM", "M"=>"M", "DM"=>"DM",
							  "SB"=>"SB", "CB"=>"CB");
							  
	
	$player_instructions_select["common_select_arr"] 		= $common_select_arr;
	$player_instructions_select["pressing_select_arr"] 		= $pressing_select_arr;
	$player_instructions_select["tackling_select_arr"] 		= $tackling_select_arr;
	$player_instructions_select["passing_style_select_arr"] = $passing_style_select_arr;
	$player_instructions_select["reset_select_arr"] 		= $reset_select_arr;
	
	
	return $player_instructions_select;
}

?>

