<?php

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
// require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php"); // 注意，这里不能使用adodb的方法来访问数据库，只能使用最原始的方法
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once("draw_image_functions.php");



//----------------------------------------------------------------------------	
// use the normal method to visit the database
//----------------------------------------------------------------------------	
$link = mysql_connect("localhost", "fmolphp", "123") ;  //or die("Could not connect: " . mysql_error()); 
mysql_select_db("fmol");

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$match_type = sql_quote($_GET['match_type']);
$match_id = sql_quote($_GET['match_id']);
$PR_type = sql_quote($_GET['PR_type']);


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	


$PR_opposite = array(
			   		"PR_F" => "PR_B", "PR_M" => "PR_M", "PR_B" => "PR_F",
			   		"PR_L" => "PR_R", "PR_C" => "PR_C", "PR_R" => "PR_L",
			   		"PROZ_0" => "PROZ_8", "PROZ_1" => "PROZ_7", "PROZ_2" => "PROZ_6",
			   		"PROZ_3" => "PROZ_5", "PROZ_4" => "PROZ_4", "PROZ_5" => "PROZ_3",
			   		"PROZ_6" => "PROZ_2", "PROZ_7" => "PROZ_1", "PROZ_8" => "PROZ_0"
			   );

/**
 * get the match team arr
 */

$match_team_arr = getMatchTeamArr($match_type, $match_id);
$home_team_id = $match_team_arr['home_team_id'];
$away_team_id = $match_team_arr['away_team_id'];

// PR_arr: T F M B L C R, PROZ_0~8
$PR_arr = getPRArr($match_type, $match_id, $home_team_id, $away_team_id); 

// theme_color
$theme_color_arr = getThemeColorArr($home_team_id, $away_team_id); 

$item_arr = array(
				array("percent" => $PR_arr[$home_team_id][$PR_type], 
				      "bg_color" => $theme_color_arr[$home_team_id]["bg_color"], 
					  "font_color" => $theme_color_arr[$home_team_id]["font_color"]), 
				array("percent" => $PR_arr[$away_team_id][$PR_opposite[$PR_type]], 
				      "bg_color" => $theme_color_arr[$away_team_id]["bg_color"], 
					  "font_color" => $theme_color_arr[$away_team_id]["font_color"])
			);

$img_width = 110;
$img_height = 100;
$border = 50;
imageColumnS($img_width, $img_height, $item_arr, $border);




//----------------------------------------------------------------------------	
// functions
//----------------------------------------------------------------------------	
/**
 * get Match Team Arr
 *
 * @param [db]						db	
 * @param [match_id]				match_id	
 *
 * @return  $match_team_arr
 *   $match_team_arr["home_team_id"]
 *   $match_team_arr["home_team_name"]
 *   $match_team_arr["away_team_id"]
 *   $match_team_arr["away_team_name"]
 */	
function getMatchTeamArr($match_type, $match_id)
{
	
	$match_team_arr = array();
	$match_type_str = "schedule";
	switch(intval($match_type)) {
	case 0:
		$match_type_str = "schedule";
		break;
	case 1:
		$match_type_str = "friendly";
		break;
	}
	
	$query = sprintf(
				" select team1.name as home_team, m.home_id, " . 
				" team2.name as away_team, m.away_id " .
				" from %s m, team team1, team team2 " .
				" where m.id='%s' " .
				" and m.home_id=team1.team_id and m.away_id=team2.team_id ",
				$match_type_str, $match_id);
				
	$result = mysql_query($query);
	

	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

		
		$match_team_arr["home_team_id"] = $row['home_id'];
		$match_team_arr["home_team_name"] = $row['home_team'];
		$match_team_arr["away_team_id"] = $row['away_id'];
		$match_team_arr["away_team_name"] = $row['away_team'];
		
	}
	
	
	return $match_team_arr;
	
}

/**
 * get PR: T F M B L C R, PROZ_0~8
 *
 * @param [db]					db	
 * @param [match_id]			match_id	
 * @param [home_team_id]		home_team_id
 * @param [away_team_id]		away_team_id
 *
 * @return  $PR_arr
 */	
function getPRArr($match_type, $match_id, $home_team_id, $away_team_id)
{
	$PR_arr = array();

	$query = sprintf(
				" select team_id, PR_T, PR_F, PR_M, PR_B, " . 
				" PR_L, PR_C, PR_R, PROZ_0, PROZ_1, " . 
				" PROZ_2, PROZ_3, PROZ_4, PROZ_5, PROZ_6, " .
				" PROZ_7, PROZ_8 " .
				" from team_match_stat " .
				" where match_type='%s' and match_id='%s' and (team_id='%s' or team_id='%s') " .
				" order by team_id ASC ",
				$match_type, $match_id, $home_team_id, $away_team_id);
				
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))  { 
		$team_id = $row['team_id'];
		
		$PR_arr[$team_id]['PR_T'] = $row['PR_T'];			
		$PR_arr[$team_id]['PR_F'] = $row['PR_F'];
		$PR_arr[$team_id]['PR_M'] = $row['PR_M'];
		$PR_arr[$team_id]['PR_B'] = $row['PR_B'];
		$PR_arr[$team_id]['PR_L'] = $row['PR_L'];
		$PR_arr[$team_id]['PR_C'] = $row['PR_C'];
		$PR_arr[$team_id]['PR_R'] = $row['PR_R'];
		$PR_arr[$team_id]['PROZ_0'] = $row['PROZ_0'];
		$PR_arr[$team_id]['PROZ_1'] = $row['PROZ_1'];
		$PR_arr[$team_id]['PROZ_2'] = $row['PROZ_2'];
		$PR_arr[$team_id]['PROZ_3'] = $row['PROZ_3'];
		$PR_arr[$team_id]['PROZ_4'] = $row['PROZ_4'];
		$PR_arr[$team_id]['PROZ_5'] = $row['PROZ_5'];
		$PR_arr[$team_id]['PROZ_6'] = $row['PROZ_6'];
		$PR_arr[$team_id]['PROZ_7'] = $row['PROZ_7'];
		$PR_arr[$team_id]['PROZ_8'] = $row['PROZ_8'];
	}		
	
	return $PR_arr;
}

/**
 * get PR: T F M B L C R, PROZ_0~8
 *
 * @param [db]					db	
 * @param [home_team_id]		home_team_id
 * @param [away_team_id]		away_team_id
 *
 * @return  $theme_color_arr
 */	
function getThemeColorArr($home_team_id, $away_team_id)
{
	$theme_color_arr = array();

	$query = sprintf(
				" select team_id, bg_color, font_color " . 
				" from theme_color " .
				" where team_id='%s' or team_id='%s' " .
				" order by team_id ASC ",
				$home_team_id, $away_team_id);
				
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$team_id = $row['team_id'];
		
		$theme_color_arr[$team_id]['bg_color'] 		= $row['bg_color'];			
		$theme_color_arr[$team_id]['font_color'] 	= $row['font_color'];

	}		
	
	return $theme_color_arr;
	
}


?>