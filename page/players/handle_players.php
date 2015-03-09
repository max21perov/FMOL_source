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
	// go back to the page "club_info.php"
	goToPageInTime(0, "/fmol/page/info/club_info.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("saveExtendContract" == $myaction) {
	performSaveExtendContract($db, DOCUMENT_ROOT);
}
else {
	$return_page_url = sql_quote($_POST["return_page_url"]);

	if ($return_page_url == "")
		goToPageInTime(0, "/fmol/page/info/club_info.php");
	else 
		goToPageInTime(0, $return_page_url);
}



//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------
/**
	 * save the extend contract
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performSaveExtendContract($db, $document_root)
{
	$player_id = sql_quote($_POST["player_id"]);
	$extend_seasons = sql_quote($_POST["extend_seasons"]);
	$return_page_url = sql_quote($_POST["return_page_url"]);
	
	// update player externd contract
	$returnValue = saveExterndContractIntoDB($db, $player_id, $extend_seasons);
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
	 * save externd contract into DB
	 *
	 * @param [db]					database
	 * @param [player_id]			player_id
	 * @param [extend_seasons]		extend_seasons
	 *
	 * @return return 0, -1, -2
	 */	
function saveExterndContractIntoDB($db, $player_id, $extend_seasons)
{    
	$query = sprintf (
				" UPDATE player p, team t, club c SET " . 
				" p.contract_negotiating='1', p.extend_seasons='%s', " .
				" c.activity_point_num=c.activity_point_num-5 " .
				" WHERE p.player_id='%s' AND p.team_id=t.team_id AND t.club_id=c.club_id " ,
				$extend_seasons, $player_id);

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
