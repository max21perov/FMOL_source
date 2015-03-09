<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");


// update the div_id 
// when a season finishes and the team's div_id may be changed
require_once(DOCUMENT_ROOT . "/page/system/update_div_id.php");

$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("schedule.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
/**
 * get the div id by team_id
 */
$div_id = "";

$query = sprintf(
			" SELECT div_id " .
			" FROM team_in_div " .
			" WHERE team_id='%s' " ,
			$team_id );
			
$rs = &$db->Execute($query);

if (!$rs) {
    print $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    if ($rs->RecordCount() > 0) {
        $div_id = $rs->fields['div_id'];
    }
}	

/**
 * get the div info 
 */
$query = sprintf(
			" SELECT name AS div_name, season " .
			" FROM division " .
			" WHERE div_id ='%s' " ,
			$div_id );
			
$rs = &$db->Execute($query);

if (!$rs) {
    print $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    if ($rs->RecordCount() > 0) {

		// set the variable of the template
        $tpl->setVariable("DIV_NAME", $rs->fields['div_name']);
		$tpl->setVariable("SEASON", $rs->fields['season']) ;
    }
}		

/**
 * get the schedule info
 */
$query = sprintf(
			"select sch.id AS schedule_id, sch.round, team1.name as home_team, sch.home_id, " .
			" team2.name as away_team, sch.away_id, sch.home_score, sch.away_score " .
			" from schedule sch, team team1, team team2 " .
			" where sch.div_id='%s' " .
			" and sch.home_id=team1.team_id and sch.away_id=team2.team_id " .
			" order by sch.round, team1.name, team2.name " ,
			$div_id );
			
$rs = &$db->Execute($query);

if (!$rs) {
    print $db->ErrorMsg(); // Displays the error message if no results could be returned
}
else {
    $pre_round = '';

    while (!$rs->EOF) {
	    // find the round which is not the same as before
		if(strcmp($rs->fields['round'], $pre_round) != 0)  {
			$pre_round = $rs->fields['round'];
		    $tpl->setVariable("ROUND", $rs->fields['round']) ;
			
			// find the rows whose round are the same, 
			// once not the same, jump outside the while loop
			$index = 1;
			while((!$rs->EOF)) {
				if(strcmp($rs->fields['round'], $pre_round) != 0)
				{			
					$rs->Move($rs->CurrentRow()-1);  //  Moves to the pre row
					break; 
				}
				else {
					$tpl->setCurrentBlock("schedule") ;
					if ($index % 2 != 0 )
					    $tpl->setVariable("SCHEDULE_TR_CLASS", 'gSGRowEven') ;
				    else 
					    $tpl->setVariable("SCHEDULE_TR_CLASS", 'gSGRowOdd') ;
					$index++;
					// set the self team name's class: to indicate the self team
					if ($rs->fields['home_id'] == $s_primary_team_id)
					    $tpl->setVariable("HOME_TEAM_CLASS", 'SelfTeamText') ;
					else 
					    $tpl->setVariable("HOME_TEAM_CLASS", 'OtherTeamText') ;						
					if ($rs->fields['away_id'] == $s_primary_team_id)
					    $tpl->setVariable("AWAY_TEAM_CLASS", 'SelfTeamText') ;
					else 
					    $tpl->setVariable("AWAY_TEAM_CLASS", 'OtherTeamText'); 
					
					$tpl->setVariable("MATCH_ID", $rs->fields['schedule_id']) ;
					$tpl->setVariable("HOME_PRIMARY_TEAM_ID", $rs->fields['home_id']) ;
					$tpl->setVariable("HOME_TEAM", $rs->fields['home_team']) ;
					$tpl->setVariable("HOME_SCORE", $rs->fields['home_score']) ;
					$tpl->setVariable("AWAY_PRIMARY_TEAM_ID", $rs->fields['away_id']) ;
					$tpl->setVariable("AWAY_TEAM", $rs->fields['away_team']) ;
					$tpl->setVariable("AWAY_SCORE", $rs->fields['away_score']) ;
					$tpl->parseCurrentBlock("schedule") ;
					
					$rs->MoveNext(); 
				}
			}
			
			
		    $tpl->setCurrentBlock("round") ;
    		$tpl->parseCurrentBlock("round") ;
		}
		
		$rs->MoveNext();  //  Moves to the next row
    }
}		


//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();

?>

