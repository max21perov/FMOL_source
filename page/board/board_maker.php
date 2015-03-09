<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");


// update the div_id 
// when a season finishes and the team's div_id may be changed
require_once(DOCUMENT_ROOT . "/page/system/update_div_id.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile('board.tpl.php', true, true); 
		
//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);
$s_primary_club_id = sql_quote($_SESSION['s_primary_club_id']);


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * get the board basic information
 */
$board_info_arr = getBoardBasicInfo($db, DOCUMENT_ROOT, $s_primary_club_id);
$tpl->setVariable("SEASON_EXPECTION", $board_info_arr["season_expectation"]);
$tpl->setVariable("BOARD_SATISFACTION", floatval($board_info_arr["satisfaction"])*100 . "%");
$tpl->setVariable("LATEST_OPTION", $board_info_arr["latest_option"]);
$tpl->setVariable("SEASON_OPTION", $board_info_arr["season_option"]);


// 给action_str赋值
$action_str = "action1:action 1|action2:action 2|action3:action 3|action4:action 4|action5:action 5";
$tpl->setVariable("ACTION_STR", $action_str);
$tpl->setVariable("ACTION_EXPLAIN", "action 1");


//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// 为了防止页面不显示，所以加了一个空格
$tpl->setVariable("SPACE", " ");
$tpl->show();



//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------	
/**
  * get the board basic information
  *
  * @param [db]				database
  * @param [document_root]	document_root
  * @param [club_id]		club 的 id
  *
  * @return fans basic info
  */
function getBoardBasicInfo($db, $document_root, $club_id)
{
	$board_info_arr = array();
	
	$query = sprintf(
				" SELECT season_expectation, satisfaction, " .
				" latest_option, season_option " .
				" FROM board " .
				" WHERE club_id='%s' ",
				$club_id);
	$rs = &$db->Execute($query);
	if (!$rs) {		
		$error_message = "Database error.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() < 0){
		$error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() > 0) {
		$board_info_arr["season_expectation"] = $rs->fields["season_expectation"];
		$board_info_arr["satisfaction"] = $rs->fields["satisfaction"];
		$board_info_arr["latest_option"] = $rs->fields["latest_option"];
		$board_info_arr["season_option"] = $rs->fields["season_option"];
	}
	
	return $board_info_arr;
}





?>

