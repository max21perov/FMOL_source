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
$tpl->loadTemplatefile("free_coach_pool.tpl.php", true, true); 
// pager
$tpl_pager = new HTML_Template_ITX($TPL_PATCH); 
$tpl_pager->loadTemplatefile('pager.tpl.php', true, true); 

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------




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


$query = sprintf(
			" SELECT coach.coach_id, coach.custom_given_name AS given_name, " . 
			" coach.custom_family_name AS family_name, coach.age, " . 
			" coach.salary, fcp.bids " . 
			" FROM free_coach_pool fcp, coach " . 
			" WHERE fcp.coach_id=coach.coach_id " . 
			" %s " . 
			" ORDER BY fcp.start_time DESC " ,
			$sql_filter);  

$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page); 
if (!$rs) {
	$error_message = "Database error.";

	require (DOCUMENT_ROOT . "/page/system/error_maker.php");
	exit(0);
}
else {
	$index = 1;
    for (; !$rs->EOF; $rs->MoveNext(), $index++) {
		$tpl->setCurrentBlock('free_coach_pool') ;
		
		$tpl->setVariable("COACH_ID", $rs->fields['coach_id']) ;
		$tpl->setVariable("AGE", $rs->fields['age']) ;
		$tpl->setVariable("SALARY", $rs->fields['salary']) ;		
		$tpl->setVariable("BIDS", $rs->fields['bids']) ;
		
		$full_name = "";
		if ($rs->fields['given_name']  == "") {
			$full_name = $rs->fields['family_name'];
		}
		else {
			$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
		}
		$tpl->setVariable("COACH_NAME", $full_name) ;
		

		
		if ($index == 1)
			$tpl->setVariable("LIST_SEPARATOR", 'none') ;
		$tpl->setVariable("TRAN_TR_CLASS", 'gSGRowOdd');
	
	
		
		$tpl->parseCurrentBlock('free_coach_pool') ;
		 
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


