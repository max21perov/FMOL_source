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
$tpl->loadTemplatefile('friendly_list.tpl.php', true, true); 
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
$friendly_filter = sql_quote($_GET["friendly_filter"]); 

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
/**
 * set the default value of SEPARATOR
 */
$tpl->setVariable("FIX_SEPARATOR", "none") ;
$tpl->setVariable("APP_SEPARATOR", "none") ;
$tpl->setVariable("RSP_SEPARATOR", "none") ;
$tpl->setVariable("WAIT_SEPARATOR", "none") ;


/**
 * handle the filter of friendly status
 */
if ($friendly_filter != "") {
	$friendly_filter = intval($friendly_filter);
	
	switch($friendly_filter) {
	case 0: // app
		$tpl->setVariable("APP_FRIENDLY_SELECTED", "selected") ;
		break;
	case 1: // rsp
		$tpl->setVariable("RSP_FRIENDLY_SELECTED", "selected") ;
		break;
	case 2: // fix
		$tpl->setVariable("FIX_FRIENDLY_SELECTED", "selected") ;
		break;
	case 3: // all
		$tpl->setVariable("ALL_FRIENDLY_SELECTED", "selected") ;
		break;
	case 10: // wait
		$tpl->setVariable("WAIT_FRIENDLY_SELECTED", "selected") ;
		break;
	default: // default: wait
		$tpl->setVariable("ALL_FRIENDLY_SELECTED", "selected") ;
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


/**
 * get the friendly list from table "friendly", 
 * the status is fix, app, rsp
 */


// all
if (strlen($friendly_filter) == 0 || $friendly_filter == "3") {  
	// strlen($friendly_filter) == 0 表示$friendly_filter为空
	$query = " ( ";
	$query .= " SELECT f.id AS friendly_id, f.away_id AS opponent_id, ";  
	$query .= " u.name AS user_name, t.name AS team_name, "; 
	$query .= " 'H' AS home_or_away, f.status AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly f, team t, club c ";
	$query .= " LEFT JOIN user_info u ON c.user_id = u.user_id  ";
	$query .= sprintf(" WHERE f.home_id = '%s' ", $s_primary_team_id);
	$query .= " AND f.away_id = t.team_id ";
	$query .= " AND t.club_id = c.club_id ";
	$query .= " ORDER BY o_time DESC ";
	$query .= " ) ";
	$query .= " UNION "; 
	$query .= " ( ";
	$query .= " SELECT f.id AS friendly_id, f.home_id AS opponent_id, ";  
	$query .= " u.name AS user_name, t.name AS team_name, "; 
	$query .= " 'A' AS home_or_away, f.status AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly f, team t, club c ";
	$query .= " LEFT JOIN user_info u ON c.user_id = u.user_id  ";
	$query .= sprintf(" WHERE f.away_id = '%s' ", $s_primary_team_id);
	$query .= " AND f.home_id = t.team_id ";
	$query .= " AND t.club_id = c.club_id ";
	$query .= " ) ";
	$query .= " UNION "; 
	$query .= " ( ";
	$query .= " SELECT f.id AS friendly_id, _utf8'' AS opponent_id, ";
	$query .= " _utf8'' AS user_name, _utf8'' AS team_name, "; 
	$query .= " f.home_away AS home_or_away, '10' AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly_pool f ";
	$query .= sprintf(" WHERE f.team_id = '%s' ", $s_primary_team_id);
	//$query .= " ORDER BY home_or_away, o_time ";
	$query .= " ) ";
	$query .= " ORDER BY o_time DESC ";

	$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page);

	if (!$rs) {
	    print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else { 
		
		$index = 1;
		for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext(), $index++) {
			if ($rs->fields['match_status'] == 2) { 
				// fix
				$tpl->setCurrentBlock("friendly") ;
				// tpl_friendly
				$tpl_friendly = new HTML_Template_ITX($TPL_PATCH); 
				$tpl_friendly->loadTemplatefile('fix_friendly.tpl.php', true, true); 
//				if ($index % 2 != 0 ) {
//					$tpl_friendly->setVariable("FIX_FRIENDLY_TR_CLASS", 'gSGRowEven_input') ; 
//				}
//				else { 
					$tpl_friendly->setVariable("FIX_FRIENDLY_TR_CLASS", 'gSGRowOdd_input') ; 
//				}
				
				$tpl_friendly->setVariable("STATUS", 'fix') ;
				$tpl_friendly->setVariable("USER_NAME", $rs->fields['user_name']) ;
				$tpl_friendly->setVariable("OPPONENT_PRIMARY_TEAM_ID", $rs->fields['opponent_id']) ;
				$tpl_friendly->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
				$tpl_friendly->setVariable("HOME_OR_AWAY", $rs->fields['home_or_away']) ;
				$tpl_friendly->setVariable("MATCH_DATE", $rs->fields['match_date']) ;
				$tpl_friendly->setVariable("MATCH_TIME", $rs->fields['match_time']) ;
				$tpl_friendly->setVariable("OPPONENT_ID", $rs->fields['opponent_id']) ;
				$tpl_friendly->setVariable("NEXT_PAGE", $rs->AbsolutePage()) ;
				$tpl_friendly->setVariable("FRIENDLY_FILTER", $friendly_filter) ;
				
				$tpl->setVariable("FRIENDLY_ENTIRY", $tpl_friendly->get()) ;
				$tpl->parseCurrentBlock("friendly") ;
			}
			else if ($rs->fields['match_status'] == 0 && $rs->fields['home_or_away'] == 'H' ||
				$rs->fields['match_status'] == 1 && $rs->fields['home_or_away'] == 'A') {
				// app
				$tpl->setCurrentBlock("friendly") ;	
				// tpl_friendly
				$tpl_friendly = new HTML_Template_ITX($TPL_PATCH); 			
				$tpl_friendly->loadTemplatefile('app_friendly.tpl.php', true, true); 
				
//				if ($index % 2 != 0 )
//					$tpl_friendly->setVariable("APP_FRIENDLY_TR_CLASS", 'gSGRowEven_input') ;
//				else 
					$tpl_friendly->setVariable("APP_FRIENDLY_TR_CLASS", 'gSGRowOdd_input') ;
			
				$tpl_friendly->setVariable("STATUS", 'app') ;
				$tpl_friendly->setVariable("USER_NAME", $rs->fields['user_name']) ;
				$tpl_friendly->setVariable("OPPONENT_PRIMARY_TEAM_ID", $rs->fields['opponent_id']) ;
				$tpl_friendly->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
				$tpl_friendly->setVariable("HOME_OR_AWAY", $rs->fields['home_or_away']) ;
				$tpl_friendly->setVariable("MATCH_DATE", $rs->fields['match_date']) ;
				$tpl_friendly->setVariable("MATCH_TIME", $rs->fields['match_time']) ;
				$tpl_friendly->setVariable("FRIENDLY_ID", $rs->fields['friendly_id']) ;
				$tpl_friendly->setVariable("OPPONENT_ID", $rs->fields['opponent_id']) ;
				$tpl_friendly->setVariable("NEXT_PAGE", $rs->AbsolutePage()) ;   // 对翻页有用  
				$tpl_friendly->setVariable("FRIENDLY_FILTER", $friendly_filter) ;   // 对翻页有用  
				
				$tpl->setVariable("FRIENDLY_ENTIRY", $tpl_friendly->get()) ;
				$tpl->parseCurrentBlock("friendly") ;
			}
			else if ($rs->fields['match_status'] == 1 && $rs->fields['home_or_away'] == 'H' ||
				$rs->fields['match_status'] == 0 && $rs->fields['home_or_away'] == 'A') {
				// rsp_friendly
				$tpl->setCurrentBlock("friendly") ;
				// tpl_friendly
				$tpl_friendly = new HTML_Template_ITX($TPL_PATCH); 
				$tpl_friendly->loadTemplatefile('rsp_friendly.tpl.php', true, true); 
				
//				if ($index % 2 != 0 )
//					$tpl_friendly->setVariable("RSP_FRIENDLY_TR_CLASS", 'gSGRowEven_input') ;
//				else 
					$tpl_friendly->setVariable("RSP_FRIENDLY_TR_CLASS", 'gSGRowOdd_input') ;
		
				$tpl_friendly->setVariable("STATUS", 'rsp') ;
				$tpl_friendly->setVariable("USER_NAME", $rs->fields['user_name']) ;
				$tpl_friendly->setVariable("OPPONENT_PRIMARY_TEAM_ID", $rs->fields['opponent_id']) ;
				$tpl_friendly->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
				$tpl_friendly->setVariable("HOME_OR_AWAY", $rs->fields['home_or_away']) ;
				$tpl_friendly->setVariable("MATCH_DATE", $rs->fields['match_date']) ;
				$tpl_friendly->setVariable("MATCH_TIME", $rs->fields['match_time']) ;
				$tpl_friendly->setVariable("FRIENDLY_ID", $rs->fields['friendly_id']) ;
				$tpl_friendly->setVariable("OPPONENT_ID", $rs->fields['opponent_id']) ;
				$tpl_friendly->setVariable("NEXT_PAGE", $rs->AbsolutePage()) ;   // 对翻页有用  
				$tpl_friendly->setVariable("FRIENDLY_FILTER", $friendly_filter) ; // 对翻页有用  
				
				$tpl->setVariable("FRIENDLY_ENTIRY", $tpl_friendly->get()) ;
				$tpl->parseCurrentBlock("friendly") ;
			}
			else if ($rs->fields['match_status'] == "10") {
				// wait_friendly
				$tpl->setCurrentBlock("friendly") ;
				// tpl_friendly
				$tpl_friendly = new HTML_Template_ITX($TPL_PATCH); 
				$tpl_friendly->loadTemplatefile('wait_friendly.tpl.php', true, true); 
				
//				if ($index % 2 != 0 )
//					$tpl_friendly->setVariable("WAIT_FRIENDLY_TR_CLASS", 'gSGRowEven_input') ;
//				else 
					$tpl_friendly->setVariable("WAIT_FRIENDLY_TR_CLASS", 'gSGRowOdd_input') ;
			
				$tpl_friendly->setVariable("STATUS", 'wait') ;
				if ($rs->fields['home_or_away'] == "0")
					$tpl_friendly->setVariable("HOME_OR_AWAY", 'H') ;
				else 
					$tpl_friendly->setVariable("HOME_OR_AWAY", 'A') ;
				$tpl_friendly->setVariable("MATCH_DATE", $rs->fields['match_date']) ;
				$tpl_friendly->setVariable("MATCH_TIME", $rs->fields['match_time']) ;
				$tpl_friendly->setVariable("FRIENDLY_POOL_ID", $rs->fields['friendly_id']) ;
				$tpl_friendly->setVariable("NEXT_PAGE", $rs->AbsolutePage()) ;
				$tpl_friendly->setVariable("FRIENDLY_FILTER", $friendly_filter) ;
				
				$tpl->setVariable("FRIENDLY_ENTIRY", $tpl_friendly->get()) ;
				$tpl->parseCurrentBlock("friendly") ;
		
			}
			 
		}
	}
}

// fix
else if ($friendly_filter == "2") {  
	$query = " ( ";
	$query .= " SELECT f.id AS friendly_id, f.away_id AS opponent_id, ";  
	$query .= " u.name AS user_name, t.name AS team_name, "; 
	$query .= " 'H' AS home_or_away, f.status AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly f, team t, club c ";
	$query .= " LEFT JOIN user_info u ON c.user_id = u.user_id  ";
	$query .= sprintf(" WHERE f.home_id = '%s' ", $s_primary_team_id);
	$query .= " AND f.away_id = t.team_id ";
	$query .= " AND t.club_id = c.club_id ";
	$query .= " AND f.status='2' ";	
	$query .= " ORDER BY o_time DESC ";
	$query .= " ) ";
	$query .= " UNION "; 
	$query .= " ( ";
	$query .= " SELECT f.id AS friendly_id, f.home_id AS opponent_id, ";  
	$query .= " u.name AS user_name, t.name AS team_name, "; 
	$query .= " 'A' AS home_or_away, f.status AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly f, team t, club c ";
	$query .= " LEFT JOIN user_info u ON c.user_id = u.user_id  ";
	$query .= sprintf(" WHERE f.away_id = '%s' ", $s_primary_team_id);
	$query .= " AND f.home_id = t.team_id ";
	$query .= " AND t.club_id = c.club_id ";
	$query .= " AND f.status='2' ";	
	$query .= " ) ";
	$query .= " ORDER BY o_time DESC ";

	$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page);

	if (!$rs) {
	    print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else { 
		
		$index = 1;
		for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext(), $index++) {
			$tpl->setCurrentBlock("friendly") ;
			// tpl_friendly
			$tpl_friendly = new HTML_Template_ITX($TPL_PATCH); 
			$tpl_friendly->loadTemplatefile('fix_friendly.tpl.php', true, true); 
//			if ($index % 2 != 0 )
//				$tpl_friendly->setVariable("FIX_FRIENDLY_TR_CLASS", 'gSGRowEven_input') ;
//			else 
				$tpl_friendly->setVariable("FIX_FRIENDLY_TR_CLASS", 'gSGRowOdd_input') ;
		
			$tpl_friendly->setVariable("STATUS", 'fix') ;
			$tpl_friendly->setVariable("USER_NAME", $rs->fields['user_name']) ;
			$tpl_friendly->setVariable("OPPONENT_PRIMARY_TEAM_ID", $rs->fields['opponent_id']) ;
			$tpl_friendly->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
			$tpl_friendly->setVariable("HOME_OR_AWAY", $rs->fields['home_or_away']) ;
			$tpl_friendly->setVariable("MATCH_DATE", $rs->fields['match_date']) ;
			$tpl_friendly->setVariable("MATCH_TIME", $rs->fields['match_time']) ;
			$tpl_friendly->setVariable("OPPONENT_ID", $rs->fields['opponent_id']) ;
			$tpl_friendly->setVariable("NEXT_PAGE", $rs->AbsolutePage()) ;
			$tpl_friendly->setVariable("FRIENDLY_FILTER", $friendly_filter) ;
			
			$tpl->setVariable("FRIENDLY_ENTIRY", $tpl_friendly->get()) ;
			$tpl->parseCurrentBlock("friendly") ;
		
		}
	}
}

// app
else if ($friendly_filter == "0") {  
	$query = " ( ";
	$query .= " SELECT f.id AS friendly_id, f.away_id AS opponent_id, ";  
	$query .= " u.name AS user_name, t.name AS team_name, "; 
	$query .= " 'H' AS home_or_away, f.status AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly f, team t, club c ";
	$query .= " LEFT JOIN user_info u ON c.user_id = u.user_id  ";
	$query .= sprintf(" WHERE f.home_id = '%s' ", $s_primary_team_id);
	$query .= " AND f.away_id = t.team_id ";
	$query .= " AND t.club_id = c.club_id ";
	$query .= " AND f.status = '0' ";
	$query .= " ORDER BY o_time DESC ";
	$query .= " ) ";
	$query .= " UNION "; 
	$query .= " ( ";
	$query .= " SELECT f.id AS friendly_id, f.home_id AS opponent_id, ";  
	$query .= " u.name AS user_name, t.name AS team_name, "; 
	$query .= " 'A' AS home_or_away, f.status AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly f, team t, club c ";
	$query .= " LEFT JOIN user_info u ON c.user_id = u.user_id  ";
	$query .= sprintf(" WHERE f.away_id = '%s' ", $s_primary_team_id);
	$query .= " AND f.home_id = t.team_id ";
	$query .= " AND t.club_id = c.club_id ";
	$query .= " AND f.status = '1' ";
	$query .= " ) ";
	$query .= " ORDER BY o_time DESC ";
	
	$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page);

	if (!$rs) {
	    print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else { 
		
		$index = 1;
		for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext(), $index++) {  
				
			$tpl->setCurrentBlock("friendly") ;	
			// tpl_friendly
			$tpl_friendly = new HTML_Template_ITX($TPL_PATCH); 			
			$tpl_friendly->loadTemplatefile('app_friendly.tpl.php', true, true); 
			
//			if ($index % 2 != 0 )
//				$tpl_friendly->setVariable("APP_FRIENDLY_TR_CLASS", 'gSGRowEven_input') ;
//			else 
				$tpl_friendly->setVariable("APP_FRIENDLY_TR_CLASS", 'gSGRowOdd_input') ;
			
			$tpl_friendly->setVariable("STATUS", 'app') ;
			$tpl_friendly->setVariable("USER_NAME", $rs->fields['user_name']) ;
			$tpl_friendly->setVariable("OPPONENT_PRIMARY_TEAM_ID", $rs->fields['opponent_id']) ;
			$tpl_friendly->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
			$tpl_friendly->setVariable("HOME_OR_AWAY", $rs->fields['home_or_away']) ;
			$tpl_friendly->setVariable("MATCH_DATE", $rs->fields['match_date']) ;
			$tpl_friendly->setVariable("MATCH_TIME", $rs->fields['match_time']) ;
			$tpl_friendly->setVariable("FRIENDLY_ID", $rs->fields['friendly_id']) ;
			$tpl_friendly->setVariable("OPPONENT_ID", $rs->fields['opponent_id']) ;
			$tpl_friendly->setVariable("NEXT_PAGE", $rs->AbsolutePage()) ;
			$tpl_friendly->setVariable("FRIENDLY_FILTER", $friendly_filter) ;
			
			$tpl->setVariable("FRIENDLY_ENTIRY", $tpl_friendly->get()) ;
			$tpl->parseCurrentBlock("friendly") ;
			
		}
	}
}

// rsp
else if ($friendly_filter == "1") {   
	$query = " ( ";
	$query .= " SELECT f.id AS friendly_id, f.away_id AS opponent_id, ";  
	$query .= " u.name AS user_name, t.name AS team_name, "; 
	$query .= " 'H' AS home_or_away, f.status AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly f, team t, club c ";
	$query .= " LEFT JOIN user_info u ON c.user_id = u.user_id  ";
	$query .= sprintf(" WHERE f.home_id = '%s' ", $s_primary_team_id);
	$query .= " AND f.away_id = t.team_id ";
	$query .= " AND t.club_id = c.club_id ";
	$query .= " AND f.status = '1' ";
	$query .= " ORDER BY o_time DESC ";
	$query .= " ) ";
	$query .= " UNION "; 
	$query .= " ( ";
	$query .= " SELECT f.id AS friendly_id, f.home_id AS opponent_id, ";  
	$query .= " u.name AS user_name, t.name AS team_name, "; 
	$query .= " 'A' AS home_or_away, f.status AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly f, team t, club c ";
	$query .= " LEFT JOIN user_info u ON c.user_id = u.user_id  ";
	$query .= sprintf(" WHERE f.away_id = '%s' ", $s_primary_team_id);
	$query .= " AND f.home_id = t.team_id ";
	$query .= " AND t.club_id = c.club_id ";
	$query .= " AND f.status = '0' ";
	$query .= " ) ";
	$query .= " ORDER BY o_time DESC ";
	
	$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page);

	if (!$rs) {
	    print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else { 
		
		$index = 1;
		for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext(), $index++) {
				
			$tpl->setCurrentBlock("friendly") ;
			// tpl_friendly
			$tpl_friendly = new HTML_Template_ITX($TPL_PATCH); 
			$tpl_friendly->loadTemplatefile('rsp_friendly.tpl.php', true, true); 
			
//			if ($index % 2 != 0 )
//				$tpl_friendly->setVariable("RSP_FRIENDLY_TR_CLASS", 'gSGRowEven_input') ;
//			else 
				$tpl_friendly->setVariable("RSP_FRIENDLY_TR_CLASS", 'gSGRowOdd_input') ;
	
			$tpl_friendly->setVariable("STATUS", 'rsp') ;
			$tpl_friendly->setVariable("USER_NAME", $rs->fields['user_name']) ;
			$tpl_friendly->setVariable("OPPONENT_PRIMARY_TEAM_ID", $rs->fields['opponent_id']) ;
			$tpl_friendly->setVariable("TEAM_NAME", $rs->fields['team_name']) ;
			$tpl_friendly->setVariable("HOME_OR_AWAY", $rs->fields['home_or_away']) ;
			$tpl_friendly->setVariable("MATCH_DATE", $rs->fields['match_date']) ;
			$tpl_friendly->setVariable("MATCH_TIME", $rs->fields['match_time']) ;
			$tpl_friendly->setVariable("FRIENDLY_ID", $rs->fields['friendly_id']) ;
			$tpl_friendly->setVariable("OPPONENT_ID", $rs->fields['opponent_id']) ;
			$tpl_friendly->setVariable("NEXT_PAGE", $rs->AbsolutePage()) ;
			$tpl_friendly->setVariable("FRIENDLY_FILTER", $friendly_filter) ;
			
			$tpl->setVariable("FRIENDLY_ENTIRY", $tpl_friendly->get()) ;
			$tpl->parseCurrentBlock("friendly") ;
			
		}
	}
}

// wait
else if ($friendly_filter == "10") {	
	
	$query  = " SELECT f.id AS friendly_id, f.id AS opponent_id, ";
	$query .= " f.time AS user_name, f.id AS team_name, "; 
	$query .= " f.home_away AS home_or_away, '10' AS match_status, "; 
	$query .= " date_format( f.time, '%a %c.%e' ) AS match_date, "; 
	$query .= " date_format( f.time, '%H:%i' ) AS match_time, "; 
	$query .= " f.time AS o_time ";
	$query .= " FROM friendly_pool f ";
	$query .= sprintf(" WHERE f.team_id = '%s' ", $s_primary_team_id);
	$query .= " ORDER BY home_or_away ASC, o_time DESC ";

	
	$rs = &$db->PageExecute($query, $num_of_rows_per_page, $curr_page);

	if (!$rs) {
	    print "Database error."; // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else { 
		
		$index = 1;
		for ($rs->MoveFirst(); !$rs->EOF; $rs->MoveNext(), $index++) {
				
			$tpl->setCurrentBlock("friendly") ;
			// tpl_friendly
			$tpl_friendly = new HTML_Template_ITX($TPL_PATCH); 
			$tpl_friendly->loadTemplatefile('wait_friendly.tpl.php', true, true); 
			
//			if ($index % 2 != 0 )
//				$tpl_friendly->setVariable("WAIT_FRIENDLY_TR_CLASS", 'gSGRowEven_input') ;
//			else 
			$tpl_friendly->setVariable("WAIT_FRIENDLY_TR_CLASS", 'gSGRowOdd_input') ;
	
			$tpl_friendly->setVariable("STATUS", 'wait') ;
			if ($rs->fields['home_or_away'] == "0")
				$tpl_friendly->setVariable("HOME_OR_AWAY", 'H') ;
			else 
				$tpl_friendly->setVariable("HOME_OR_AWAY", 'A') ;
			$tpl_friendly->setVariable("MATCH_DATE", $rs->fields['match_date']) ;
			$tpl_friendly->setVariable("MATCH_TIME", $rs->fields['match_time']) ;
			$tpl_friendly->setVariable("FRIENDLY_POOL_ID", $rs->fields['friendly_id']) ;
			$tpl_friendly->setVariable("NEXT_PAGE", $rs->AbsolutePage()) ;
			$tpl_friendly->setVariable("FRIENDLY_FILTER", $friendly_filter) ;
			
			$tpl->setVariable("FRIENDLY_ENTIRY", $tpl_friendly->get()) ;
			$tpl->parseCurrentBlock("friendly") ;				
	
		}	// while
	}
}

	


/**
 * pager
 */
$tpl_pager->setVariable("FORM_ACTION", "/fmol/page/friendly/friendly_list.php") ;
$tpl_pager->setVariable("FILTER_NAME", "friendly_filter"); 
$tpl_pager->setVariable("FILTER_VALUE", $friendly_filter);
$tpl_pager->setVariable("CURRENT_PAGE_NUM", $rs->AbsolutePage()) ;
$tpl_pager->setVariable("TOTAL_PAGE_NUM", ($rs->LastPageNo(false)=="-1") ? "1" : $rs->LastPageNo(false)) ;
$tpl_pager->setVariable("TOTAL_RECORD_NUM", $rs->MaxRecordCount()) ;
// pre page
if (!$rs->AtFirstPage()) {
	$tpl_pager->setVariable("PRE_PAGE_URL", "<a href=$PHPSELF?next_page=". ($rs->AbsolutePage() - 1). "&friendly_filter=$friendly_filter>pre</a>") ;
}
else if ($rs->AtFirstPage()) {
	$tpl_pager->setVariable("PRE_PAGE_URL", "") ;
}
// next page
if (!$rs->AtLastPage()) {
	$tpl_pager->setVariable("NEXT_PAGE_URL", "<a href=$PHPSELF?next_page=". ($rs->AbsolutePage() + 1). "&friendly_filter=$friendly_filter>next</a>") ;
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

