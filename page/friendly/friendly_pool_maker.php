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
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);  

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile('friendly_pool.tpl.php', true, true); 
// pager
$tpl_pager = new HTML_Template_ITX($TPL_PATCH); 
$tpl_pager->loadTemplatefile('pager.tpl.php', true, true); 

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
 * get the friendly poollist from table "friendly_pool", 
 */
$query = " SELECT u.name AS user_name, t.name AS team_name, "; 
$query .= " f.id AS friendly_pool_id, f.home_away, f.team_id AS owner_team_id, "; 
$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
$query .= " f.time AS o_time ";
$query .= " FROM friendly_pool f, team t, club c, user_info u ";
$query .= " WHERE f.team_id = t.team_id ";
$query .= " AND t.club_id = c.club_id ";
$query .= " AND c.user_id = u.user_id ";
$query .= " ORDER BY user_name, o_time ";

$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page);

if (!$rs) {
    print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    $index = 1;
    for (; !$rs->EOF; $rs->MoveNext(), $index++) {

		$tpl->setCurrentBlock("friendly_pool") ;
//		if ($index % 2 != 0 )
//			$tpl->setVariable("FRIENDLY_POOL_TR_CLASS", 'gSGRowEven_input') ;
//		else 
			$tpl->setVariable("FRIENDLY_POOL_TR_CLASS", 'gSGRowOdd_input') ;
	
		$tpl->setVariable("USER_NAME", $rs->fields['user_name']) ;
		if ($rs->fields['owner_team_id'] == $s_primary_team_id)
			$tpl->setVariable("TEAM_CLASS", 'SelfTeamText') ;
		else 
			$tpl->setVariable("TEAM_CLASS", 'OtherTeamText') ;
		$tpl->setVariable("PRIMARY_TEAM_ID", $rs->fields['owner_team_id']) ;
		$tpl->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
		if ($rs->fields['home_away'] == 0)
		    $tpl->setVariable("HOME_OR_AWAY", 'H') ;
		else 
		    $tpl->setVariable("HOME_OR_AWAY", 'A') ;
		$tpl->setVariable("MATCH_DATE", $rs->fields['match_date']) ;
		$tpl->setVariable("MATCH_TIME", $rs->fields['match_time']) ;
		$tpl->setVariable("FRIENDLY_POOL_ID", $rs->fields['friendly_pool_id']) ;
		$tpl->setVariable("OWNER_TEAM_ID", $rs->fields['owner_team_id']) ;;
		$tpl->setVariable("O_TIME", $rs->fields['o_time']) ;
		$tpl->parseCurrentBlock("friendly_pool") ;

    }
}	


/**
 * pager
 */
$tpl_pager->setVariable("FORM_ACTION", "/fmol/page/friendly/friendly_pool.php") ;

$tpl_pager->setVariable("CURRENT_PAGE_NUM", $rs->AbsolutePage()) ;
$tpl_pager->setVariable("TOTAL_PAGE_NUM", ($rs->LastPageNo(false)=="-1") ? "1" : $rs->LastPageNo(false)) ;
$tpl_pager->setVariable("TOTAL_RECORD_NUM", $rs->MaxRecordCount()) ;
// pre page
if (!$rs->AtFirstPage()) {
	$tpl_pager->setVariable("PRE_PAGE_URL", "<a href=$PHPSELF?next_page=". ($rs->AbsolutePage() - 1). ">pre</a>") ;
}
else if ($rs->AtFirstPage()) {
	$tpl_pager->setVariable("PRE_PAGE_URL", "") ;
}
// next page
if (!$rs->AtLastPage()) {
	$tpl_pager->setVariable("NEXT_PAGE_URL", "<a href=$PHPSELF?next_page=". ($rs->AbsolutePage() + 1). ">next</a>") ;
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

