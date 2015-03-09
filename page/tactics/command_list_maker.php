<?php

session_start();

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
$tpl->loadTemplatefile('command_list.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from SESSION
//----------------------------------------------------------------------------
$team_id = sql_quote($_SESSION['s_primary_team_id']);

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
produceSelect($tpl);

// --------------------------------
/**
 * get target_diff from Database
 */
//$target_diff = getTargetDiff($db, $team_id);

/**
 * get amount_of_DC_striction and amount_of_SF_striction from Database
 */
//$AmountOfDC_SF_arr = getAmountOfDC_SF($db, $team_id, $tactics_id);


// --------------------------------
/**
 * get player_id_name_arr from Database 
 */
$player_id_name_arr = getPlayerIdNameArr($db, $team_id);

/**
 * get common select arr
 */
$common_select_arr = getCommonSelectArr($player_id_name_arr);

/**
 * 构造设置command_value的div  
 */
produceSetCommandValueDiv($db, $tpl, $TPL_PATCH, $player_id_name_arr);


// --------------------------------
/**
 * get existent_command_list from Database 
 */
$existent_command_list = getExistentCommandList($db, $team_id);
/**
 * display the existent command_list
 */
displayExistentCommandList($db, $tpl, $existent_command_list, $common_select_arr);


// --------------------------------
/**
 * produce common select
 */
produceCommonSelect($db, $tpl, $common_select_arr);



// $tpl->setVariable("TACTICS_ID", $tactics_id );

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// in order to show the template file in every case, add this sentense
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
function produceSelect($tpl)
{
	$command_select_arr = getSelectArr();
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
function getSelectArr()
{
	$command_select_arr = array();
	$command_select_value = array();
	$command_select_selectedIndex = array();

	$target_diff_select = array (
							"-3"=>"-3", "-2"=>"-2", "-1"=>"-1", 
							"0"=>"0", "1"=>"1", "2"=>"2",
							"3"=>"3"
							);
	$time_select = array (
							"0"=>"0", "15"=>"15", "30"=>"30", 
							"45"=>"45", "60"=>"60", "75"=>"75",
							"90"=>"90"
							);
	$cond_select = array (
							"0"=>"drawing", "1"=>"wining", "2"=>"losing", 
							"3"=>"anyscore"
							);
	$type_select = array (
							"9201"=>"POPSwitch", "9202"=>"POPChangeD", "9203"=>"POPChangeN", 
							"9204"=>"Substitution", "9205"=>"SubstitutionN", "9240"=>"SetTactics"
							);
	
	// command_select_value							
	$command_select_value["target_diff_select"] = $target_diff_select;
	$command_select_value["time_select"] = $time_select;
	$command_select_value["cond_select"] = $cond_select;
	$command_select_value["type_select"] = $type_select;
	
	// command_select_selectedIndex
	$command_select_selectedIndex["target_diff_select"] = 3;
	$command_select_selectedIndex["time_select"] = 0;
	$command_select_selectedIndex["cond_select"] = 0;
	$command_select_selectedIndex["type_select"] = 0;
	
	$command_select_arr["command_select_value"] = $command_select_value;
	$command_select_arr["command_select_selectedIndex"] = $command_select_selectedIndex;
	
	return $command_select_arr;
}

/**
 * 从数据库中读取player信息，包括在球场的球员、替补和剩下的球员
 * 然后把它们分别放在数组$player_on_field、$player_sub和$player_rest中
 * 最后再把这三个数组放入数组$player_id_name_arr中，并返回
 * 注意：函数终，取$p_tactics_id的方法不知道是否正确？？？  
 *
 * @param [db]			db	
 * @param [team_id]		team_id	
 *
 * @return  $player_id_name_arr
 */	
function getPlayerIdNameArr($db, $team_id)
{
	$p_tactics_id = "0";  
	$player_id_name_arr = array();		
	$GK_on_field = array();
	$player_on_field = array();
	$GK_sub = array();
	$player_sub = array();
	$player_rest = array();
	
	// 以下这个地方取$p_tactics_id的方法不知道是否正确  
	// team_tactics 
	$query = sprintf(
			" SELECT cur_tactics_id AS p_tactics_id " . 
			" FROM team_tactics " .
			" WHERE team_id='%s' " ,
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
	
	// tactics_detail
	$query = sprintf(
			" SELECT t.position_place, p.player_id, p.custom_given_name AS given_name, " . 
			" p.custom_family_name AS family_name " . 
			" FROM player p left join tactics_detail t on p.player_id=t.player_id AND t.tactics_id='%s' " .
			" WHERE p.team_id='%s' " .
			" ORDER BY t.position_place ASC" ,
			$p_tactics_id, $team_id
			);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
	    print "Database error.";
	    exit(0);
	}
	else {		 		
		// 选择显示在阵型中的球员、替补以及剩下的球员
	    for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext()) {
		    $position_place = $rs->fields['position_place'];
		    
			$player_name = "";
			if ($rs->fields['given_name'] == "") {
				$player_name = $rs->fields['family_name'];
			}
			else {
				$player_name = substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name'];
			}
			
			if ($position_place == null) {
				$player_rest[$rs->fields['player_id']] = $player_name;
			}
			else if (intval($position_place)==0) {
				$GK_on_field[$rs->fields['player_id']] = $player_name;
			}
			else if (intval($position_place) >= 1 && intval($position_place) <= 25){
				// [1, 25]
				$player_on_field[$rs->fields['player_id']] = $player_name;
			}
			else if (intval($position_place)==100) {
				$GK_sub[$rs->fields['player_id']] = $player_name;
			}
			else if (intval($position_place)>=101 && intval($position_place)<=104) {
				// only 4 suber at present, so the upper limit is 104
				// [101, 104]
				$player_sub[$rs->fields['player_id']] = $player_name;
			}
			else {
				$player_rest[$rs->fields['player_id']] = $player_name;
			}
			
			if ($index >= 25) break; // the number of players is less then 25 or equals to 25
		}
		
	}	
	
	$player_id_name_arr["player_on_field"] = $player_on_field;
	$player_id_name_arr["player_sub"] = $player_sub;
	$player_id_name_arr["player_rest"] = $player_rest;
	
	return $player_id_name_arr;
}


/**
 * 初始化不同命令类型下，配置命令值的div界面  
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [TPL_PATCH]			TPL_PATCH	
 * @param [player_id_name_arr]	player_id_name_arr	
 *
 * @return  none
 */	
function produceSetCommandValueDiv($db, $tpl, $TPL_PATCH, $player_id_name_arr)
{
	$player_on_field = $player_id_name_arr["player_on_field"];
	$player_sub = $player_id_name_arr["player_sub"];
	$player_rest = $player_id_name_arr["player_rest"];
	
	
	// POPSwitch
	$template_file_name = "POPSwitch.tpl.php";
	$div_id = "POPSwitch_div";
	$display_flag = "";
	producePOPSwitchDiv($db, $tpl, $TPL_PATCH, $player_on_field, $template_file_name, $div_id, $display_flag);
	
	// POPChangeD
	$template_file_name = "POPChangeD.tpl.php";
	$div_id = "POPChangeD_div";
	$display_flag = "none";
	producePOPChangeDDiv($db, $tpl, $TPL_PATCH, $player_on_field, $template_file_name, $div_id, $display_flag);
	
	// POPChangeN
	$template_file_name = "POPChangeN.tpl.php";
	$div_id = "POPChangeN_div";
	$display_flag = "none";
	producePOPChangeNDiv($db, $tpl, $TPL_PATCH, $player_on_field, $template_file_name, $div_id, $display_flag);
	
	// Substitution
	$template_file_name = "Substitution.tpl.php";
	$div_id = "Substitution_div";
	$display_flag = "none";
	produceSubstitutionDiv($db, $tpl, $TPL_PATCH, $player_on_field, $player_sub, $template_file_name, $div_id, $display_flag);
	
	// SubstitutionN
	$template_file_name = "SubstitutionN.tpl.php";
	$div_id = "SubstitutionN_div";
	$display_flag = "none";
	produceSubstitutionNDiv($db, $tpl, $TPL_PATCH, $player_on_field, $player_sub, $template_file_name, $div_id, $display_flag);
		
	// SetTactics
	$template_file_name = "SetTactics.tpl.php";
	$div_id = "SetTactics_div";
	$display_flag = "none";
	produceSetTacticsDiv($db, $tpl, $TPL_PATCH, $template_file_name, $div_id, $display_flag);
	
}

/**
 * 初始化命令类型为POPSwitch时，配置命令值的div界面  
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [TPL_PATCH]			TPL_PATCH	
 * @param [player_on_field]		player_on_field	
 * @param [template_file_name]	template_file_name	
 * @param [div_id]				div_id	
 * @param [display_flag]		display_flag	
 *
 * @return  none
 */	
function producePOPSwitchDiv($db, $tpl, $TPL_PATCH, $player_on_field, $template_file_name, $div_id, $display_flag)
{ 
	$tpl->setCurrentBlock("set_command_value_div") ;
	// tpl_instruction
	$tpl_child = new HTML_Template_ITX($TPL_PATCH); 
	$tpl_child->loadTemplatefile($template_file_name, true, true); 	
	$tpl_child->setVariable('SPACE', " "); 	
	
	// 对POPer1_select的初始化
	$block_name = "POPSwitch_POPer1_select";		
	foreach($player_on_field as $select_value => $select_text) {
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}	
	
	// 对POPer2_select的初始化
	$block_name = "POPSwitch_POPer2_select";		
	foreach($player_on_field as $select_value => $select_text) {
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}	

	$tpl->setVariable("DIV_CONTENT", $tpl_child->get()) ;	
	$tpl->setVariable("DIV_ID", $div_id) ;
	$tpl->setVariable("DIV_DISPLAY", $display_flag) ;
	
	$tpl->parseCurrentBlock("set_command_value_div") ;
}

/**
 * 初始化命令类型为POPChangeD时，配置命令值的div界面  
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [TPL_PATCH]			TPL_PATCH	
 * @param [player_on_field]		player_on_field	
 * @param [template_file_name]	template_file_name	
 * @param [div_id]				div_id	
 * @param [display_flag]		display_flag	
 *
 * @return  none
 */	
function producePOPChangeDDiv($db, $tpl, $TPL_PATCH, $player_on_field, $template_file_name, $div_id, $display_flag)
{
	$tpl->setCurrentBlock("set_command_value_div") ;
	// tpl_instruction
	$tpl_child = new HTML_Template_ITX($TPL_PATCH); 
	$tpl_child->loadTemplatefile($template_file_name, true, true); 	
	$tpl_child->setVariable('SPACE', " "); 	
	
	// 对POPer_select的初始化
	$block_name = "POPChangeD_POPer_select";		
	foreach($player_on_field as $select_value => $select_text) {
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}	
	
	// 对move_direction_select的初始化		
	$move_direction_arr = array( "4" => "left",
							     "6" => "right",
							     "8" => "up",
							     "2" => "down");
	$block_name = "move_direction_select";
	foreach($move_direction_arr as $select_value => $select_text) {  
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}			

	$tpl->setVariable("DIV_CONTENT", $tpl_child->get()) ;	
	$tpl->setVariable("DIV_ID", $div_id) ;
	$tpl->setVariable("DIV_DISPLAY", $display_flag) ;
	
	$tpl->parseCurrentBlock("set_command_value_div") ;
	
}


/**
 * 初始化命令类型为POPChangeN时，配置命令值的div界面  
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [TPL_PATCH]			TPL_PATCH	
 * @param [player_on_field]		player_on_field	
 * @param [template_file_name]	template_file_name	
 * @param [div_id]				div_id	
 * @param [display_flag]		display_flag	
 *
 * @return  none
 */	
function producePOPChangeNDiv($db, $tpl, $TPL_PATCH, $player_on_field, $template_file_name, $div_id, $display_flag)
{
	$tpl->setCurrentBlock("set_command_value_div") ;
	// tpl_instruction
	$tpl_child = new HTML_Template_ITX($TPL_PATCH); 
	$tpl_child->loadTemplatefile($template_file_name, true, true); 	
	$tpl_child->setVariable('SPACE', " "); 	
	
	// 对POPer_select的初始化
	$block_name = "POPChangeN_POPer_select";		
	foreach($player_on_field as $select_value => $select_text) {
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}	
	
	// 对POPChangeN_row_select的初始化			
	$row_arr = array( "0" => "F",
					  "1" => "AM",
					  "2" => "M",
					  "3" => "DM",
					  "4" => "D");
	$col_arr = array( "0" => "L",
					  "1" => "CL",
					  "2" => "C",
					  "3" => "CR",
					  "4" => "R");
	$block_name = "POPChangeN_row_select";
	foreach($row_arr as $select_value => $select_text) {  
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}			
	
	// 对POPChangeN_row_select的初始化	
	$block_name = "POPChangeN_col_select";
	foreach($col_arr as $select_value => $select_text) {  
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}			

	$tpl->setVariable("DIV_CONTENT", $tpl_child->get()) ;	
	$tpl->setVariable("DIV_ID", $div_id) ;
	$tpl->setVariable("DIV_DISPLAY", $display_flag) ;
	
	$tpl->parseCurrentBlock("set_command_value_div") ;
	
}

/**
 * 初始化命令类型为Substitution时，配置命令值的div界面  
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [TPL_PATCH]			TPL_PATCH	
 * @param [player_on_field]		player_on_field	
 * @param [player_sub]			player_sub	
 * @param [template_file_name]	template_file_name	
 * @param [div_id]				div_id	
 * @param [display_flag]		display_flag	
 *
 * @return  none
 */	
function produceSubstitutionDiv($db, $tpl, $TPL_PATCH, $player_on_field, $player_sub, $template_file_name, $div_id, $display_flag)
{
	$tpl->setCurrentBlock("set_command_value_div") ;
	// tpl_instruction
	$tpl_child = new HTML_Template_ITX($TPL_PATCH); 
	$tpl_child->loadTemplatefile($template_file_name, true, true); 	
	$tpl_child->setVariable('SPACE', " "); 	
	
	// 对Substitution_POPer_select的初始化
	$block_name = "Substitution_POPer_select";		
	foreach($player_on_field as $select_value => $select_text) {
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}	
	
	// 对Substitution_Suber_select的初始化
	$block_name = "Substitution_Suber_select";		
	foreach($player_sub as $select_value => $select_text) {
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}	

	$tpl->setVariable("DIV_CONTENT", $tpl_child->get()) ;	
	$tpl->setVariable("DIV_ID", $div_id) ;
	$tpl->setVariable("DIV_DISPLAY", $display_flag) ;
	
	$tpl->parseCurrentBlock("set_command_value_div") ;
	
}


/**
 * 初始化命令类型为SubstitutionN时，配置命令值的div界面  
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [TPL_PATCH]			TPL_PATCH	
 * @param [player_on_field]		player_on_field	
 * @param [player_sub]			player_sub	
 * @param [template_file_name]	template_file_name	
 * @param [div_id]				div_id	
 * @param [display_flag]		display_flag	
 *
 * @return  none
 */	
function produceSubstitutionNDiv($db, $tpl, $TPL_PATCH, $player_on_field, $player_sub, $template_file_name, $div_id, $display_flag)
{
	$tpl->setCurrentBlock("set_command_value_div") ;
	// tpl_instruction
	$tpl_child = new HTML_Template_ITX($TPL_PATCH); 
	$tpl_child->loadTemplatefile($template_file_name, true, true); 	
	$tpl_child->setVariable('SPACE', " "); 	
	
	// 对SubstitutionN_POPer_select的初始化
	$block_name = "SubstitutionN_POPer_select";		
	foreach($player_on_field as $select_value => $select_text) {
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}	
	
	// 对SubstitutionN_Suber_select的初始化
	$block_name = "SubstitutionN_Suber_select";		
	foreach($player_sub as $select_value => $select_text) {
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}	

	// 对SubstitutionN_row_select的初始化		
	$row_arr = array( "0" => "F",
					  "1" => "AM",
					  "2" => "M",
					  "3" => "DM",
					  "4" => "D");	
	$col_arr = array( "0" => "L",
					  "1" => "CL",
					  "2" => "C",
					  "3" => "CR",
					  "4" => "R");
	$block_name = "SubstitutionN_row_select";
	foreach($row_arr as $select_value => $select_text) {  
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}			
	
	// 对SubstitutionN_row_select的初始化	
	$block_name = "SubstitutionN_col_select";
	foreach($col_arr as $select_value => $select_text) {  
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}		
	
	
	$tpl->setVariable("DIV_CONTENT", $tpl_child->get()) ;	
	$tpl->setVariable("DIV_ID", $div_id) ;
	$tpl->setVariable("DIV_DISPLAY", $display_flag) ;
	
	$tpl->parseCurrentBlock("set_command_value_div") ;
	
}



/**
 * 初始化命令类型为SetTactics时，配置命令值的div界面  
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [TPL_PATCH]			TPL_PATCH	
 * @param [player_sub]			player_sub	
 * @param [template_file_name]	template_file_name	
 * @param [div_id]				div_id	
 * @param [display_flag]		display_flag	
 *
 * @return  none
 */	
function produceSetTacticsDiv($db, $tpl, $TPL_PATCH, $template_file_name, $div_id, $display_flag)
{
	$tpl->setCurrentBlock("set_command_value_div") ;
	// tpl_instruction
	$tpl_child = new HTML_Template_ITX($TPL_PATCH); 
	$tpl_child->loadTemplatefile($template_file_name, true, true); 	
	$tpl_child->setVariable('SPACE', " "); 	
	
	// 对SetTactics_CanTactics_select的初始化	
	$can_tactics_arr = array ("0" => "Can Tactics A",
							  "1" => "Can Tactics B",
							  "2" => "Can Tactics c");

	$block_name = "SetTactics_CanTactics_select";		
	foreach($can_tactics_arr as $select_value => $select_text) {
		$tpl_child->setCurrentBlock($block_name) ;
		$tpl_child->setVariable("OPTION_VALUE", $select_value) ;
		$tpl_child->setVariable("OPTION_TEXT", $select_text) ;
		$tpl_child->parseCurrentBlock($block_name) ;
	}	
	
	$tpl->setVariable("DIV_CONTENT", $tpl_child->get()) ;	
	$tpl->setVariable("DIV_ID", $div_id) ;
	$tpl->setVariable("DIV_DISPLAY", $display_flag) ;
	
	$tpl->parseCurrentBlock("set_command_value_div") ;
	
}


/**
 * get existent_command_list from Database  
 *
 * @param [db]					db	
 * @param [team_id]				team_id	
 *
 * @return  $existent_command_list
 */	
function getExistentCommandList($db, $team_id)
{
	$existent_command_list = array();
	
	
	$query = sprintf(
			" SELECT time, cond, type, " . 
			" par1, par2, par3, " .
			" par4, par5, par6, " .
			" par7, par8, par9 " . 
			" FROM command " .
			" WHERE team_id='%s' " .
			" ORDER BY time ASC" ,
			$team_id
			);
	$rs = &$db->Execute($query);
	
	if (!$rs) {
	    print "Database error.";
	    exit(0);
	}
	else {		
		for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext()) {
			$command_entity = array();
			
			$command_entity["time"] = $rs->fields['time'];
			$command_entity["cond"] = $rs->fields['cond'];
			$command_entity["type"] = $rs->fields['type'];
			$command_entity["par1"] = $rs->fields['par1'];
			$command_entity["par2"] = $rs->fields['par2'];
			$command_entity["par3"] = $rs->fields['par3'];
			$command_entity["par4"] = $rs->fields['par4'];
			$command_entity["par5"] = $rs->fields['par5'];
			$command_entity["par6"] = $rs->fields['par6'];
			$command_entity["par7"] = $rs->fields['par7'];
			$command_entity["par8"] = $rs->fields['par8'];
			$command_entity["par9"] = $rs->fields['par9'];
			
			$existent_command_list[count($existent_command_list)] = $command_entity;
		}
	}
	
	return $existent_command_list;	
}

/**
 * display the existent command_list
 *
 * @param [db]						db	
 * @param [tpl]						tpl
 * @param [TPL_PATCH]				TPL_PATCH	
 * @param [existent_command_list]	existent_command_list	
 * @param [common_select_arr]	common_select_arr
 *
 * @return  $existent_command_list
 */	
function displayExistentCommandList($db, $tpl, $existent_command_list, $common_select_arr)
{
	$command_num = count($existent_command_list);
	$pre_time = "";
	$cmd_list_table_arr = 
						array(
							"0"=>"zero_min_table", "15"=>"fifteen_min_table", "30"=>"thirty_min_table",
				  			"45"=>"fourty_five_min_table", "60"=>"sisty_min_table", "75"=>"seventy_five_min_table",
				  			"90"=>"ninety_min_table"
				  				);
	for ($i=0; $i<$command_num; ++$i) {
		$command_entity = $existent_command_list[$i];
		
		$time = $command_entity["time"];	
		$table_name = $cmd_list_table_arr[$time];
		$block_name = $table_name . "_tr";
		$row_id = $table_name . "_row_" . time() . $i;
		$full_command_value = getFullCommandValue($command_entity);
		$command_display_str = getCommandDisplayStr($command_entity, $common_select_arr);
		
		$tpl->setCurrentBlock($block_name) ;
		$tpl->setVariable("ROW_ID", $row_id) ;
		$tpl->setVariable("FULL_COMMAND_VALUE", $full_command_value) ;
		$tpl->setVariable("COMMAND_DISPLAY_STR", $command_display_str) ;
		$tpl->parseCurrentBlock($block_name) ;
	}
}

/**
 * get full_command_str
 *
 * @param [command_entity]			command_entity
 *
 * @return  $full_command_value
 */	
function getFullCommandValue($command_entity)
{
	$time = $command_entity["time"];
	$cond = $command_entity["conds"];
	$type = $command_entity["type"];
	
	$full_command_value = "";
	
	if ($type == "9201") {
		// POPSwitch
		$full_command_value = $time . "|" . $cond . "|" . $type . "|" .
							  $command_entity["par1"] . "|" . 
							  $command_entity["par2"];
	}
	else if ($type == "9202") {
		// POPChangeD
		$full_command_value = $time . "|" . $cond . "|" . $type . "|" .
							  $command_entity["par1"] . "|" . 
							  $command_entity["par2"];
	}
	else if ($type == "9203") {
		// POPChangeN
		$full_command_value = $time . "|" . $cond . "|" . $type . "|" .
							  $command_entity["par1"] . "|" . 
							  $command_entity["par2"] . "|" . 
							  $command_entity["par3"];
	}
	else if ($type == "9204") {
		// Substitution
		$full_command_value = $time . "|" . $cond . "|" . $type . "|" .
							  $command_entity["par1"] . "|" . 
							  $command_entity["par2"];
	}
	else if ($type == "9205") {
		// SubstitutionN
		$full_command_value = $time . "|" . $cond . "|" . $type . "|" .
							  $command_entity["par1"] . "|" . 
							  $command_entity["par2"] . "|" . 
							  $command_entity["par3"] . "|" . 
							  $command_entity["par4"];
	}
	else if ($type == "9240") {
		// SetTactics
		$full_command_value = $time . "|" . $cond . "|" . $type . "|" .
							  $command_entity["par1"];
	}
	
	return $full_command_value;
}

/**
 * get command_display_str
 *
 * @param [command_entity]			command_entity
 * @param [common_select_arr]		common_select_arr
 *
 * @return  $full_command_value
 */	
function getCommandDisplayStr($command_entity, $common_select_arr)
{
	// common select
	$player_on_field = $common_select_arr["player_on_field"];
	$player_sub = $common_select_arr["player_sub"];
	$move_direction_arr = $common_select_arr["move_direction_arr"];
	$row_arr = $common_select_arr["row_arr"];
	$col_arr = $common_select_arr["col_arr"];
	$can_tactics_arr = $common_select_arr["can_tactics_arr"];
	
	$type = $command_entity["type"];
	
	$command_display_str = "";
	
	if ($type == "9201") {
		// POPSwitch
		$command_display_str = getPlayerNameByPlayerId($player_on_field, $command_entity["par1"]) . 
		                       "<=>" . 
							   getPlayerNameByPlayerId($player_on_field, $command_entity["par2"]);
	}
	else if ($type == "9202") {
		// POPChangeD
		$command_display_str = getPlayerNameByPlayerId($player_on_field, $command_entity["par1"]) . 
							   "=>" . 
							   $move_direction_arr[$command_entity["par2"]];
	}
	else if ($type == "9203") {
		// POPChangeN
		$command_display_str = getPlayerNameByPlayerId($player_on_field, $command_entity["par1"]) . 
							   "=>" . 
							   $row_arr[$command_entity["par2"]] . 
							   $col_arr[$command_entity["par3"]];
	}
	else if ($type == "9204") {
		// Substitution
		$command_display_str = getPlayerNameByPlayerId($player_on_field, $command_entity["par1"]) . 
		                       "<=>" .
		                       getPlayerNameByPlayerId($player_sub, $command_entity["par2"]);
	}
	else if ($type == "9205") {
		// SubstitutionN
		$command_display_str = getPlayerNameByPlayerId($player_on_field, $command_entity["par1"]) . 
		                       "<=>" .
		                       getPlayerNameByPlayerId($player_sub, $command_entity["par2"]) . 
		                       "=>" . 
							   $row_arr[$command_entity["par3"]] . 
							   $col_arr[$command_entity["par4"]];
	}
	else if ($type == "9240") {
		// SetTactics
		$command_display_str = $can_tactics_arr[$command_entity["par1"]] .
							   " => " .
							   "Cur Tactics";
	}
	
	return $command_display_str;
}


/**
 * get the player name by the tpop index
 * the tpop index is the selectedIndex
 *
 * @param [player_arr]			the player arr, the key is player_id, the value is the player_name
 * @param [playerId]		    playerId
 *
 * @return  $full_command_value
 */	
function getPlayerNameByPlayerId($player_id_name_arr, $p_playerId)
{
	//$p_playerId = intval($p_playerId);
	
	$index = 0;
	
	foreach($player_id_name_arr as $player_id => $player_name) {
		if ($p_playerId == $player_id) {	
			return $player_name;
		}
		
		++ $index;
	}
	
	return "";
}

/**
 * produce common select
 *
 * @param [db]						db
 * @param [tpl]						tpl
 * @param [common_select_arr]		common_select_arr
 *
 * @return  void
 */	
function produceCommonSelect($db, $tpl, $common_select_arr)
{
	$player_on_field = $common_select_arr["player_on_field"];
	$player_sub = $common_select_arr["player_sub"];
	$move_direction_arr = $common_select_arr["move_direction_arr"];
	$row_arr = $common_select_arr["row_arr"];
	$col_arr = $common_select_arr["col_arr"];
	$can_tactics_arr = $common_select_arr["can_tactics_arr"];
	
		
	// form the common select
	// common_player_on_field_select
	$block_name = "common_player_on_field_select";
	foreach($player_on_field as $player_id=>$player_name) {		  
		$tpl->setCurrentBlock($block_name) ;
		$tpl->setVariable("OPTION_VALUE", $player_id) ;
		$tpl->setVariable("OPTION_TEXT", $player_name) ;
		$tpl->parseCurrentBlock($block_name) ;
	}
	
	// common_player_sub_select
	$block_name = "common_player_sub_select";
	foreach($player_sub as $player_id=>$player_name) {		  
		$tpl->setCurrentBlock($block_name) ;
		$tpl->setVariable("OPTION_VALUE", $player_id) ;
		$tpl->setVariable("OPTION_TEXT", $player_name) ;
		$tpl->parseCurrentBlock($block_name) ;
	}	
	
	// common_move_direction_arr_select
	$block_name = "common_move_direction_arr_select";
	foreach($move_direction_arr as $key=>$value) {		  
		$tpl->setCurrentBlock($block_name) ;
		$tpl->setVariable("OPTION_VALUE", $key) ;
		$tpl->setVariable("OPTION_TEXT", $value) ;
		$tpl->parseCurrentBlock($block_name) ;
	}
	
	// common_row_arr_select
	$block_name = "common_row_arr_select";
	foreach($row_arr as $key=>$value) {		  
		$tpl->setCurrentBlock($block_name) ;
		$tpl->setVariable("OPTION_VALUE", $key) ;
		$tpl->setVariable("OPTION_TEXT", $value) ;
		$tpl->parseCurrentBlock($block_name) ;
	}
	
	// common_col_arr_select
	$block_name = "common_col_arr_select";
	foreach($col_arr as $key=>$value) {		  
		$tpl->setCurrentBlock($block_name) ;
		$tpl->setVariable("OPTION_VALUE", $key) ;
		$tpl->setVariable("OPTION_TEXT", $value) ;
		$tpl->parseCurrentBlock($block_name) ;
	}
	
	// common_can_tactics_arr_select
	$block_name = "common_can_tactics_arr_select";
	foreach($can_tactics_arr as $key=>$value) {		  
		$tpl->setCurrentBlock($block_name) ;
		$tpl->setVariable("OPTION_VALUE", $key) ;
		$tpl->setVariable("OPTION_TEXT", $value) ;
		$tpl->parseCurrentBlock($block_name) ;
	}
		
					  
}

/**
 * get common select arr
 *
 * @param [db]						db
 * @param [tpl]						tpl
 * @param [player_id_name_arr]		player_id_name_arr
 *
 * @return  void
 */	
function getCommonSelectArr($player_id_name_arr)
{
	
	$player_on_field = $player_id_name_arr["player_on_field"];
	$player_sub = $player_id_name_arr["player_sub"];
	
	$move_direction_arr = array( "4" => "left",
							     "6" => "right",
							     "8" => "up",
							     "2" => "down");		
	$row_arr = array( "0" => "F",
					  "1" => "AM",
					  "2" => "M",
					  "3" => "DM",
					  "4" => "D");
	$col_arr = array( "0" => "L",
					  "1" => "CL",
					  "2" => "C",
					  "3" => "CR",
					  "4" => "R");
					  
	$can_tactics_arr = array ("0" => "Can Tactics A",
							  "1" => "Can Tactics B",
							  "2" => "Can Tactics c");
							  				  
	$common_select_arr = array();
	$common_select_arr["player_on_field"]		= $player_on_field;
	$common_select_arr["player_sub"] 			= $player_sub;
	$common_select_arr["move_direction_arr"] 	= $move_direction_arr;
	$common_select_arr["row_arr"] 				= $row_arr;
	$common_select_arr["col_arr"] 				= $col_arr;
	$common_select_arr["can_tactics_arr"] 		= $can_tactics_arr;
	
	return $common_select_arr;	
}

?>

