<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");


if($_SERVER["REQUEST_METHOD"] != "POST")    //check the page is request by method "POST"
{	
	// go back to the page "tactics_cur.php"
	goToPageInTime(0, "/fmol/page/tactics/tactics_cur.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("stdFormationChange" == $myaction) {
	performStdFormationChange($db, DOCUMENT_ROOT);
}
else if ("saveTactics" == $myaction) {
	performSaveTactics($db, DOCUMENT_ROOT);
}
else if ("saveAdvTactics" == $myaction) {
	performSaveAdvTactics($db, DOCUMENT_ROOT);
}
else if ("copyToCurTactics" == $myaction) {
	performCopyToCurTactics($db, DOCUMENT_ROOT);
}
else {
	$return_page_url = sql_quote($_POST["return_page_url"]);
	if ($return_page_url == "")
		goToPageInTime(0, "/fmol/page/tactics/tactics_cur.php");
	else 
		goToPageInTime(0, $return_page_url);
		
}

//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
	/**
	 * 当standard_tactics 发生改变时触发的事件（注意：这时会自动将战术保存）
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performStdFormationChange($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$tactics_symbol = sql_quote($_POST["standard_tactics"]);
	$passing_style = sql_quote($_POST["passing_style"]);
	$mentality = sql_quote($_POST["mentality"]);
	$p_tactics_id = sql_quote($_POST["p_tactics_id"]);
	$return_page_url = sql_quote($_POST["return_page_url"]);

	// $team_instructions
	$team_instructions = array();
	$team_instructions["passing_style"] = $passing_style;
	$team_instructions["mentality"] = $mentality;
	
	
	// 根据 $tactics_symbol 从 std_formation 表中得到 $positions
	$positions = array();
	$returnValue = getStdFormation($db, $tactics_symbol, $positions);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// 根据$team_id 和 $p_tactics_id 从 tactics_detail 表中得到 $player_ids
	$player_ids = array();
	$returnValue = getPlayer_idsInTactics_detail($db, $team_id, $p_tactics_id, $player_ids);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// 得到 $player_id_positions
	$player_id_positions = array();
	$sub_num = 0;
	for ($i=0; $i<count($player_ids); ++$i) {
		if ($i < count($positions)) {
			$player_id = $player_ids[$i];
			$position = $positions[$i];
			$player_id_positions[$player_id] = $position;
		}
		else {
			$position = $sub_num + 100;
			$player_id = $player_ids[$i];
			$player_id_positions[$player_id] = $position;
			++ $sub_num;
		}	
	}

	// 具体实现 tactics 的保存
	implementSaveTactics($db, $document_root, $team_id, 
			$tactics_symbol, $p_tactics_id, 
			$player_id_positions, $team_instructions, NULL, NULL);
			
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, $return_page_url);	
	
}

	/**
	 * 保存阵型
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performSaveTactics($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$tactics_symbol = sql_quote($_POST["standard_tactics"]);
	$p_tactics_id = sql_quote($_POST["p_tactics_id"]);
	$return_page_url = sql_quote($_POST["return_page_url"]);
	$passing_style = sql_quote($_POST["passing_style"]);
	$mentality = sql_quote($_POST["mentality"]);
	$off_focus = sql_quote($_POST["off_focus"]);
	
	$offside_trip = sql_quote($_POST["offside_trip"]);
	$dline_push_up = sql_quote($_POST["dline_push_up"]);
	$counter_attack = sql_quote($_POST["counter_attack"]);
	$pressing = sql_quote($_POST["pressing"]);
	$tackling = sql_quote($_POST["tackling"]);
	$tempo = sql_quote($_POST["tempo"]);
	
	$use_key_man = sql_quote($_POST["use_key_man"]);
	$use_target_man = sql_quote($_POST["use_target_man"]);

	// team_instructions
	$team_instructions = array();
	$team_instructions["passing_style"] = $passing_style;
	$team_instructions["mentality"] = $mentality;
	$team_instructions["off_focus"] = $off_focus;
	$team_instructions["offside_trip"] = $offside_trip;
	$team_instructions["dline_push_up"] = $dline_push_up;
	$team_instructions["counter_attack"] = $counter_attack;
	$team_instructions["pressing"] = $pressing;
	$team_instructions["tackling"] = $tackling;
	$team_instructions["tempo"] = $tempo;
	
	$team_instructions["use_key_man"] = $use_key_man;
	$team_instructions["use_target_man"] = $use_target_man;

	// 得到 $player_id_positions
	$player_id_positions = array();
	
	// get the $instructions from post
	$player_instructions = array();
	$player_instructions["forward_run"] = array();
	$player_instructions["run_with_ball"] = array();
	$player_instructions["long_shot"] = array();
	$player_instructions["hold_the_ball"] = array();
	$player_instructions["through_pass"] = array();
	$player_instructions["crossing"] = array();
	
	$player_instructions["pressing"] = array();
	$player_instructions["tackling"] = array();
	$player_instructions["passing_style"] = array();
	$instruction_prefix = array(
							"forward_run_", "run_with_ball_", "long_shot_", 
							"hold_the_ball_", "through_pass_", "crossing_",
							"pressing_", "tackling_", "passing_style_", 
							);
	foreach($_POST as $varname=>$value) { 
		// place_select_
    	if (substr($varname, 0, 13) == "place_select_") {
    		$player_id = substr($varname, 13, strlen($varname));
    		$position = $value;
    		
    		if ($position < 500) {
    			$player_id_positions[$player_id] = $position;
    		}
    	}
    	
    	// player_instructions
    	else {
    		foreach($instruction_prefix as $prefix_index=>$prefix_value) {
    			if (substr($varname, 0, strlen($prefix_value)) == $prefix_value) {
    				$pop_id = substr($varname, strlen($prefix_value), strlen($varname));
    				$pop_value = $value;
    				
    				$instruction_name = substr($prefix_value, 0, strlen($prefix_value)-1); 
    				$player_instructions[$instruction_name][$pop_id] = $pop_value;  
    				
    				break;
    			}
    		}
    	}
    }
  
    
  
    
	// 具体实现 tactics 的保存
	implementSaveTactics($db, $document_root, $team_id, 
			$tactics_symbol, $p_tactics_id, 
			$player_id_positions, $team_instructions, $player_instructions, NULL);
			
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, $return_page_url);	
}

/**
 * 保存adv阵型
 *
 * @param [db]				db
 * @param [document_root]	document_root
 *
 * @return return true or false
 */	
function performSaveAdvTactics($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$tactics_symbol = sql_quote($_POST["standard_tactics"]);
	$passing_style = sql_quote($_POST["passing_style"]);
	$mentality = sql_quote($_POST["mentality"]);
	$p_tactics_id = sql_quote($_POST["p_tactics_id"]);
	$data_str = sql_quote($_POST['data']);
	$subs_data_str = sql_quote($_POST['subs_data']);
	$others_data_str = sql_quote($_POST['others_data']);
	$tactics_run_data_str = sql_quote($_POST['tactics_run_data']);
	
	$return_page_url = sql_quote($_POST["return_page_url"]);

	// team_instructions
	$team_instructions = array();
	$team_instructions["passing_style"] = $passing_style;
	$team_instructions["mentality"] = $mentality;
	
	
	
	// 得到 $player_id_positions
	$player_id_positions = array();
	// data
	$player_formation = split('&', $data_str);
	$length = count($player_formation);
	for ($r=0; $r<$length; ++$r) {
		$element_array = split('_', $player_formation[$r]);
		$player_id = $element_array[0];
		$position = $element_array[1];
		
		$player_id_positions[$player_id] = $position;
	}
	// subs_data
	$player_formation = split('&', $subs_data_str);
	$length = count($player_formation);
	for ($r=0; $r<$length; ++$r) {
		$element_array = split('_', $player_formation[$r]);
		$player_id = $element_array[0];
		$position = $element_array[1];
		
		$player_id_positions[$player_id] = $position;
	}
	// tactics_run
	$tactics_runs = array();   
	if (strlen($tactics_run_data_str) > 0) {
		$big_arr = split('&', $tactics_run_data_str);  
		$length = count($big_arr);  
		for ($r=0; $r<$length; ++$r) {
			$element_array = split('_', $big_arr[$r]);		
			
			$tactics_runs[count($tactics_runs)] = $element_array;
		}
	}

	// 具体实现 tactics 的保存
	implementSaveTactics($db, $document_root, $team_id, 
			$tactics_symbol, $p_tactics_id, 
			$player_id_positions, $team_instructions, NULL, 
			$tactics_runs);
			
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, $return_page_url);	
}


/**
 * 将p_tactics_id指向的tactics（包括：can_tactics_id_1, can_tactics_id_2, can_tactics_id_3）
 * 复制到cur_tactics（cur_tactics_id）中，
 * 可以参考表 team_tactics 中有几个字段：
 * cur_tactics_id, can_tactics_id_1, can_tactics_id_2, can_tactics_id_3
 *
 * @param [db]				db
 * @param [document_root]	document_root
 *
 * @return return true or false
 */	
function performCopyToCurTactics($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$p_tactics_id = sql_quote($_POST["p_tactics_id"]);
	$return_page_url = sql_quote($_POST["return_page_url"]);
		
	
	// 从 team_tactics 表中得到 $cur_tactics_id
	$cur_tactics_id = "0";
	$returnValue = getCurTacticsId($db, $team_id, $cur_tactics_id);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	

	// 具体实现 tactics 的复制
	implementCopyToCurTactics($db, $document_root, $team_id, 
			$p_tactics_id, $cur_tactics_id);
			
			
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, $return_page_url);	
	
}



//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------
	/**
	 * update the tactics
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [tactics_id]			tactics_id
	 * @param [passing_style]		passing_style
	 * @param [mentality]			mentality
	 * @param [tactics_symbol]		tactics_symbol
	 *
	 * @return return 0, -1, -2
	 */	
function updateTheTactics($db, $team_id, $p_tactics_id, $tactics_symbol, $team_instructions)
{
	$sql_condition = "";
    $j = 0;
    foreach($team_instructions as $key=>$value) {    	
    	if ($j == 0) {
			$sql_condition .= sprintf(" %s='%s' ", $key, $value);
		}
		else {
			$sql_condition .= sprintf(" , %s='%s' ", $key, $value);
		}
		
    	++$j;
    }
    
	$query = sprintf (
				" UPDATE tactics SET " . 
				" tactics_symbol='%s', %s " .
				" WHERE id='%s' " ,
				$tactics_symbol, $sql_condition, $p_tactics_id);

	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * update the primary tactics id 
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [tactics_id]			tactics_id
	 * @param [p_tactics_id]		p_tactics_id
	 *
	 * @return return "0" or error message
	 */	
function getPTacticsId($db, $team_id, $tactics_id, &$p_tactics_id)
{
	$query = sprintf(
				"SELECT id AS p_tactics_id FROM tactics " . 
				" WHERE team_id='%s' AND tactics_id='%s' ",
				$team_id, $tactics_id);

	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0) {
		$p_tactics_id = $rs->fields['p_tactics_id'];
	}
	
	return "0";
}

	/**
	 * delete records from tactics_detail where its tactics_id=$p_tactics_id 
	 *
	 * @param [db]					database
	 * @param [p_tactics_id]		p_tactics_id
	 *
	 * @return return "0" or error message
	 */	
function deleteFromTactics_detail($db, $p_tactics_id)
{
	$query = sprintf(
				" DELETE FROM tactics_detail " .
				" WHERE tactics_id='%s' " ,
				$p_tactics_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * insert one record into tactics_detail
	 *
	 * @param [db]					database
	 * @param [p_tactics_id]		p_tactics_id
	 * @param [position]			position
	 * @param [player_id]			player_id
	 *
	 * @return return "0" or error message
	 */	
function insertIntoTactics_detail($db, $p_tactics_id, $position, $player_id)
{
	$query = sprintf(
				" INSERT INTO tactics_detail " .
				" ( tactics_id, position_place, player_id ) " .
				" VALUES( '%s', '%s', '%s' ) " ,
				$p_tactics_id, $position, $player_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


	/**
	 * delete records from tactics_detail where its tactics_id=$p_tactics_id 
	 *
	 * @param [db]					database
	 * @param [p_tactics_id]		p_tactics_id
	 *
	 * @return return "0" or error message
	 */	
function deleteFromTactics_run($db, $p_tactics_id)
{
	$query = sprintf(
				" DELETE FROM tactics_run " .
				" WHERE tactics_id='%s' " ,
				$p_tactics_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * insert one record into tactics_run
	 *
	 * @param [db]					database
	 * @param [p_tactics_id]		p_tactics_id
	 * @param [player_id]			player_id
	 * @param [from_r]				from_r
	 * @param [from_c]				from_c
	 * @param [to_r]				to_r
	 * @param [to_c]				to_c
	 *
	 * @return return "0" or error message
	 */	
function insertIntoTactics_run($db, $p_tactics_id, $player_id, $pop_id, $from_r, $from_c, $to_r, $to_c)
{
	$query = sprintf(
				" INSERT INTO tactics_run " .
				" ( tactics_id, player_id, pop_id, from_r, from_c, to_r, to_c ) " .
				" VALUES( '%s', '%s', '%s', '%s', '%s', '%s', '%s' ) " ,
				$p_tactics_id, $player_id, $pop_id, $from_r, $from_c, $to_r, $to_c);
				
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


	/**
	 * insert one record into tactics_detail
	 *
	 * @param [db]					database
	 * @param [tactics_symbol]		tactics_symbol
	 * @param [positions]			positions
	 *
	 * @return return "0" or error message
	 */	
function getStdFormation($db, $tactics_symbol, &$positions)
{
	$query = sprintf(
				" SELECT position_place " . 
				" FROM std_formation_detail " . 
				" WHERE tactics_symbol='%s' " . 
				" ORDER BY position_place ASC " ,
				$tactics_symbol);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0) {
		$index = 0;
		for (; !$rs->EOF; $rs->MoveNext(), ++$index) {
			$positions[$index] = $rs->fields['position_place'];
		}
	}
		
	return "0";
}

	/**
	 * insert one record into tactics_detail
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 * @param [p_tactics_id]		p_tactics_id
	 * @param [player_ids]		player_ids
	 *
	 * @return return "0" or error message
	 */	
function getPlayer_idsInTactics_detail($db, $team_id, $p_tactics_id, &$player_ids)
{
	$query = sprintf(
				" SELECT t.position_place, p.player_id " . 
				" FROM player p, tactics_detail t " . 
				" WHERE p.player_id=t.player_id " . 
				" AND t.tactics_id='%s' " . 
				" AND p.team_id='%s' " . 
				" ORDER BY t.position_place ASC" ,
				$p_tactics_id, $team_id);
	
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0) {
		$index = 0;
		for (; !$rs->EOF; $rs->MoveNext(), ++$index) {
			$player_ids[$index] = $rs->fields['player_id'];
		}
	}
		
	return "0";
}

	/**
	 * 具体实现tactics 的实现
	 *
	 * @param [db]						database
	 * @param [document_root]			document_root
	 * @param [team_id]					team_id
	 * @param [p_tactics_id]			p_tactics_id
	 * @param [player_id_positions]		player_id_positions
	 *
	 * @return return "0" or error message
	 */	
function implementSaveTactics($db, $document_root, $team_id, 
			$tactics_symbol, $p_tactics_id, 
			$player_id_positions, $team_instructions, $player_instructions=NULL, 
			$tactics_runs=NULL)
{

	$db->BeginTrans();
	
	$returnValue = updateTheTactics($db, $team_id, $p_tactics_id, $tactics_symbol, $team_instructions);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = deleteFromTactics_detail($db, $p_tactics_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	
    foreach($player_id_positions as $player_id=>$position) {
    	$returnValue = insertIntoTactics_detail($db, $p_tactics_id, $position, $player_id);
		if ($returnValue != "0") {
			$db->RollbackTrans(); 
			$error_message = $returnValue;
			require ("$document_root/page/system/error.php");
			
			goBackInTime(3500, -1); 
		}
    }
    
    // update the player instructions into db
	if ($player_instructions != NULL) {
		for ($pop_id=1; $pop_id<=10; ++$pop_id) {
			$sql_condition = "";
			$j = 0;  
			foreach($player_instructions as $instruction_name=>$value_arr) {
				$instruction_value = intval($value_arr[$pop_id]);
				if ($j == 0) {
					$sql_condition .= sprintf(" %s='%s' ", $instruction_name, $instruction_value);
				}
				else {
					$sql_condition .= sprintf(" , %s='%s' ", $instruction_name, $instruction_value);
				}
				
				++$j;
			}
			
			$returnValue = updateThePlayer_instruction($db, $p_tactics_id, $pop_id, $sql_condition);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}    	
		}
	}
	
	
	
	$returnValue = deleteFromTactics_run($db, $p_tactics_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// update the tactics runs into DB
	if ($tactics_runs != NULL) { 
		$length = count($tactics_runs);     
		for ($i=0; $i<$length; ++$i) {
		
			$element_arr = $tactics_runs[$i]; 
			$player_id = $element_arr[0];
			$pop_id = $element_arr[1];
			$from_r = $element_arr[2];
			$from_c = $element_arr[3];
			$to_r = $element_arr[4];
			$to_c = $element_arr[5];
			$returnValue = insertIntoTactics_run($db, $p_tactics_id, $player_id, $pop_id, $from_r, $from_c, $to_r, $to_c);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValues;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
			
		}
	}
    
    
    
    // commit
	$db->CommitTrans();
	
	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
}

/**
 * update the tactics
 *
 * @param [db]					database
 * @param [p_tactics_id]		p_tactics_id
 * @param [pop_id]				pop_id
 * @param [sql_condition]		sql_condition
 *
 * @return return "0" or other error msg
 */	
function updateThePlayer_instruction($db, $p_tactics_id, $pop_id, $sql_condition)
{
	$query = sprintf(
			" UPDATE player_instruction SET " .
			" %s " .
			" WHERE tactics_id='%s' and pop_id='%s' ",
			$sql_condition, $p_tactics_id, $pop_id);
			
	$rs = &$db->Execute($query); 
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}



/**
 * get cur_tactics_id from team_tactics
 *
 * @param [db]					database
 * @param [team_id]				team_id
 * @param [cur_tactics_id]		cur_tactics_id
 *
 * @return return "0" or error message
 */	
function getCurTacticsId($db, $team_id, &$cur_tactics_id)
{
	$query = sprintf(
				" SELECT cur_tactics_id " . 
				" FROM team_tactics " . 
				" WHERE team_id='%s' " ,
				$team_id);  
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0) {
		
		$cur_tactics_id = $rs->fields['cur_tactics_id'];
		
	}
		
	return "0";
}


/**
 * 具体实现 tactics 的复制
 *
 * @param [db]						database
 * @param [document_root]			document_root
 * @param [team_id]					team_id
 * @param [p_tactics_id]			p_tactics_id
 * @param [cur_tactics_id]			cur_tactics_id
 *
 * @return return "0" or error message
 */	
function implementCopyToCurTactics($db, $document_root, $team_id, 
			$p_tactics_id, $cur_tactics_id)
{

	$db->BeginTrans();
	
	// ------------------------------
	// copy tactics
	// ------------------------------
	$tactics_info = array();
	$returnValue = getTacticsInfo($db, $p_tactics_id, $tactics_info);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$returnValue = copyTheTactics($db, $p_tactics_id, $cur_tactics_id, $tactics_info);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// ------------------------------
	// copy tactics_detail
	// ------------------------------
	$returnValue = deleteFromTactics_detail($db, $cur_tactics_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
		
    $returnValue = copyTactics_detail($db, $p_tactics_id, $cur_tactics_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
    
    // ------------------------------
	// copy player_instruction
	// ------------------------------
    $returnValue = deleteFromPlayer_instruction($db, $cur_tactics_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
    $returnValue = copyPlayer_instruction($db, $p_tactics_id, $cur_tactics_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
    
    // ------------------------------
	// copy tactics_run
	// ------------------------------
    $returnValue = deleteFromTactics_run($db, $cur_tactics_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
    
    $returnValue = copyTactics_run($db, $p_tactics_id, $cur_tactics_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
    
   
    // commit
	$db->CommitTrans();
	
	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
}



/**
 * delete records from player_instruction where its tactics_id=$p_tactics_id 
 *
 * @param [db]					database
 * @param [p_tactics_id]		p_tactics_id
 *
 * @return return "0" or error message
 */	
function deleteFromPlayer_instruction($db, $p_tactics_id)
{
	$query = sprintf(
				" DELETE FROM player_instruction " .
				" WHERE tactics_id='%s' " ,
				$p_tactics_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

/**
 * get tactics info from tactics
 *
 * @param [db]					database
 * @param [p_tactics_id]		p_tactics_id
 * @param [tactics_info]		tactics_info
 *
 * @return return "0" or error message
 */	
function getTacticsInfo($db, $p_tactics_id, &$tactics_info)
{
	$query = sprintf(
				" SELECT passing_style, mentality, tactics_symbol, off_focus, " .
				"   offside_trip, dline_push_up, counter_attack, pressing, tackling, " .
				"   tempo, amount_of_DC_striction, amount_of_SF_striction, use_key_man, " .
				"   use_target_man " . 
				" FROM tactics " . 
				" WHERE id='%s' " ,
				$p_tactics_id);
				
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0) {
		
		$tactics_info["passing_style"] 	= $rs->fields['passing_style'];
		$tactics_info["mentality"] 		= $rs->fields['mentality'];
		$tactics_info["tactics_symbol"] = $rs->fields['tactics_symbol'];
		$tactics_info["off_focus"] 		= $rs->fields['off_focus'];
		$tactics_info["offside_trip"] 	= $rs->fields['offside_trip'];
		$tactics_info["dline_push_up"] 	= $rs->fields['dline_push_up'];
		$tactics_info["counter_attack"] = $rs->fields['counter_attack'];
		$tactics_info["pressing"] 		= $rs->fields['pressing'];
		$tactics_info["tackling"] 		= $rs->fields['tackling'];
		$tactics_info["tempo"] 			= $rs->fields['tempo'];
		
		$tactics_info["amount_of_DC_striction"] 	= $rs->fields['amount_of_DC_striction'];
		$tactics_info["amount_of_SF_striction"] 	= $rs->fields['amount_of_SF_striction'];
		$tactics_info["use_key_man"] 		= $rs->fields['use_key_man'];
		$tactics_info["use_target_man"] 	= $rs->fields['use_target_man'];
		
		
	}
		
	return "0";
}


/**
 * copy the tactics
 *
 * @param [db]					database
 * @param [p_tactics_id]		p_tactics_id
 * @param [cur_tactics_id]		cur_tactics_id
 * @param [tactics_info]		tactics_info
 *
 * @return return "0" or other error msg
 */	
function copyTheTactics($db, $p_tactics_id, $cur_tactics_id, $tactics_info)
{
	$query = sprintf(
			" UPDATE tactics SET " .
			"   passing_style='%s', mentality='%s', tactics_symbol='%s', off_focus='%s', " .
			"   offside_trip='%s', dline_push_up='%s', counter_attack='%s', pressing='%s', tackling='%s', " .
			"   tempo='%s', amount_of_DC_striction='%s', amount_of_SF_striction='%s', use_key_man='%s', " .
			"   use_target_man='%s' " .
			" WHERE id='%s' ",
			$tactics_info["passing_style"], $tactics_info["mentality"], $tactics_info["tactics_symbol"],  $tactics_info["off_focus"], 
			$tactics_info["offside_trip"], $tactics_info["dline_push_up"], $tactics_info["counter_attack"], $tactics_info["pressing"], $tactics_info["tackling"], 
			$tactics_info["tempo"], $tactics_info["amount_of_DC_striction"], $tactics_info["amount_of_SF_striction"], $tactics_info["use_key_man"], 
			$tactics_info["use_target_man"], 
			$cur_tactics_id);
		
	$rs = &$db->Execute($query); 
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


/**
 * copy the tactics_detail
 * from p_tactics_id to cur_tactics_id
 *
 * @param [db]					database
 * @param [p_tactics_id]		p_tactics_id
 * @param [cur_tactics_id]		cur_tactics_id
 *
 * @return return "0" or other error msg
 */	
function copyTactics_detail($db, $p_tactics_id, $cur_tactics_id)
{
	$query = sprintf(
			" INSERT INTO tactics_detail " .
			" (tactics_id, position_place, player_id) " .
			"   SELECT '%s' AS tactics_id, position_place, player_id " .
			"   FROM tactics_detail " .
			"   WHERE tactics_id='%s' ",
			$cur_tactics_id, $p_tactics_id);
		
	$rs = &$db->Execute($query); 
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


/**
 * copy the player_instruction
 * from p_tactics_id to cur_tactics_id
 *
 * @param [db]					database
 * @param [p_tactics_id]		p_tactics_id
 * @param [cur_tactics_id]		cur_tactics_id
 *
 * @return return "0" or other error msg
 */	
function copyPlayer_instruction($db, $p_tactics_id, $cur_tactics_id)
{
	$query = sprintf(
			" INSERT INTO player_instruction " .
			" (tactics_id, pop_id, forward_run, run_with_ball, long_shot, " .
			"  hold_the_ball, through_pass, crossing) " .
			"   SELECT '%s' AS tactics_id, pop_id, forward_run, run_with_ball, long_shot, " .
			"          hold_the_ball, through_pass, crossing " .
			"   FROM player_instruction " .
			"   WHERE tactics_id='%s' ",
			$cur_tactics_id, $p_tactics_id);
			
	$rs = &$db->Execute($query); 
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


/**
 * copy the tactics_run
 * from p_tactics_id to cur_tactics_id
 *
 * @param [db]					database
 * @param [p_tactics_id]		p_tactics_id
 * @param [cur_tactics_id]		cur_tactics_id
 *
 * @return return "0" or other error msg
 */	
function copyTactics_run($db, $p_tactics_id, $cur_tactics_id)
{
	$query = sprintf(
			" INSERT INTO tactics_run " .
			" (tactics_id, player_id, from_r, from_c, to_r, " .
			"  to_c) " .
			"   SELECT '%s' AS tactics_id, player_id, from_r, from_c, to_r, " .
			"          to_c " .
			"   FROM tactics_run " .
			"   WHERE tactics_id='%s' ",
			$cur_tactics_id, $p_tactics_id);
			
	$rs = &$db->Execute($query); 
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


