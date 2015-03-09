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
$tpl->loadTemplatefile('stadium.tpl.php', true, true); 
		
//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);
$s_primary_club_id = sql_quote($_SESSION['s_primary_club_id']);


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
// 全局变量
$season_arr = array("0" => iconv("GBK", "UTF-8", "本季"), "1" => iconv("GBK", "UTF-8", "下季"), "2" => iconv("GBK", "UTF-8", "下下季"));
$period_arr = array("0" => iconv("GBK", "UTF-8", "初"), "1" => iconv("GBK", "UTF-8", "中"), "2" => iconv("GBK", "UTF-8", "末"));
$board_attitude_arr = array(
	"A" => "We should wait until the end of this season, and discuss this by the \"average attandence rate\".", 
	"B" => "We still have some place. We think we can solve the problem by increasing the \"average attandence rate\" or \"seater rate\".", 
	"C" => "Maybe it is the time when we should have a bigger stadium.",
	"D" => "Hoping the completion of the stadium, can bring more profits."
);

/**
 * get the stadium basic information
 */
$stadim_info_arr = getStadiumBasicInfo($db, DOCUMENT_ROOT, $s_primary_club_id);
$tpl->setVariable("PRIMARY_STADIUM_ID", $stadim_info_arr["primary_stadium_id"]);
$tpl->setVariable("STADIUM_NAME", $stadim_info_arr["name"]);
$tpl->setVariable("STADIUM_CAPACITY", number_format($stadim_info_arr["capacity"], null, null, ","));
$tpl->setVariable("SEATER", number_format($stadim_info_arr["seater"], null, null, ","));
$tpl->setVariable("CURRENT_SCALE_FLAG", $stadim_info_arr["scale_flag"]);
$tpl->setVariable("BOARD_ATTITUDE", $stadim_info_arr["board_attitude"]);
$tpl->setVariable("BOARD_ATTITUDE_STR", $board_attitude_arr[$stadim_info_arr["board_attitude"]]);
if ($stadim_info_arr["board_attitude"] == "A" || $stadim_info_arr["board_attitude"] == "B") {
	$tpl->setVariable("COST_PRIVILEGE_POINT_DISPLAY", "");
}
else if ($stadim_info_arr["board_attitude"] == "C") {
	$tpl->setVariable("COST_PRIVILEGE_POINT_DISPLAY", "none");
}
else if ($stadim_info_arr["board_attitude"] == "D") {
	// 显示正在进行的工程的进度  
	$tpl->setVariable("ASK_TO_EXAPND_DISABLED", "disabled");
	$expand_stadium_project = getExpandStadiumProject($db, DOCUMENT_ROOT, $stadim_info_arr["primary_stadium_id"]);
	if (count($expand_stadium_project) > 0) {
		$cur_season = getCurrentSeason($db, DOCUMENT_ROOT, $team_id);
		
		$season_index = intval($expand_stadium_project["finish_season"]) - 
					(intval($cur_season) - intval($expand_stadium_project["set_expand_season"]));
		$project_description = "The project will accomplish in " . 
					$season_arr[$season_index] .
					" " .
					$period_arr[$expand_stadium_project["finish_period"]];
		
		$tpl->setVariable("EXPAND_STADIUM_PROGRESS", $project_description);
	}
}
// seater rate
$seater_rate = "0%";
if (floatval($stadim_info_arr["capacity"]) != 0) {
	$seater_rate = floatval($stadim_info_arr["seater"]) / floatval($stadim_info_arr["capacity"]);
	$seater_rate = number_format($seater_rate, 4);  // 4为商的小数点位数
	$seater_rate = ($seater_rate * 100) . "%";
}
$tpl->setVariable("SEATER_RATE", $seater_rate);
$tpl->setVariable("AVERAGE_ATTENDANCE_RATE", ($stadim_info_arr["average_attendance_rate"] * 100) . "%");


/**
 * whether the club have start the "add seater" project
 */
$add_seater_project = getAddSeaterProject($db, DOCUMENT_ROOT, $stadim_info_arr["primary_stadium_id"]);
if (count($add_seater_project) > 0) {
	$tpl->setVariable("ADD_SEATER_DISABLED", "disabled");
	$project_description = "The project will accomplish in " . 
			$add_seater_project["remaining_time"] . 
			" . At that time, the seater of the stadium will increase by " . 
			$add_seater_project["add_seater_num"];
	$tpl->setVariable("ADD_SEATER_PROGRESS", $project_description);
}

/**
 * get the alter stadium route choice
 */
$route_choice_str = getPresentRouteChoiceStr($db, DOCUMENT_ROOT);

$route_choice_arr = getPresentRouteChoiceArr($db, DOCUMENT_ROOT, $route_choice_str);
$len = count($route_choice_arr);  
for ($i=0; $i<$len; ++$i) {
	$route_choice = $route_choice_arr[$i];
		
	$finish_season = $season_arr[$route_choice["finish_season"]];
	$finish_period = $period_arr[$route_choice["finish_period"]];
	
	$tpl->setCurrentBlock("alter_stadium");
	
	$tpl->setVariable("OPTION_VALUE", $route_choice["scale_flag"]);
	$tpl->setVariable("OPTION_TEXT", "option " . ($i+1));
	if ($i == 0) {
		$tpl->setVariable("IS_CHECKED", "checked");
	}
	
	$tpl->setVariable("SCALE_FLAG", $route_choice["scale_flag"]);
	$tpl->setVariable("ALTER_TYPE", $route_choice["alter_type"]);
	$tpl->setVariable("FINISH_TIME", $finish_season . $finish_period);
	$tpl->setVariable("ALTER_COST", number_format($route_choice["alter_cost"], null, null, ","));
	$tpl->setVariable("CAPACITY", number_format($route_choice["capacity"], null, null, ","));
	$tpl->setVariable("NEXT_SEASON_FUND_IMPACT", (floatval($route_choice["next_season_fund_impact"])*100) . "%");
	
	$tpl->parseCurrentBlock("alter_stadium");
}

// 给add_seater_option_str赋值
$add_seater_option_str = "1000:30000:2|2000:60000:4|3000:150000:10";
$tpl->setVariable("ADD_SEATER_OPTION_STR", $add_seater_option_str);

// 在A和B态度，玩家强迫通过需要花费特权点数
$cost_privilege_point_num = 5;
$tpl->setVariable("COST_PRIVILEGE_POINT_NUM", $cost_privilege_point_num);

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
  * get the board basic information
  *
  * @param [db]				database
  * @param [document_root]	document_root
  * @param [club_id]		club 的 id
  *
  * @return fans basic info
  */
function getStadiumBasicInfo($db, $document_root, $club_id)
{
	$stadim_info_arr = array();
	
	$query = sprintf(
				" SELECT id, name, capacity, seater, average_attendance_rate, scale_flag, " . 
				" board_attitude " . 
				" FROM stadium " . 
				" WHERE club_id='%s' " ,
				$club_id);
				  
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
		$stadim_info_arr["primary_stadium_id"] = $rs->fields["id"];
		$stadim_info_arr["name"] = $rs->fields["name"];
		$stadim_info_arr["capacity"] = $rs->fields["capacity"];
		$stadim_info_arr["seater"] = $rs->fields["seater"];
		$stadim_info_arr["average_attendance_rate"] = $rs->fields["average_attendance_rate"];
		$stadim_info_arr["scale_flag"] = $rs->fields["scale_flag"]; 
		$stadim_info_arr["board_attitude"] = $rs->fields["board_attitude"]; 
	}
	
	return $stadim_info_arr;
}


/**
  * get the alter stadium route choice by the scale_flag of this stadium
  *
  * @param [db]				database
  * @param [document_root]	document_root
  *
  * @return present route choice str
  */
function getPresentRouteChoiceStr($db, $document_root)
{
	$route_choice_str = "";
	
	$query = sprintf(
				" SELECT a.route_choice " . 
				" FROM alter_stadium a, stadium s " . 
				" WHERE a.scale_flag=s.scale_flag " );
	
	$rs = &$db->Execute($query);
	if (!$rs) {		
		$error_message = "Database error2." . $db->ErrorMsg();
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() < 0){
		$error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() > 0) {
		$route_choice_str = $rs->fields["route_choice"];
	}
	
	return $route_choice_str;
}

/**
  * get the alter stadium route choices by the route_choice of this stadium
  *
  * @param [db]					database
  * @param [document_root]		document_root
  * @param [route_choice_str]	route_choice_str
  *
  * @return present route choice
  */
function getPresentRouteChoiceArr($db, $document_root, $route_choice_str)
{
	$route_choice_arr = array();
	$route_choice = array();
	
	$arr = explode(",", $route_choice_str); 
	$len = count($arr);
	if ($len == 0) {
		return $route_choice_arr;
	}
	
	$query  = " SELECT a.scale_flag, a.alter_type, a.finish_season, ";
	$query .= " a.finish_period, a.alter_cost, a.capacity, a.next_season_fund_impact ";
	$query .= " FROM alter_stadium a ";
	$query .= " WHERE ";
	for ($i=0; $i<$len; ++$i) {
		if ($i != 0) {
			$query .= " OR ";
		}
		$value = $arr[$i];
		$query .= sprintf(" a.scale_flag='%s' ", $value);
	}
	$query .= " ORDER BY scale_flag ASC ";  
	
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
	else for (; !$rs->EOF; $rs->MoveNext()) {
		$route_choice["alter_type"] = $rs->fields["alter_type"];
		$route_choice["scale_flag"] = $rs->fields["scale_flag"];
		$route_choice["finish_season"] = $rs->fields["finish_season"];
		$route_choice["finish_period"] = $rs->fields["finish_period"];
		$route_choice["alter_cost"] = $rs->fields["alter_cost"];
		$route_choice["capacity"] = $rs->fields["capacity"];
		$route_choice["next_season_fund_impact"] = $rs->fields["next_season_fund_impact"];
		
		$route_choice_arr[count($route_choice_arr)] = $route_choice;
	}
	
	return $route_choice_arr;
}


/**
  * get the "add seater" project if the project is going on
  *
  * @param [db]					database
  * @param [document_root]		document_root
  * @param [p_stadium_id]		stadium的主键ID
  *
  * @return add seater project info
  */
function getAddSeaterProject($db, $document_root, $p_stadium_id)
{
	$add_seater_project = array();
	
	$query = sprintf(
				" SELECT remaining_time, add_seater_num  " . 
				" FROM alter_stadium_buffer " . 
				" WHERE stadium_id='%s' AND project_type='0' " ,
				$p_stadium_id); 
	
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
		$add_seater_project["remaining_time"] = $rs->fields["remaining_time"];
		$add_seater_project["add_seater_num"] = $rs->fields["add_seater_num"];
	}
	
	return $add_seater_project;
}


/**
  * get the "add seater" project if the project is going on
  *
  * @param [db]					database
  * @param [document_root]		document_root
  * @param [p_stadium_id]		stadium的主键ID
  *
  * @return expand stadium project info
  */
function getExpandStadiumProject($db, $document_root, $p_stadium_id)
{
	
	$expand_stadium_project = array();
	
	$query = sprintf(
				" SELECT b.set_expand_season, r.finish_season, r.finish_period  " . 
				" FROM alter_stadium_buffer b, alter_stadium r " . 
				" WHERE stadium_id='%s' AND project_type='1' " . 
				" AND b.expand_route=r.scale_flag " ,
				$p_stadium_id);
	
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
		$expand_stadium_project["set_expand_season"] = $rs->fields["set_expand_season"];
		$expand_stadium_project["finish_season"] = $rs->fields["finish_season"];
		$expand_stadium_project["finish_period"] = $rs->fields["finish_period"];
	}
	
	return $expand_stadium_project;

}

/**
	 * 取得当前的season
	 *
	 * @param [db]					database
  	 * @param [document_root]		document_root
	 * @param [team_id]				team 的主键
	 *
	 * @return return current season
	 */	
function getCurrentSeason($db, $document_root, $team_id)
{
	$season = "0";
	
	// get the current season
	$query = sprintf(
				" SELECT d.season " . 
				" FROM division d, team_in_div tid  " . 
				" WHERE d.div_id=tid.div_id AND tid.team_id='%s' " ,
				$team_id);

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
	else if ($rs->RecordCount() > 0 ) {
		$season = $rs->fields["season"];
	}	
	
	return $season;
}

?>
