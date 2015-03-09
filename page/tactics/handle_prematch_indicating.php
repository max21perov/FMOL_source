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
	goToPageInTime(0, "/fmol/page/tactics/prematch_indicating.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("savePreMatchIndicating" == $myaction) {
	performSavePreMatchIndicating($db, DOCUMENT_ROOT);
}
else {
	$return_page_url = sql_quote($_POST["return_page_url"]);

	if ($return_page_url == "")
		goToPageInTime(0, "/fmol/page/tactics/prematch_indicating.php");
	else 
		goToPageInTime(0, $return_page_url);
}


//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
/**
	 * 保存赛前部署
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performSavePreMatchIndicating($db, $document_root)
{
	$team_id = sql_quote($_SESSION['s_primary_team_id']);
	$is_indicating_in_use = sql_quote($_POST["is_indicating_in_use"]);
	$is_spec_opp_in_use = sql_quote($_POST["is_spec_opp_in_use"]);
	
	if ($is_indicating_in_use == "on") $is_indicating_in_use = 0;
	else $is_indicating_in_use = 1;
	
	if ($is_spec_opp_in_use == "on") $is_spec_opp_in_use = 0;
	else $is_spec_opp_in_use = 1;
	
	
	$opp_F_num 			= sql_quote($_POST["opp_F_num"]);
	$opp_D_num 			= sql_quote($_POST["opp_D_num"]);
	$is_opp_AMC 		= sql_quote($_POST["is_opp_AMC"]);
	$is_opp_DMC 		= sql_quote($_POST["is_opp_DMC"]);
	$opp_AD_mentality 	= sql_quote($_POST["opp_AD_mentality"]);
	$opp_tempo 			= sql_quote($_POST["opp_tempo"]);
	$is_opp_OST 		= sql_quote($_POST["is_opp_OST"]);
	$is_opp_CA 			= sql_quote($_POST["is_opp_CA"]);
	$spec_opp_player_id = sql_quote($_POST["spec_opp_player_id"]);
	$is_heavy_tackling 	= sql_quote($_POST["is_heavy_tackling"]);
	$is_heavy_pressing 	= sql_quote($_POST["is_heavy_pressing"]);
	
	// update the PreMatch Indication
	$returnValue = updatePreMatchIndication($db, $team_id, $is_indicating_in_use, $opp_F_num, $opp_D_num,
												 $is_opp_AMC, $is_opp_DMC, $opp_AD_mentality, 
												 $opp_tempo, $is_opp_OST, $is_opp_CA,
												 $is_spec_opp_in_use, $spec_opp_player_id, $is_heavy_tackling,
												 $is_heavy_pressing);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
	
	
	// 最后，返回 $return_page_url 页面
	goToPageInTime(2, $return_page_url);	
	
}


//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------
	/**
	 * update the PreMatch Indication
	 *
	 * @param [db]					database
	 * @param [team_id]				team_id
	 *
	 * @return return 0, -1, -2
	 */	
function updatePreMatchIndication($db, $team_id, $is_indicating_in_use, $opp_F_num, $opp_D_num,
										$is_opp_AMC, $is_opp_DMC, $opp_AD_mentality, 
										$opp_tempo, $is_opp_OST, $is_opp_CA,
										$is_spec_opp_in_use, $spec_opp_player_id, $is_heavy_tackling,
										$is_heavy_pressing)
{
	
    
	$query = sprintf (
				" UPDATE team_tactics SET " . 
				" is_indicating_in_use='%s', opp_F_num='%s', opp_D_num='%s', " .
				" is_opp_AMC='%s', is_opp_DMC='%s', opp_AD_mentality='%s', " .
				" opp_tempo='%s', is_opp_OST='%s', is_opp_CA='%s', " .
				" is_spec_opp_in_use='%s', spec_opp_player_id='%s', is_heavy_tackling='%s', " .
				" is_heavy_pressing='%s' " .
				" WHERE team_id='%s' " ,
				$is_indicating_in_use, $opp_F_num, $opp_D_num,
				$is_opp_AMC, $is_opp_DMC, $opp_AD_mentality, 
				$opp_tempo, $is_opp_OST, $is_opp_CA,
				$is_spec_opp_in_use, $spec_opp_player_id, $is_heavy_tackling,
				$is_heavy_pressing, $team_id);

	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


?>