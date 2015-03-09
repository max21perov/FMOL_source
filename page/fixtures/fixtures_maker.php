<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once("fixtures_funtions.php");

// update the div_id 
// when a season finishes and the team's div_id may be changed
require_once(DOCUMENT_ROOT . "/page/system/update_div_id.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile($tpl_file_name, true, true); 

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$finish_filter = sql_quote($_GET["finish_filter"]); 
$type_filter = sql_quote($_GET["type_filter"]);

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
/**
 * get the div id by team_id
 */
$div_id = getDivIdOfTeam($db, $team_id);


/**
 * handle the filter of fixtures
 */
 
// finish_filter

if ($finish_filter == "0") { 
	// future
	$tpl->setVariable("FUTURE_FIXTURES_SELECTED", "selected") ;
}
else if ($finish_filter == "1") { 
	// finish
	$tpl->setVariable("FINISH_FIXTURES_SELECTED", "selected") ;
}
else { 
    // all
	$tpl->setVariable("ALL_FF_FIXTURES_SELECTED", "selected") ;
	
	$finish_filter = "10";
}



// type_filter
if ($type_filter == "1") {
	// league
	$tpl->setVariable("LEAGUE_FIXTURES_SELECTED", "selected") ;
	// form the query str
	$query = getLeagueFixturesQueryStr($team_id, $div_id, $finish_filter);
}
else if ($type_filter == "2") { 
	// friendly
	$tpl->setVariable("FRIENDLY_FIXTURES_SELECTED", "selected") ;
	// form the query str
	$query = getFriendlyFixturesQueryStr($team_id, $div_id, $finish_filter);
}
else { 
	// default: all
	$tpl->setVariable("ALL_FIXTURES_SELECTED", "selected") ;
	// form the query str
	$query = getAllFixturesQueryStr($team_id, $div_id, $finish_filter);
	
}
	



/**
 * execute the query str
 */
if (strlen($query) != 0) {	
	
	$rs = &$db->Execute($query);

	if (!$rs) {
		print "Database Error. " ;   //  $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		
		$schedule_exist = false;
		for ($index = 1; !$rs->EOF; $rs->MoveNext(), $index++) {
			if ($index == 1)
				$schedule_exist = true;
			
			$tpl->setCurrentBlock("fixtures") ;
			if ($index % 2 != 0 )
				$tpl->setVariable("FIXTURES_TR_CLASS", 'gSGRowEven_input') ;
			else 
				$tpl->setVariable("FIXTURES_TR_CLASS", 'gSGRowOdd_input') ;
			
			$tpl->setVariable("MATCH_ID", $rs->fields['schedule_id']) ;
			$tpl->setVariable("SELF_SCORE", $rs->fields("self_score")) ;
			$tpl->setVariable("OPPONENT_SCORE", $rs->fields("opp_score")) ;
			$tpl->setVariable("OPPONENT_PRIMARY_TEAM_ID", $rs->fields("opp_team_id")) ;
			$tpl->setVariable("OPPONENT_NAME", $rs->fields("opp_name")) ;
			$tpl->setVariable("HOME_OR_AWAY", $rs->fields("home_or_away")) ;
			$tpl->setVariable("MATCH_DATE", $rs->fields("match_date")) ;
			$tpl->setVariable("MATCH_TIME", $rs->fields("match_time")) ;
			$tpl->setVariable("MATCH_TYPE", $rs->fields("match_type")) ;
			if ($rs->fields("match_type") == "0") {
				$tpl->setVariable("MATCH_TYPE_STR", "schedule") ;
			}
			else if ($rs->fields("match_type") == "1") {
				$tpl->setVariable("MATCH_TYPE_STR", "friendly") ;
			}
			
			if ($rs->fields("played") == 1) {
				$tpl->setVariable("HAVE_PLEYED_DISPLAY", "") ;
				$tpl->setVariable("NOT_PLAYED_DISPLAY", "none") ;
			}
			else {
				$tpl->setVariable("HAVE_PLEYED_DISPLAY", "none") ;
				$tpl->setVariable("NOT_PLAYED_DISPLAY", "") ;			
			}
			
			
			$tpl->parseCurrentBlock("fixtures") ;
						
		}
	}		

}


$tpl->setVariable("TEAM_ID", $team_id);

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();



//----------------------------------------------------------------------------	
// common functions
//----------------------------------------------------------------------------	


?>





