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
$tpl->loadTemplatefile("online_list.tpl.php", true, true); 
// pager
$tpl_pager = new HTML_Template_ITX($TPL_PATCH); 
$tpl_pager->loadTemplatefile('pager.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	


/**
 * pager
 */
$num_of_rows_per_page = 10;  // 每页显示的记录数
$curr_page = "";
if (isset($_GET['next_page']))
        $curr_page = sql_quote($_GET['next_page']);
if ($curr_page == "") $curr_page = 1; // at first page


/**
 * show the simple mail list
 */
$query = sprintf(
			" SELECT t.team_id, t.name AS team_name, u.name AS user_name, " . 
			" n.name AS nation_name " . 
			" FROM session s, user_info u, club c, team t, nation n " . 
			" WHERE s.user_id=u.user_id AND c.user_id=u.user_id " . 
			" AND t.club_id=c.club_id AND c.nation_id=n.nation_id " . 
			" AND t.team_id<>'%s'" , 
			$s_primary_team_id);

$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page);

if (!$rs) {
	$error_message = "Database Error!"; // $db->ErrorMsg();
	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
}
else {
    $index = 1; 
    for (; !$rs->EOF; $rs->MoveNext(), $index++) {
    	
		$tpl->setCurrentBlock("online") ;
		if ($index % 2 != 0 )
			$tpl->setVariable("ONLINE_TR_CLASS", 'gSGRowEven') ;
		else 
			$tpl->setVariable("ONLINE_TR_CLASS", 'gSGRowOdd') ;

		
		
		$tpl->setVariable("TEAM_ID", $rs->fields['team_id']) ;
		$tpl->setVariable("USER_NAME", $rs->fields['user_name']) ;
		$tpl->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
		$tpl->setVariable("COUNTRY", $rs->fields['nation_name']) ;
		$tpl->parseCurrentBlock("online") ;
	
    }
	
	$tpl->setVariable("ONLINE_COUNT", $rs->RecordCount()) ;
}		


/**
 * pager
 */
$tpl_pager->setVariable("FORM_ACTION", "/fmol/page/mail/online_list.php") ;
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

