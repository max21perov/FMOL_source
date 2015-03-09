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
$tpl->loadTemplatefile('role_list.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$team_id = sql_quote($_GET["team_id"]);

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
/**
 * tactics data
 */
$tactics_id = 1;

// id => name of player
$player_id_name_arr = array();

// --------------------------------
/**
 * 构造页面上的 select  
 */
produceSelect($db, $tpl);

// --------------------------------
/**
 * get target_diff from Database
 */
//$target_diff = getTargetDiff($db, $team_id);





// --------------------------------


// --------------------------------







// --------------------------------



/**
 * player value
 */
$player_id_list = ''; 

$left_coordinate = 2; // 285;
$ic_left_coordinate = $left_coordinate + 2;
$index_left_coordinate =  $ic_left_coordinate + 18;
$player_name_left_coordinate = $index_left_coordinate + 35;
$o_top_coordinate  = 10 + 16;
$top_coordinate  = 10 + 16;
$index_width = 30;
$player_name_width = 120;
$info_left_coordinate = 50;
$info_top_coordinate = 100;
$img_width = 22;
$img_height = 22;
// the width of player_list
$PLAYER_LIST_WIDTH = 400;
$FIELD_LEFT = $PLAYER_LIST_WIDTH + 10;
$FIELD_TOP = 50;
$GRASS_WIDTH = 325;


/**
 * script code
 */
$script_code = "
	limit_min_x = 2;
	limit_max_x = $FIELD_LEFT + $GRASS_WIDTH;
	limit_min_y = 2;
	limit_max_y = 2 + 450 + 85;
";




/**
 * show the training items
 */
$query = sprintf(
			" SELECT p.player_id, p.custom_given_name AS given_name, p.custom_family_name AS family_name, " . 
			" p.position, p.prefer_foot, p.cloth_number, p.age, " . 
			" p.pace, p.power, p.stamina, p.height, " . 
			" p.finishing, p.passing, p.crossing, p.ball_control, p.tackling, p.heading, " . 
			" p.play_making, p.off_awareness, p.def_awareness, p.experience, " .  
			" p.agility, p.reflex, " . 
			" p.handing, p.rushing_out, p.positioning, p.aerial_ability, " .  
			" p.judgment, " .  
			" p.form, p.condition, p.morale, p.happiness " . 
			" FROM player p " . 
			" WHERE p.team_id='%s' " . 
			" ORDER BY p.cloth_number ASC " ,
			$team_id);

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database Error."; // Displays the error message if no results could be returned
	exit(0);
}
else {
	$item_num = 1;
	$players_number = $rs->RecordCount();
    for (; !$rs->EOF; $rs->MoveNext(), $item_num+=1, $top_coordinate += 26) {
    	
		$player_id = $rs->fields['player_id'];
    	    	
        $player_property_script .= getPlayerPropertyScript($rs);
    	
		// form the "$player_id_list"
		if ($item_num != 1) {
			$player_id_list .= ',';
		}
		$player_id_list .= $rs->fields['player_id'];
		// form the add_drag and put it into the "$script_code"
		$script_code .= "add_drag('p" . $rs->fields['player_id'] . "');
        " ; 
	
		
			
		// produce the training_player 	
		$tpl->setCurrentBlock("player_list") ;
		
		$tpl->setVariable("TRAINING_ITEM_TR_CLASS", 'gSGRowOdd_input') ;
		if (intval($rs->fields['cloth_number']) < 10) { 
			$tpl->setVariable("INDEX", "&nbsp;" . $rs->fields['cloth_number']);
		}
		else {
			$tpl->setVariable("INDEX", $rs->fields['cloth_number']);
		}
		$tpl->setVariable("PLAYER_ID", $player_id);
		$tpl->setVariable("LEFT_COORDINATE", $left_coordinate);
		$tpl->setVariable("INDEX_LEFT_COORDINATE", $index_left_coordinate);
		$tpl->setVariable("IC_LEFT_COORDINATE", $ic_left_coordinate);
		$tpl->setVariable("PLAYER_NAME_LEFT_COORDINATE", $player_name_left_coordinate);
		$tpl->setVariable("TOP_COORDINATE", $top_coordinate);
		$tpl->setVariable("INDEX_WIDTH_VALUE", $index_width);
		$tpl->setVariable("PLAYER_NAME_WIDTH_VALUE", $player_name_width);
		$tpl->setVariable("WIDTH_VALUE", $img_width);
		$tpl->setVariable("HEIGHT_VALUE", $img_height);
		$tpl->setVariable("IMG_NAME", 'img.gif');
		
		$player_name = "";
		if ($rs->fields['given_name'] == "") {
			$player_name = $rs->fields['family_name'];
		}
		else {
			$player_name = substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name'];
		}
		$tpl->setVariable("PLAYER_NAME", $player_name);
		
		$player_id_name_arr[$player_id] = $player_name;
		
		$tpl->parseCurrentBlock("player_list") ;
    }
}		

// "ft" is the top of the display ground
$script_code .= $player_property_script;
$script_code .= "
  //init_role_list(ft, fl, fx, fy, gw,  gh,  sw, sh, pw, ph, iw,  ih, md, mm, mf, with_count, top_coordinate, left_coordinate, place_left_coordinate, ic_left_coordinate, player_name_left_coordinate)
    init_role_list($FIELD_TOP,  $FIELD_LEFT,  10, 11, $GRASS_WIDTH, 450, 22, 21, 30, 30, 182, 342, 2,  2, 1,  true, $o_top_coordinate, $left_coordinate, $index_left_coordinate, $ic_left_coordinate, $player_name_left_coordinate); 
";

$tpl->setVariable("SCRIPT_CODE", $script_code);
$tpl->setVariable("PLAYERS_VALUE", $player_id_list);
$tpl->setVariable("PLAYERS_NUMBER", $players_number);

$tpl->setVariable("PLAYER_LIST_WIDTH", $PLAYER_LIST_WIDTH);




// --------------------------------
/**
 * 构造页面上的 role_list  
 */
produceRoleList($db, $tpl, $team_id, $player_id_name_arr);


// handle the info div
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
// 自定义函数部分
//----------------------------------------------------------------------------	
/**
 * 构造页面上的 select
 *
 * @param [tpl]		php 模板变量
 *
 * @return  no
 */		 
function produceSelect($db, $tpl)
{
	$command_select_arr = getSelectArr($db);
	$command_select_value = $command_select_arr["command_select_value"];	
	$command_select_selectedIndex = $command_select_arr["command_select_selectedIndex"];
	
	foreach($command_select_value as $key=>$value) {
		$block_name = $key;		  
		
		$select_entity = $command_select_value[$key];  
		$selectedIndex = $command_select_selectedIndex[$key];  
		$index = 0; 
		foreach($select_entity as $select_value => $select_text) {
			$tpl->setCurrentBlock($block_name) ;
			$tpl->setVariable("OPTION_VALUE", $select_value) ;
			$tpl->setVariable("OPTION_TEXT", $select_text) ;
			if ($index == $selectedIndex) {
				$tpl->setVariable("OPTION_SELECTED", "selected") ;
			}
			$tpl->parseCurrentBlock($block_name) ;
			
			++$index;
		}
	}
}

/**
 * 从数据库中读取静态数据，然后存放在php的数组中，并返回
 *
 * @param []			
 *
 * @return  $command_select_arr
 */	
function getSelectArr($db)
{
	$command_select_arr = array();
	$command_select_value = array();
	$command_select_selectedIndex = array();
	
	$business_type_id = "role_type";
	$role_type_select = getSelectArrFromDB($db, $business_type_id);
	
	
	// command_select_value							
	$command_select_value["role_type_select"] = $role_type_select;
	
	// command_select_selectedIndex
	$command_select_selectedIndex["role_type_select"] = 0;
	
	$command_select_arr["command_select_value"] = $command_select_value;
	$command_select_arr["command_select_selectedIndex"] = $command_select_selectedIndex;
	
	return $command_select_arr;
}


/**
 * 从数据库中读取dict_dictionary，该表中存放着 select 的内容
 * 取出 business_id 和 business_name 
 * 
 *
 * @param [db]			db	
 * @param [team_id]		team_id
 * @param [tactics_id]	tactics_id		
 *
 * @return  $select_arr
 */	
function getSelectArrFromDB($db, $business_type_id)
{
	$select_arr = array();	
	
	// tactics 
	$query = sprintf(
			" SELECT business_id, business_name " . 
			" FROM dict_dictionary " .
			" WHERE business_type_id='%s' ",
			$business_type_id
			);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
	    print "Database error.";
	    exit(0);
	}
	else {		 		
	    for (; !$rs->EOF; $rs->MoveNext()) {
		    $business_id = $rs->fields['business_id'];
		    $business_name = $rs->fields['business_name'];
		    
			$select_arr[$business_id] = $business_name;
		}
		
	}	
	
	
	return $select_arr;
}



/**
 * 构建页面上的 role_list   
 * 
 *
 * @param [db]		db	
 * @param [tpl]		tpl
 * @param [team_id]	team_id		
 *
 * @return  void
 */	
function produceRoleList($db, $tpl, $team_id, $player_id_name_arr)
{   
	// （1）从数据库的表 role 中获取 role_list  
	// a) 取整个 role_list
	$role_list_arr = getRoleListArrFromDB($db, $team_id);
	
	// b) 取整个 role_list 中各种role的数量 
	$role_type_len_arr = getRoleTypeLenArrFromDB($db, $team_id);
	
	
	// （2）从数据库的表 dict_dictionary 中获取 role_type  
	$business_type_id = "role_type";
	$role_type_arr = getSelectArrFromDB($db, $business_type_id);
	$all_role_types = implode($role_type_arr, "|");
	$tpl->setVariable("ALL_ROLE_TYPES", $all_role_types);
	
	// （3）根据数组 $role_type_arr 中的role_type，搜索 $role_list_arr 中的数据
	//      并把它们现实出来
	$role_list_arr_len = count($role_list_arr);
	$role_type_index = 0;
	foreach($role_type_arr as $business_id=>$business_name) {  
		// -----------------------
		// 生成 role_list_tr
		// -----------------------
		$role_type_len = $role_type_len_arr[$business_id];
		$index_in_role_type = 1;
		for ($i=0; $i<$role_list_arr_len; ++$i) {  
			$role_entity = $role_list_arr[$i];
			if ($role_entity["role_id"] == $business_id) {  
				
				$role_id = $role_entity["role_id"];
				$player_id = $role_entity["player_id"];
				$player_name = $player_id_name_arr[$player_id];  
				$row_id = $role_id . "_row_" . time() . $i;
				$index_select_id = "index_select_" . time() . $i;  
				
				// 显示每一个 role_list_tr
				
				// 显示 index_select 的下拉框				
				produceIndexSelectOfRoleList($tpl, $role_type_len, $index_in_role_type);	
				
				// 显示 role_list_tr 下的其他内容
				$tpl->setCurrentBlock("role_list_tr") ;
				
				$tpl->setVariable("ROLE_TYPE", $role_id);
				$tpl->setVariable("PLAYER_ID", $player_id);
				$tpl->setVariable("PLAYER_NAME", $player_name);
				$tpl->setVariable("ROW_ID", $row_id);
				$tpl->setVariable("INDEX_SELECT_ID", $index_select_id);
							
				$tpl->parseCurrentBlock("role_list_tr") ;	
				
				// 增加 $index_in_role_type
				++ $index_in_role_type;	
			}
			
		}
		
		// -----------------------
		// 生成 role_list_table
		// -----------------------
		$tpl->setCurrentBlock("role_list_table") ;
				
		$tpl->setVariable("ROLE_TYPE", $business_id);
		if ($role_type_len == 0) {
			$tpl->setVariable("BLANK_TR_DISPLAY", "block");	
		}
		else {
			$tpl->setVariable("BLANK_TR_DISPLAY", "none");	
		}
		
		if ($role_type_index == 0) {  // 第一个role  
			$tpl->setVariable("ROLE_TYPE_TABLE_DISPLAY", "block");	
		}
		else {
			$tpl->setVariable("ROLE_TYPE_TABLE_DISPLAY", "none");	
		}
			
		$tpl->parseCurrentBlock("role_list_table") ;
		
		
		// 增加$role_type_index
		++ $role_type_index;
		
	}
	
	
}

/**
 * 从数据库中获取 role_list   
 * 
 *
 * @param [db]		db	
 * @param [team_id]	team_id		
 *
 * @return  $role_list_arr
 */	
function getRoleListArrFromDB($db, $team_id)
{
	$role_list_arr = array();
	
	$query = sprintf(
			" SELECT player_id, role_id, role_priority " . 
			" FROM role " .
			" WHERE team_id='%s' " .
			" ORDER BY role_id, role_priority " ,
			$team_id
			);

	$rs = &$db->Execute($query);
	
	if (!$rs) {
	    print "Database error.";
	    exit(0);
	}
	else {		 		
	    for (; !$rs->EOF; $rs->MoveNext()) {
		    
		    $role_entity = array();
		    
			$role_entity["player_id"] = $rs->fields['player_id'];
			$role_entity["role_id"] = $rs->fields['role_id'];
			$role_entity["role_priority"] = $rs->fields['role_priority'];
			
			$role_list_arr[count($role_list_arr)] = $role_entity;
			
		}
		
	}	
		
	return $role_list_arr;
		
}

/**
 * 从数据库中获取 role_list   
 * 
 *
 * @param [db]		db	
 * @param [team_id]	team_id		
 *
 * @return  $role_type_len_arr
 */	
function getRoleTypeLenArrFromDB($db, $team_id)
{
	$role_type_len_arr = array();
	
	$query = sprintf(
			" SELECT role_id, count(1) AS len " . 
			" FROM role " .
			" WHERE team_id='%s' " .
			" GROUP BY role_id " ,
			$team_id
			);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
	    print "Database error.";
	    exit(0);
	}
	else {		 		
	    for (; !$rs->EOF; $rs->MoveNext()) {
	    	
		    $role_type_len_arr[$rs->fields['role_id']] = $rs->fields['len'];
		    			
		}		
	}	
		
	return $role_type_len_arr;
		
}

/**
 * 在页面上，对 role_list 中的每一个role，生成其中的index_select  
 * 
 *
 * @param [role_type_len]		role_list 中，每一个role_type的role的数量  	
 * @param [index_in_role_type]	当前role在 role_list 中，每一个role_type下的序号，从 1 开始算	
 *
 * @return  void
 */	
function produceIndexSelectOfRoleList($tpl, $role_type_len, $index_in_role_type)
{
	for ($i=1; $i<=$role_type_len; ++$i) {
		$tpl->setCurrentBlock("index_select") ;
		
		$tpl->setVariable("OPTION_VALUE", $i);
		$tpl->setVariable("OPTION_TEXT", $i);
		
		if ($index_in_role_type == $i)
			$tpl->setVariable("OPTION_SELECTED", "selected");
		
		$tpl->parseCurrentBlock("index_select") ;	
	}
	
}






/**
 * get the player name by the tpop index
 * the tpop index is the selectedIndex
 *
 * @param [player_arr]			the player arr, the key is player_id, the value is the player_name
 * @param [selectedIndex]		selectedIndex, the index in the arr
 *
 * @return  $full_command_value
 */	
function getPlayerNameBySelectedIndex($player_id_name_arr, $selectedIndex)
{
	$selectedIndex = intval($selectedIndex);
	
	$index = 0;
	
	foreach($player_id_name_arr as $player_id => $player_name) {
		if ($selectedIndex == $index) {	
			return $player_name;
		}
		
		++ $index;
	}
	
	return "";
}



?>

