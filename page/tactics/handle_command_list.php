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
	// go back to the page "command_list.php"
	goToPageInTime(0, "/fmol/page/tactics/command_list.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("saveCommandList" == $myaction) {
	performSaveCommandList($db, DOCUMENT_ROOT);
}
else {
	goToPageInTime(0, "/fmol/page/tactics/command_list.php");
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
function performSaveCommandList($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']);


	// 得到 $player_id_positions
	
	
	$pre_command_value_name = "full_command_value";
	$all_commands = array(); 
    $all_commands = $_POST["full_command_value"];
  
  
    
	// 具体实现 tactics 的保存
	implementSaveCommandList($db, $document_root, $team_id, $all_commands);
			
	// 最后，返回tactics_easy.php 页面
	goToPageInTime(2, "/fmol/page/tactics/command_list.php");	
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
 * @param [p_tactics_id]			p_tactics_id
 * @param [player_id_positions]		player_id_positions
 *
 * @return return "0" or error message
 */	
function implementSaveCommandList($db, $document_root, $team_id, $all_commands)
{
    
	$db->BeginTrans();
	
		
	$returnValue = deleteFromComamndList($db, $team_id);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$count = count($all_commands);  
    for ($i=0; $i<$count; ++$i) {  
    	$command_entity = $all_commands[$i];
    	$command_entity = explode("|", $command_entity);
    	$field_num = count($command_entity);  
    	
    	switch($field_num) {
    	case (3+1):
    		$entity_index = 0;
    		$time = $command_entity[$entity_index++];
    		$cond = $command_entity[$entity_index++];
    		$type = $command_entity[$entity_index++];
    		$par1 = $command_entity[$entity_index++];
    		$returnValue = insertIntoCommandList($db, $team_id, $time, $cond, $type, 
    											$par1);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
    		break;
    	case (3+2):
    		$entity_index = 0;
    		$time = $command_entity[$entity_index++];
    		$cond = $command_entity[$entity_index++];
    		$type = $command_entity[$entity_index++];
    		$par1 = $command_entity[$entity_index++];
    		$par2 = $command_entity[$entity_index++];
    		$returnValue = insertIntoCommandList($db, $team_id, $time, $cond, $type, 
    											$par1, $par2);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
    		break;
    	case (3+3):
    		$entity_index = 0;
    		$time = $command_entity[$entity_index++];
    		$cond = $command_entity[$entity_index++];
    		$type = $command_entity[$entity_index++];
    		$par1 = $command_entity[$entity_index++];
    		$par2 = $command_entity[$entity_index++];
    		$par3 = $command_entity[$entity_index++];
    		$returnValue = insertIntoCommandList($db, $team_id, $time, $cond, $type, 
    											$par1, $par2, $par3);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
    		break;
    	case (3+4):
    		$entity_index = 0;
    		$time = $command_entity[$entity_index++];
    		$cond = $command_entity[$entity_index++];
    		$type = $command_entity[$entity_index++];
    		$par1 = $command_entity[$entity_index++];
    		$par2 = $command_entity[$entity_index++];
    		$par3 = $command_entity[$entity_index++];
    		$par4 = $command_entity[$entity_index++];
    		$returnValue = insertIntoCommandList($db, $team_id, $time, $cond, $type, 
    											$par1, $par2, $par3,
    											$par4);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
    		break;
    	case (3+5):
    		$entity_index = 0;
    		$time = $command_entity[$entity_index++];
    		$cond = $command_entity[$entity_index++];
    		$type = $command_entity[$entity_index++];
    		$par1 = $command_entity[$entity_index++];
    		$par2 = $command_entity[$entity_index++];
    		$par3 = $command_entity[$entity_index++];
    		$par4 = $command_entity[$entity_index++];
    		$par5 = $command_entity[$entity_index++];
    		$returnValue = insertIntoCommandList($db, $team_id, $time, $cond, $type, 
    											$par1, $par2, $par3,
    											$par4, $par5);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
    		break;
    	case (3+6):
    		$entity_index = 0;
    		$time = $command_entity[$entity_index++];
    		$cond = $command_entity[$entity_index++];
    		$type = $command_entity[$entity_index++];
    		$par1 = $command_entity[$entity_index++];
    		$par2 = $command_entity[$entity_index++];
    		$par3 = $command_entity[$entity_index++];
    		$par4 = $command_entity[$entity_index++];
    		$par5 = $command_entity[$entity_index++];
    		$par6 = $command_entity[$entity_index++];
    		$returnValue = insertIntoCommandList($db, $team_id, $time, $cond, $type, 
    											$par1, $par2, $par3,
    											$par4, $par5, $par6);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
    		break;
    	case (3+7):
    		$entity_index = 0;
    		$time = $command_entity[$entity_index++];
    		$cond = $command_entity[$entity_index++];
    		$type = $command_entity[$entity_index++];
    		$par1 = $command_entity[$entity_index++];
    		$par2 = $command_entity[$entity_index++];
    		$par3 = $command_entity[$entity_index++];
    		$par4 = $command_entity[$entity_index++];
    		$par5 = $command_entity[$entity_index++];
    		$par6 = $command_entity[$entity_index++];
    		$par7 = $command_entity[$entity_index++];
    		$returnValue = insertIntoCommandList($db, $team_id, $time, $cond, $type, 
    											$par1, $par2, $par3,
    											$par4, $par5, $par6,
    											$par7);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
    		break;
    	case (3+8):
    		$entity_index = 0;
    		$time = $command_entity[$entity_index++];
    		$cond = $command_entity[$entity_index++];
    		$type = $command_entity[$entity_index++];
    		$par1 = $command_entity[$entity_index++];
    		$par2 = $command_entity[$entity_index++];
    		$par3 = $command_entity[$entity_index++];
    		$par4 = $command_entity[$entity_index++];
    		$par5 = $command_entity[$entity_index++];
    		$par6 = $command_entity[$entity_index++];
    		$par7 = $command_entity[$entity_index++];
    		$par8 = $command_entity[$entity_index++];
    		$returnValue = insertIntoCommandList($db, $team_id, $time, $cond, $type, 
    											$par1, $par2, $par3,
    											$par4, $par5, $par6,
    											$par7, $par8);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
    		break;
    	case (3+9):
    		$entity_index = 0;
    		$time = $command_entity[$entity_index++];
    		$cond = $command_entity[$entity_index++];
    		$type = $command_entity[$entity_index++];
    		$par1 = $command_entity[$entity_index++];
    		$par2 = $command_entity[$entity_index++];
    		$par3 = $command_entity[$entity_index++];
    		$par4 = $command_entity[$entity_index++];
    		$par5 = $command_entity[$entity_index++];
    		$par6 = $command_entity[$entity_index++];
    		$par7 = $command_entity[$entity_index++];
    		$par8 = $command_entity[$entity_index++];
    		$par9 = $command_entity[$entity_index++];
    		$returnValue = insertIntoCommandList($db, $team_id, $time, $cond, $type, 
    											$par1, $par2, $par3,
    											$par4, $par5, $par6,
    											$par7, $par8, $par9);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$error_message = $returnValue;
				require ("$document_root/page/system/error.php");
				
				goBackInTime(3500, -1); 
			}
    		break;
    	}
    }
    
    
    // commit
	$db->CommitTrans();
	
	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
	
}


/**
 * delete records from command where its team_id=$team_id 
 *
 * @param [db]			database
 * @param [team_id]		team_id
 *
 * @return return "0" or error message
 */	
function deleteFromComamndList($db, $team_id)
{
	$query = sprintf(
				" DELETE FROM command " .
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
 * @param [p_tactics_id]		p_tactics_id
 * @param [position]			position
 * @param [player_id]			player_id
 *
 * @return return "0" or error message
 */	
function insertIntoCommandList($db, $team_id, $time, $cond, $type,
								$par1=0, $par2=0, $par3=0, 
								$par4=0, $par5=0, $par6=0, 
								$par7=0, $par8=0, $par9=0)
{  
	$query = sprintf(
				" INSERT INTO command " .
				" ( " .
				" team_id, time, cond, type, " .
				" par1, par2, par3, " .
				" par4, par5, par6, " .
				" par7, par8, par9 " .
				" ) " .
				" VALUES( " .
				" '%s', '%s', '%s', '%s', " . 
				" '%s', '%s', '%s', " .
				" '%s', '%s', '%s', " .
				" '%s', '%s', '%s' " .
				" ) " ,
				$team_id, $time, $cond, $type, 
				$par1, $par2, $par3, 
				$par4, $par5, $par6, 
				$par7, $par8, $par9 );
				
	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}



