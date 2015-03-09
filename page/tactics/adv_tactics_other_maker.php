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
$tpl->loadTemplatefile('adv_tactics_other.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$team_id = sql_quote($_GET['team_id']);



//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
/**
 * script code
 */
$script_code = "
	limit_min_x = 1;
	limit_max_x = 1 + 390 + 153;
	limit_min_y = 2;
	limit_max_y = 2 + 561 + 105;
";


/**
 * produces the prompt divs
 */
$prompt_img_width_value = 30;
$prompt_img_height_value = 30;
for ($r=0; $r<5; ++$r) {
    for ($c=0; $c<5; ++$c) {
	    /*
		$script_code .= "add_drag('prompt_p_" . $r . "_" . $c . "');
        " ; 
		*/
	    
		$tpl->setCurrentBlock('prompt_divs') ;
//		$tpl->setVariable('PROMPT_DIV_NAME', 'prompt_' . $r . '_' . $c);
//		$tpl->setVariable('PROMPT_PLACE_NOTMOVE_DIV_NAME', 'prompt_p_n_' . $r . '_' . $c);
		$tpl->setVariable('PROMPT_PLACE_MOVE_DIV_NAME', 'prompt_p_' . $r . '_' . $c);
		$tpl->setVariable('PLAYER_DIV_NAME', 'player_div_' . $r . '_' . $c);
		$tpl->setVariable('PRMOPT_IMG_WIDTH_VALUE', $prompt_img_width_value);
		$tpl->setVariable('PROMPT_IMG_HEIGHT_VALUE', $prompt_img_height_value);
		switch ($r) {
		case 0:    
			$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_F.gif');
			break;
		case 1:
			$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_AM.gif');
		    break;
		case 2:
			$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_M.gif');
		    break;
		case 3:
			$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_DM.gif');
		    break;
		case 4:
			$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_D.gif');
		    break;			
		}
		$tpl->parseCurrentBlock('prompt_divs') ;
	}
}
/*
$script_code .= "add_drag('prompt_p_5_0');
        " ; 
*/
$tpl->setCurrentBlock('prompt_divs') ;
$tpl->setVariable('PROMPT_PLACE_MOVE_DIV_NAME', 'prompt_p_5_0');
$tpl->setVariable('PLAYER_DIV_NAME', 'player_div_5_0');
$tpl->setVariable('PRMOPT_IMG_WIDTH_VALUE', $prompt_img_width_value);
$tpl->setVariable('PROMPT_IMG_HEIGHT_VALUE', $prompt_img_height_value);
$tpl->setVariable('PROMPT_IMG_NAME', 'prompt_GK.gif');
$tpl->parseCurrentBlock('prompt_divs') ;



/**
 * player value
 */
$player_id_list = ''; 

$left_coordinate = 395;
$place_left_coordinate = $left_coordinate + 2;
$player_name_left_coordinate = $place_left_coordinate + 35;
$o_top_coordinate  = 6 + 16;
$top_coordinate  = $o_top_coordinate;
$place_width = 30;
$player_name_width = 95;
$info_left_coordinate = 50;
$info_top_coordinate = 100;
$img_width = 22;
$img_height = 22;
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

$p_tactics_id = "0";
// tactics 
$query = sprintf(
			" SELECT t.id as p_tactics_id, t.passing_style, t.mentality, t.tactics_symbol " . 
			" FROM tactics t, team_tactics tt " . 
			" WHERE tt.team_id='%s' " . 
			" AND tt.cur_tactics_id=t.id " ,
			$team_id);

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database error.";
    exit(0);
}
else {
	if ($rs->RecordCount() > 0) {
        $p_tactics_id = $rs->fields['p_tactics_id'];
        $passing_style = $rs->fields['passing_style'];
        $mentality = $rs->fields['mentality'];   
        $tactics_symbol = $rs->fields['tactics_symbol'];   
    }
}

// tactics_detail
/*
$query = sprintf(
			" SELECT t.position_place, p.player_id, p.custom_given_name AS given_name, " . 
			" p.custom_family_name AS family_name, p.position, p.height, p.prefer_foot " . 
			" FROM player p " . 
			" left join tactics_detail t on p.player_id=t.player_id AND t.tactics_id='%s' " . 
			" WHERE p.team_id='%s' " . 
			" ORDER BY t.position_place ASC" ,
			$p_tactics_id, $team_id);
*/

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
		" ORDER BY t.position_place ASC" ,
		$p_tactics_id, $team_id
		);
		
$rs = &$db->Execute($query);

$player_property_script = "";
if (!$rs) {
    print "Database error.";
}
else {
	$players_number = $rs->RecordCount();						  
	// 第1次遍历 $rs：
	// 选择显示在阵型中的球员以及替补
	$index = 0;
	$in_field_index = 0;
	$subs_index = 0;
	$others_index = 0;
	$rs->MoveFirst();
    for (; !$rs->EOF; $rs->MoveNext()) {
    	$position_place = $rs->fields['position_place'];
		if ($position_place == null) {
			continue;
		}
		
		// form the $tactics_data
		if ($position_place < 100) {
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
		}
		else if ($position_place >= 100 && $position_place < 500) {
			if ($subs_index == 0) {
			    ++ $subs_index;
			}
			else {
			    $tactics_subs_data .= '&' ;
			}
			$tactics_subs_data .= $rs->fields['player_id'];
		}
		
		// form the add_drag and put it into the "$script_code"
		/*
		$script_code .= "add_drag('p" . $rs->fields['player_id'] . "');
        " ; 
        */
		/*
        $player_property_script .= "ppTable". $rs->fields['player_id']. " = new Array( \"".
        	$rs->fields['given_name']. "\", \"". $rs->fields['family_name']. "\", ". $rs->fields['position']. ", ". 
        	$rs->fields['height']. ", ". $rs->fields['prefer_foot']. " );
        ";
	    */
		$player_property_script .= getPlayerPropertyScript($rs);
	
		// form the "$player_id_list"
		if ($index != 0) {
			$player_id_list .= ',';
		}
		$player_id_list .= $rs->fields['player_id'];
		
		
		// produce the player divs
		$tpl->setCurrentBlock("player_divs") ;
		if ($index % 2 == 0)
		    $tpl->setVariable("PLAYER_DIVS_TR_CLASS", 'gSGRowEven_input');
		else
		    $tpl->setVariable("PLAYER_DIVS_TR_CLASS", 'gSGRowOdd_input');
		$tpl->setVariable("PLAYER_ID", $rs->fields['player_id']);
		$tpl->setVariable("LEFT_COORDINATE", $left_coordinate);
		$tpl->setVariable("PLACE_LEFT_COORDINATE", $place_left_coordinate);
		$tpl->setVariable("PLAYER_NAME_LEFT_COORDINATE", $player_name_left_coordinate);
		$tpl->setVariable("INFO_LEFT_COORDINATE", $info_left_coordinate);
		$tpl->setVariable("INFO_TOP_COORDINATE", $info_top_coordinate);
		$tpl->setVariable("TOP_COORDINATE", $top_coordinate);
		$tpl->setVariable("PLACE_WIDTH_VALUE", $place_width);
		$tpl->setVariable("PLAYER_NAME_WIDTH_VALUE", $player_name_width);
		$tpl->setVariable("WIDTH_VALUE", $img_width);
		$tpl->setVariable("HEIGHT_VALUE", $img_height);
		$tpl->setVariable("IMG_NAME", 'img.gif');
		
		if ($rs->fields['given_name'] == "") {
			$tpl->setVariable("PLAYER_NAME", $rs->fields['family_name']);
		}
		else {
			$tpl->setVariable("PLAYER_NAME", substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name']);
		}
		
		// set the detail of player
		//setDetailPropertyOfPlayer($tpl, $rs);
		
		
		$tpl->parseCurrentBlock("player_divs") ;

		$top_coordinate += 26; 
		++$index;
		if ($index >= 25) break; // the number of players is less then 25 or equals to 25
	}
	// 第2次遍历 $rs：
	// 选择显示不在阵型中的球员
	$rs->MoveFirst();
	$rest_index = 0;
    for (; !$rs->EOF; $rs->MoveNext()) {
	    $position_place = $rs->fields['position_place'];
		if ($position_place != null) {
			continue;
		}
		
		// form the $tactics_others_data
		if ($others_index == 0) {
		    ++ $others_index;
		}
		else {
		   $tactics_others_data .= '&' ;
		}
		$tactics_others_data .= $rs->fields['player_id'];
		
		
		// form the add_drag and put it into the "$script_code"
		/*
		$script_code .= "add_drag('p" . $rs->fields['player_id'] . "');
        " ; 
        */
		/*
        $player_property_script .= "ppTable". $rs->fields['player_id']. " = new Array( \"".
        	$rs->fields['given_name']. "\", \"". $rs->fields['family_name']. "\", ". $rs->fields['position']. ", ". 
        	$rs->fields['height']. ", ". $rs->fields['prefer_foot']. " );
        ";
	    */
		$player_property_script .= getPlayerPropertyScript($rs);
	
		// form the "$player_id_list"
		if ($index != 0) {
			$player_id_list .= ',';
		}
		$player_id_list .= $rs->fields['player_id'];
		
		
		// produce the player divs
		$tpl->setCurrentBlock("player_divs") ;
		if ($index % 2 == 0)
		    $tpl->setVariable("PLAYER_DIVS_TR_CLASS", 'gSGRowEven_input');
		else
		    $tpl->setVariable("PLAYER_DIVS_TR_CLASS", 'gSGRowOdd_input');
		$tpl->setVariable("PLAYER_ID", $rs->fields['player_id']);
		$tpl->setVariable("LEFT_COORDINATE", $left_coordinate);
		$tpl->setVariable("PLACE_LEFT_COORDINATE", $place_left_coordinate);
		$tpl->setVariable("PLAYER_NAME_LEFT_COORDINATE", $player_name_left_coordinate);
		$tpl->setVariable("INFO_LEFT_COORDINATE", $info_left_coordinate);
		$tpl->setVariable("INFO_TOP_COORDINATE", $info_top_coordinate);
		$tpl->setVariable("TOP_COORDINATE", $top_coordinate);
		$tpl->setVariable("PLACE_WIDTH_VALUE", $place_width);
		$tpl->setVariable("PLAYER_NAME_WIDTH_VALUE", $player_name_width);
		$tpl->setVariable("WIDTH_VALUE", $img_width);
		$tpl->setVariable("HEIGHT_VALUE", $img_height);
		$tpl->setVariable("IMG_NAME", 'img.gif');
		
		if ($rs->fields['given_name'] == "") {
			$tpl->setVariable("PLAYER_NAME", $rs->fields['family_name']);
		}
		else {
			$tpl->setVariable("PLAYER_NAME", substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name']);
		}
		
		// set the detail of player
		//setDetailPropertyOfPlayer($tpl, $rs);
		
		
		$tpl->parseCurrentBlock("player_divs") ;

		$top_coordinate += 26; 
		++$index;
		if ($index >= 25) break; // the number of players is less then 25 or equals to 25
    }
}



$script_code .= $player_property_script;
$script_code .= "
  //init_tactics(ft, fl, fx, fy, gw,  gh,  sw, sh, pw, ph, iw,  ih, md, mm, mf, with_count, top_coordinate, left_coordinate, place_left_coordinate, player_name_left_coordinate)
    init_tactics(1,  2,  10, 11, 370, 539, 22, 21, 30, 30, 182, 342, 2,  2, 1,  true, $o_top_coordinate, $left_coordinate, $place_left_coordinate, $player_name_left_coordinate); 
";
$tpl->setVariable("SCRIPT_CODE", $script_code);
$tpl->setVariable("PLAYERS_VALUE", $player_id_list);
$tpl->setVariable("PLAYERS_NUMBER", $players_number);


$tpl->setVariable("TACTICS_DATA", $tactics_data);
$tpl->setVariable("TACTICS_SUBS_DATA", $tactics_subs_data);
$tpl->setVariable("TACTICS_OTHERS_DATA", $tactics_others_data);
$tpl->setVariable("TACTICS_SUBS_COUNT", 4);
$tpl->setVariable("PASSING_STYLE_VALUE", $passing_style);
$tpl->setVariable("MENTALITY_VALUE", $mentality);

// 判断不同的浏览器，如果是 ie 的话，就给 div 加上 iframe，目的是避免层和select的冲突
$browser = browser_info();     
if ($browser == "Internet Explorer") { 
	$iframe_code = '<iframe  style="position:absolute; visibility:inherit; top:0px; left:0px; width:100%; height:100%; z-index:-1; "></iframe>';
	$tpl->setVariable("IFRAME_CODE", $iframe_code);
}
		
//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();


//----------------------------------------------------------------------------	
// the used function
//----------------------------------------------------------------------------	



?>

