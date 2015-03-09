<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once("team_stats_functions.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("match_formations.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = $_SESSION['s_primary_team_id'];

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$match_type = sql_quote($_GET['match_type']);
$match_id = sql_quote($_GET['match_id']);

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	


/**
 * get the home team id
 */
$match_team_arr = getMatchTeamArr($db, $match_type, $match_id);
$home_team_id = $match_team_arr["home_team_id"];
$away_team_id = $match_team_arr["away_team_id"];

// set the self team name's class: to indicate the self team					
$tpl->setVariable("HOME_PRIMARY_TEAM_ID", $home_team_id) ;
$tpl->setVariable("HOME_TEAM_NAME", $match_team_arr['home_team_name']) ;
$tpl->setVariable("AWAY_PRIMARY_TEAM_ID", $away_team_id) ;
$tpl->setVariable("AWAY_TEAM_NAME", $match_team_arr['away_team_name']) ;
			




//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------
// add a space in the page to show the page any time
$tpl->setVariable("SPACE", " ");

$tpl->show();


//----------------------------------------------------------------------------	
// functions
//----------------------------------------------------------------------------


?>


