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
	// go back to the page "role_list.php"
	goToPageInTime(0, "/fmol/page/tactics/role_list.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("saveRoleList" == $myaction) {
	performSaveRoleList($db, DOCUMENT_ROOT);
}
else {
	goToPageInTime(0, "/fmol/page/tactics/role_list.php");
}

//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------

	/**
	 * 保存阵型
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performSaveRoleList($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$all_role_types = sql_quote($_POST['all_role_types']);  
	
	$all_role_types_arr = explode("|", $all_role_types);  
	$all_role_types_arr_len = count($all_role_types_arr);  
	
	
	// 得到 $all_role_priority 和 $all_player_id  	
	$pre_index_select_name = "index_select_";
	$pre_player_ids_name = "player_ids_";
	$all_role_priority = array(); 
	$all_player_id = array(); 
	for ($i=0; $i<$all_role_types_arr_len; ++$i) {
		$role_type = $all_role_types_arr[$i];
		
		$priority_arr = array(); 
		$priority_arr = $_POST[$pre_index_select_name . $role_type];   
		
		$player_id_arr = array(); 
		$player_id_arr = $_POST[$pre_player_ids_name . $role_type];
		
		$all_role_priority[$role_type] = $priority_arr;
		$all_player_id[$role_type] = $player_id_arr;
	}
	
  
  
    
	// 具体实现 tactics 的保存
	implementSaveRoleList($db, $document_root, $team_id, $all_role_types_arr, $all_player_id, $all_role_priority);
			
	// 最后，返回tactics_easy.php 页面
	goToPageInTime(2, "/fmol/page/tactics/role_list.php");	
}

//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------


/**
 * 具体实现tactics 的实现
 *
 * @param [db]						database
 * @param [document_root]			document_root
 * @param [team_id]					team_id
 * @param [all_role_types_arr]		该数组保存了所有的role_type
 * @param [all_player_id]			该数组保存了不同role_type下的player_id序列
 * @param [all_role_priority]		该数组保存了不同role_type下的role_priority序列，该序列要跟player_id序列顺序一致
 *
 * @return return "0" or error message
 */	
function implementSaveRoleList($db, $document_root, $team_id, $all_role_types_arr, $all_player_id, $all_role_priority)
{
    
	$db->BeginTrans();
	
		
	$returnValue = deleteFromRoleList($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$count = count($all_role_types_arr);  
    for ($i=0; $i<$count; ++$i) {  
    	$role_type = $all_role_types_arr[$i];
    	
    	$player_id_arr = $all_player_id[$role_type];
    	$role_priority_arr = $all_role_priority[$role_type];
    	
    	$player_id_arr_len = count($player_id_arr);
    	for ($j=0; $j<$player_id_arr_len; ++$j) {
    		$player_id = $player_id_arr[$j];
    		$role_priority = $role_priority_arr[$j];
    		
    		$returnValue = insertIntoCommandList($db, $team_id, $player_id, $role_type, $role_priority);
    		if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
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
 * delete records from tactics_detail where its tactics_id=$p_tactics_id 
 *
 * @param [db]					database
 * @param [p_tactics_id]		p_tactics_id
 *
 * @return return "0" or error message
 */	
function deleteFromRoleList($db, $team_id)
{
	$query = sprintf(
				" DELETE FROM role " .
				" WHERE team_id='%s' " ,
				$team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error1";
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
 * @param [team_id]				team_id
 * @param [player_id]			player_id
 * @param [role_id]				role_id
 * @param [role_priority]		单个role在同一个role_id下的顺序
 *
 * @return return "0" or error message
 */	
function insertIntoCommandList($db, $team_id, $player_id, $role_type, $role_priority)
{  
	$query = sprintf(
				" INSERT INTO role " .
				" ( " .
				" team_id, player_id, role_id, role_priority " .
				" ) " .
				" VALUES( " .
				" '%s', '%s', '%s', '%s' " .
				" ) " ,
				$team_id, $player_id, $role_type, $role_priority );
				
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}



