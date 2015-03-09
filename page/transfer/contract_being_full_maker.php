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
$tpl->loadTemplatefile("contract_being_full.tpl.php", true, true); 
// pager
$tpl_pager = new HTML_Template_ITX($TPL_PATCH); 
$tpl_pager->loadTemplatefile('pager.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = $_SESSION['s_primary_team_id'];

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
			" SELECT team.team_id, team.name AS team_name, " .
			" player.player_id, player.custom_given_name AS given_name, " . 
			" player.custom_family_name AS family_name, player.highest_tsp, player.age, " . 
			" player.position, cbf.bids, cbf.start_time " . 
			" FROM contract_being_full cbf, player " . 
			" LEFT JOIN team ON cbf.team_id=team.team_id " . 
			" WHERE cbf.player_id=player.player_id AND cbf.team_id<>'%s' " . 
			" %s " . 
			" ORDER BY team.name, cbf.start_time DESC " ,
			$s_primary_team_id, $sql_filter);  

$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page); 
if (!$rs) {
	$error_message = "Database error.";
	$error_message = $db->ErrorMsg();
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit(0);
}
else {
	$index = 1;
    for (; !$rs->EOF; $rs->MoveNext(), $index++) {
		$tpl->setCurrentBlock('contract_being_full') ;
		
		$tpl->setVariable("TEAM_ID", $rs->fields['team_id']) ;
		$tpl->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
		$tpl->setVariable("PLAYER_ID", $rs->fields['player_id']) ;
		$tpl->setVariable("AGE", $rs->fields['age']) ;
		
		$full_name = "";
		if ($rs->fields['given_name']  == "") {
			$full_name = $rs->fields['family_name'];
		}
		else {
			$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
		}
		$tpl->setVariable("PLAYER_NAME", $full_name) ;
		
		$h_tsp = doubleval($rs->fields['highest_tsp']);
		$worth = $h_tsp * 1000000;
		$tpl->setVariable("WORTH", number_format($worth, null, null, ",")) ;
		
		$position = $position_arr[$rs->fields['position']];
		$tpl->setVariable("POS", $position) ;
		$tpl->setVariable("BIDS", $rs->fields['bids']) ;
		
		if ($index == 1)
			$tpl->setVariable("LIST_SEPARATOR", 'none') ;
		$tpl->setVariable("TRAN_TR_CLASS", 'gSGRowOdd');
	
	
		
		$tpl->parseCurrentBlock('contract_being_full') ;
		 
	}
}		


/**
 * pager
 */
$tpl_pager->setVariable("FORM_ACTION", "/fmol/page/transfer/free_market.php") ;
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


