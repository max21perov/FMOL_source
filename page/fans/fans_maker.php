<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");


// update the div_id 
// when a season finishes and the team's div_id may be changed
require_once(DOCUMENT_ROOT . "/page/system/update_div_id.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile('fans.tpl.php', true, true); 
		
//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);
$s_primary_club_id = sql_quote($_SESSION['s_primary_club_id']);


//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$match_num = sql_quote($_GET["match_num"]);
// 当路径为 /fmol/page/fans/fans.php 时，默认显示最近5场比赛
if ($match_num == "") 
	$match_num = "5";   // recently 5 matches


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * get the fans basic information
 */
$fans_info_arr = getFansBasicInfo($db, DOCUMENT_ROOT, $s_primary_club_id);
$tpl->setVariable("FANS_NUMBER", number_format($fans_info_arr["fans_num"], null, null, ","));
$tpl->setVariable("SEASON_EXPECTION", $fans_info_arr["season_expectation"]);
$tpl->setVariable("FANS_SATISFACTION", floatval($fans_info_arr["satisfaction"])*100 . "%");
$tpl->setVariable("FANS_CORE_RATE", floatval($fans_info_arr["core_rate"])*100 . "%");
$tpl->setVariable("LATEST_OPTION", $fans_info_arr["latest_option"]);
$tpl->setVariable("SEASON_OPTION", $fans_info_arr["season_option"]);

/**
 * get the fund: season_begin_fund and current_fund
 */
$match_info_arr = getRecentlyMatchInfo($db, DOCUMENT_ROOT, $s_primary_team_id, $match_num);
$len = count($match_info_arr);
// 因为 select 语句用了 order by match_time DESC
// 所以这里要从位置为 $len-1 的记录开始显示
for ($i=$len-1; $i>=0; --$i) {
	$match_info = $match_info_arr[$i];
	$vs_result = $match_info["home_name"] . " - " . $match_info["away_name"] .
					"  " . $match_info["home_score"] . ":" . $match_info["away_score"];
					
	$audience_num = 0;
	if ($match_info["audience_num"] != "") {
		$audience_num = $match_info["audience_num"];
	}
	$gate_receipts = 0;
	if ($match_info["gate_receipts"] != "") {
		$gate_receipts = $match_info["gate_receipts"];
	}
	
	$tpl->setCurrentBlock("match");
	$tpl->setVariable("MATCH_DATE", $match_info["match_date"]);
	$tpl->setVariable("MATCH_TYPE", $match_info["match_type"]);
	$tpl->setVariable("VS_RESULT", $vs_result);
	
	$tpl->setVariable("AUDIENCE_NUMBER", $audience_num);
	$tpl->setVariable("RECEIPTS", $gate_receipts);
	
	$tpl->parseCurrentBlock("match");
}
if ($match_num == "all") {
	$tpl->setVariable("MATCH_NUM_BUTTON_VALUE", "recently 5 match");	
}
else {
	$tpl->setVariable("MATCH_NUM_BUTTON_VALUE", "all");	
}


// 给action_str赋值
$action_str = "action1:action 1|action2:action 2|action3:action 3|action4:action 4|action5:action 5";
$tpl->setVariable("ACTION_STR", $action_str);
$tpl->setVariable("ACTION_EXPLAIN", "action 1");


//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");
$tpl->show();



//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------	
/**
  * get the fans basic information
  *
  * @param [db]				database
  * @param [document_root]	document_root
  * @param [club_id]		club 的 id
  *
  * @return fans basic info
  */
function getFansBasicInfo($db, $document_root, $club_id)
{
	$fans_info_arr = array();
	 
	$query = sprintf(
				" SELECT season_expectation, satisfaction, core_rate, fans_num, " .
				" latest_option, season_option " .
				" FROM fans " .
				" WHERE club_id='%s' " ,
				$club_id );
				
	$rs = &$db->Execute($query);
	if (!$rs) {		
		$error_message = "Database error.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() < 0){
		$error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() > 0) {
		$fans_info_arr["season_expectation"] = $rs->fields["season_expectation"];
		$fans_info_arr["satisfaction"] = $rs->fields["satisfaction"];
		$fans_info_arr["core_rate"] = $rs->fields["core_rate"];
		$fans_info_arr["fans_num"] = $rs->fields["fans_num"];
		$fans_info_arr["latest_option"] = $rs->fields["latest_option"];
		$fans_info_arr["season_option"] = $rs->fields["season_option"];
	}
	
	return $fans_info_arr;
}

/**
  * get the finance of current_season, last_season ans last_last_season
  *
  * @param [db]				database
  * @param [document_root]	document_root
  * @param [team_id]		team 的 id
  * @param [match_num]		recently match num 
  *
  * @return match info arr
  */
function getRecentlyMatchInfo($db, $document_root, $team_id, $match_num)
{
	$match_info_arr = array();
	
	$query  = " ( ";
	$query .= " SELECT s.time AS match_time, date_format( s.time, '%a %c.%e' ) AS match_date, ";
	$query .= " 'schedule' AS match_type, t1.name AS home_name, t2.name AS away_name, ";
	$query .= " s.home_score, s.away_score, i.gate_receipts, i.audience_num ";
	$query .= " FROM schedule s, team t1, team t2 ";
	$query .= " LEFT JOIN match_info i ON s.id=i.match_id AND i.type='0' ";
	$query .= sprintf(" WHERE s.home_id='%s' AND s.played='1' ", $team_id);
	$query .= " AND s.home_id=t1.team_id AND s.away_id=t2.team_id ";
	$query .= " ) ";
	$query .= " UNION ";
	$query .= " ( ";
	$query .= " SELECT f.time AS match_time, date_format( f.time, '%a %c.%e' ) AS match_date, ";
	$query .= " 'friendly' AS match_type, t1.name AS home_name, t2.name AS away_name, ";
	$query .= " f.home_score, f.away_score, i.gate_receipts, i.audience_num ";
	$query .= " FROM friendly f, team t1, team t2 ";
	$query .= " LEFT JOIN match_info i ON f.id=i.match_id AND i.type='1' ";
	$query .= sprintf(" WHERE f.home_id='%s' AND f.played='1' ", $team_id);
	$query .= " AND f.home_id=t1.team_id AND f.away_id=t2.team_id ";
	$query .= " ) ";
	$query .= " UNION ";
	$query .= " ( ";
	$query .= " SELECT c.time AS match_time, date_format( c.time, '%a %c.%e' ) AS match_date, ";
	$query .= " 'cup match' AS match_type, t1.name AS home_name, t2.name AS away_name, ";
	$query .= " c.home_score, c.away_score, i.gate_receipts, i.audience_num ";
	$query .= " FROM cup_match c, team t1, team t2 ";
	$query .= " LEFT JOIN match_info i ON c.id=i.match_id AND i.type='2' ";
	$query .= sprintf(" WHERE  c.home_id='%s' AND c.played='1' ", $team_id);
	$query .= " AND c.home_id=t1.team_id AND c.away_id=t2.team_id ";
	$query .= " ) ";
	$query .= " ORDER BY match_time DESC ";
	if ($match_num != "all") {
		$query .= sprintf(" LIMIT %s ", $match_num);; 
	}

	$rs = &$db->Execute($query);
	if (!$rs) {		
		$error_message = "Database error.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() < 0 ){
		$error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else for (; !$rs->EOF; $rs->MoveNext()) {
		$match_info = array();
		$match_info["match_date"] = $rs->fields["match_date"];
		$match_info["match_type"] = $rs->fields["match_type"];
		$match_info["home_name"] = $rs->fields["home_name"];
		$match_info["away_name"] = $rs->fields["away_name"];
		$match_info["home_score"] = $rs->fields["home_score"];
		$match_info["away_score"] = $rs->fields["away_score"];
		$match_info["gate_receipts"] = $rs->fields["gate_receipts"];
		$match_info["audience_num"] = $rs->fields["audience_num"];
		
		$match_info_arr[count($match_info_arr)] = $match_info;		
	}
	
	return $match_info_arr;
}




?>

