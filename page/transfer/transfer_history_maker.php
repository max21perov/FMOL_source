<?php
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
$tpl->loadTemplatefile("transfer_history.tpl.php", true, true); 
// pager
$tpl_pager = new HTML_Template_ITX($TPL_PATCH); 
$tpl_pager->loadTemplatefile('pager.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']); 


//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$position_filter = sql_quote($_GET["position_filter"]); 
/**
  * handle the filter of mail type
  */
$sql_filter = "";
if ($position_filter == "") {
	$tpl->setVariable("ALL_SELECTED", "selected") ;
}
else { 
	switch (intval($position_filter)) {
	case 0:
		// GK
		$sql_filter .= " AND player.position = 0 ";
		$tpl->setVariable("GOAL_KEEPERS_SELECTED", "selected") ;
		break;
	case 1:
		$sql_filter .= " AND player.position between 1 and 3 ";
		$tpl->setVariable("DEFENDERS_SELECTED", "selected") ;
		break;
	case 2:
		$sql_filter .= " AND player.position between 4 and 12 ";
		$tpl->setVariable("MIDFIELDERS_SELECTED", "selected") ;
		break;
	case 3:
		$sql_filter .= " AND player.position = 13 ";
		$tpl->setVariable("STRIKERS_SELECTED", "selected") ;
		break;
	default:
		$tpl->setVariable("ALL_SELECTED", "selected") ;
		break;
	}
}

/**
 * pager
 */
$num_of_rows_per_page = 10;  // 每页显示的记录数
$curr_page = "";
if (isset($_GET['next_page']))
        $curr_page = sql_quote($_GET['next_page']);
if ($curr_page == "") $curr_page = 1; // at first page


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * get the player id who are in the transfer market
 */
$position_arr = array(
	"0"=>"GK", "1"=>"DC", "2"=>"DL", "3"=>"DR", "4"=>"DMC", 
	"5"=>"DML", "6"=>"DMR", "7"=>"MC", "8"=>"ML", "9"=>"MR",
	"10"=>"AMC", "11"=>"AML", "12"=>"AMR", "13"=>"F");
	
$pre_play_id = "";

$query = sprintf(
			" ( " .
			" SELECT t1.team_id AS s_team_id, t1.name AS s_team_name, " . 
			" t2.team_id AS t_team_id, t2.name AS t_team_name, " . 
			" player.player_id AS player_id, player.custom_given_name AS given_name, " . 
			" player.custom_family_name AS family_name, player.age, " . 
			" player.position, th.price, th.time, 'sell out' as tran_type " . 
			" FROM transfer_history th, team t1, team t2, player " . 
			" WHERE th.player_id=player.player_id " . 
			" AND th.s_team_id=t1.team_id " . 
			" AND th.t_team_id=t2.team_id " . 
			" AND th.s_team_id='%s' " . 
			" %s " . 
			" ) " . 
			" union " . 
			" ( " . 
			" SELECT t1.team_id AS s_team_id, t1.name AS s_team_name, " . 
			" t2.team_id AS t_team_id, t2.name AS t_team_name, " . 
			" player.player_id AS player_id, player.custom_given_name AS given_name, " . 
			" player.custom_family_name AS family_name, player.age, " . 
			" player.position, th.price, th.time, 'buy in' as tran_type " . 
			" FROM transfer_history th, team t1, team t2, player " . 
			" WHERE th.player_id=player.player_id " . 
			" AND th.s_team_id=t1.team_id " . 
			" AND th.t_team_id=t2.team_id " . 
			" AND th.t_team_id='%s' " . 
			" %s " . 
			" ) " . 
			" ORDER BY time DESC " ,
			$s_primary_team_id, $sql_filter, $s_primary_team_id, $sql_filter);  

$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page);   
if (!$rs) {
	$error_message = "Database error.";
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit(0);
}
else {  
	$index = 1;
    for (; !$rs->EOF; $rs->MoveNext(), $index++) {
		$tpl->setCurrentBlock('transfer_history') ;
		
		$tpl->setVariable("S_TEAM_ID", $rs->fields['s_team_id']) ;
		$tpl->setVariable("S_TEAM_NAME", $rs->fields['s_team_name']) ;
		$tpl->setVariable("T_TEAM_ID", $rs->fields['t_team_id']) ;
		$tpl->setVariable("T_TEAM_NAME", $rs->fields['t_team_name']) ;
		$tpl->setVariable("PLAYER_ID", $rs->fields['player_id']) ;
		$tpl->setVariable("AGE", $rs->fields['age']) ;
		$tpl->setVariable("PRICE", number_format($rs->fields['price'], null, null, ",")) ;
		$tpl->setVariable("TIME", substr($rs->fields['time'], 0, 10)) ;
		$tpl->setVariable("TYPE", $rs->fields['tran_type']) ;
		
		$full_name = "";
		if ($rs->fields['given_name']  == "") {
			$full_name = $rs->fields['family_name'];
		}
		else {
			$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
		}
		$tpl->setVariable("PLAYER_NAME", $full_name) ;
		
		
		$position = $position_arr[$rs->fields['position']];
		$tpl->setVariable("POS", $position) ;
		
		if ($index == 1)
			$tpl->setVariable("LIST_SEPARATOR", 'none') ;
		$tpl->setVariable("TRAN_TR_CLASS", 'gSGRowOdd');

		
		$tpl->parseCurrentBlock('transfer_history') ;
		
	}
}		



/**
 * pager
 */
$tpl_pager->setVariable("FORM_ACTION", "/fmol/page/transfer/transfer_history.php") ;
$tpl_pager->setVariable("FILTER_NAME", "position_filter"); 
$tpl_pager->setVariable("FILTER_VALUE", $position_filter);
$tpl_pager->setVariable("CURRENT_PAGE_NUM", $rs->AbsolutePage()) ;
$tpl_pager->setVariable("TOTAL_PAGE_NUM", ($rs->LastPageNo(false)=="-1") ? "1" : $rs->LastPageNo(false)) ;
$tpl_pager->setVariable("TOTAL_RECORD_NUM", $rs->MaxRecordCount()) ;
// pre page
if (!$rs->AtFirstPage()) {
	$tpl_pager->setVariable("PRE_PAGE_URL", "<a href=$PHPSELF?next_page=". ($rs->AbsolutePage() - 1). "&position_filter=$position_filter>pre</a>") ;
}
else if ($rs->AtFirstPage()) {
	$tpl_pager->setVariable("PRE_PAGE_URL", "") ;
}
// next page
if (!$rs->AtLastPage()) {
	$tpl_pager->setVariable("NEXT_PAGE_URL", "<a href=$PHPSELF?next_page=". ($rs->AbsolutePage() + 1). "&position_filter=$position_filter>next</a>") ;
}
else if ($rs->AtLastPage()) {
	$tpl_pager->setVariable("NEXT_PAGE_URL", "") ;
}

$tpl->setVariable("PAGER_TOOLBAR", $tpl_pager->get());



//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();



?>


