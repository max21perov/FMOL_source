<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);


//----------------------------------------------------------------------------	
// handle the mail
//----------------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] != "POST")    //check the page is request by method "POST"
{
	// go back to the page "mail.php"
	goToPageInTime(0, "/fmol/page/transfer/transfer_hotlist.php");
}

$myaction = sql_quote($_GET["myaction"]); 


if ("deleteSelectedHotlists" == $myaction) {
	performDeleteSelectedHotlists($db, DOCUMENT_ROOT);
	
}
else {
	// default: go back to the page "mail.php"
	goToPageInTime(0, "/fmol/page/transfer/transfer_hotlist.php");
}



//----------------------------------------------------------------------------	
// 使用到的(perform)函数
//----------------------------------------------------------------------------


/**
 * delete selected hotlists
 */
function performDeleteSelectedHotlists($db, $document_root)
{
	$selected_hotlist_ids = $_POST["hotlist_checkbox"]; 
		
	deleteSelectedHotlists($db, DOCUMENT_ROOT, $selected_hotlist_ids);
	
}




//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------


/**
  * 删除已经选择的hotlist
 **/
function deleteSelectedHotlists($db, $document_root, $selected_hotlist_ids)
{
	$db->BeginTrans();
	foreach ($selected_hotlist_ids as $hotlist_id) {
		$query = sprintf(
					" DELETE FROM hot_list " .
					" WHERE id='%s' " ,
					$hotlist_id);
		$rs = &$db->Execute($query);
	
		if (!$rs) {
			$db->RollbackTrans();
			$error_message = "Database error.";
			require ("$document_root/page/system/error.php");
		
			goBackInTime(3500, -1); 
		}
		else if ($rs->RecordCount() < 0 ){
			$db->RollbackTrans();
			$error_message = "There is not this right record in the database.";
			require ("$document_root/page/system/error.php");
			
			goBackInTime(3500, -1); 
		}
		
	}
	
	

	$db->CommitTrans();
	$error_message = "delete hotlist success.";
	require_once ("$document_root/page/system/error.php");

	// go back to the page "transfer_hotlist.php"
	goToPageInTime(2, "/fmol/page/transfer/transfer_hotlist.php");
	
}







?>