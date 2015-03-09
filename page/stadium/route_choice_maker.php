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
$tpl->loadTemplatefile('route_choice.tpl.php', true, true); 
		
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


/**
 * get current scale flag of stadium
 */
$cur_scale_flag = getStadiumScaleFlag($db, DOCUMENT_ROOT, $s_primary_club_id);
$tpl->setVariable("CURRENT_SCALE_FLAG", $cur_scale_flag);

/**
 * get the alter stadium route choice 
 */
$route_choice_arr = getRouteChoice($db, DOCUMENT_ROOT);
$len = count($route_choice_arr);  
$pre_scale = "";
for ($i=0; $i<$len; ++$i) {
	$route_choice = $route_choice_arr[$i];
		
	$finish_season = $season_arr[$route_choice["finish_season"]];
	$finish_period = $period_arr[$route_choice["finish_period"]];
	
	$tpl->setCurrentBlock("alter_stadium");
	if ($pre_scale != $route_choice["scale"]) {
		$pre_scale = $route_choice["scale"];
		$tpl->setVariable("SCALE", $route_choice["scale"]);
	}
	$tpl->setVariable("SCALE_FLAG", $route_choice["scale_flag"]);
	if ($cur_scale_flag == $route_choice["scale_flag"]) {
		$tpl->setVariable("SCALE_FLAG_CLASS", "SelfTeamText");
	}
	else {
		$tpl->setVariable("SCALE_FLAG_CLASS", "OtherTeamText");
	}
	$tpl->setVariable("ALTER_TYPE", $route_choice["alter_type"]);
	$tpl->setVariable("ROUTE_CHOICE", $route_choice["route_choice"]);
	$tpl->setVariable("FINISH_TIME", $finish_season . $finish_period);
	$tpl->setVariable("ALTER_COST", number_format($route_choice["alter_cost"], null, null, ","));
	$tpl->setVariable("CAPACITY", number_format($route_choice["capacity"], null, null, ","));
	$tpl->setVariable("NEXT_SEASON_FUND_IMPACT", (floatval($route_choice["next_season_fund_impact"])*100) . "%");
	$tpl->setVariable("NOTE", $route_choice["note"]);
	
	$tpl->parseCurrentBlock("alter_stadium");
}

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
  * @return current scale flag
  */
function getStadiumScaleFlag($db, $document_root, $club_id)
{
	$cur_scale_flag = "";
	
	$query = sprintf(
				" SELECT scale_flag " . 
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
		$cur_scale_flag = $rs->fields["scale_flag"];
	}
	
	return $cur_scale_flag;
}

/**
  * get the alter stadium route choice 
  *
  * @param [db]				database
  * @param [document_root]	document_root
  *
  * @return fans basic info
  */
function getRouteChoice($db, $document_root)
{
	$route_choice_arr = array();
	$route_choice = array();
	
	$query = sprintf(
				" SELECT scale, scale_flag, alter_type, route_choice, finish_season, " . 
				" finish_period, alter_cost, capacity, next_season_fund_impact, " . 
				" note " . 
				" FROM alter_stadium " . 
				" ORDER BY scale_flag ASC ");  

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
		$route_choice["scale"] = $rs->fields["scale"];
		$route_choice["scale_flag"] = $rs->fields["scale_flag"];
		$route_choice["alter_type"] = $rs->fields["alter_type"];
		$route_choice["route_choice"] = $rs->fields["route_choice"];
		$route_choice["finish_season"] = $rs->fields["finish_season"];
		$route_choice["finish_period"] = $rs->fields["finish_period"];
		$route_choice["alter_cost"] = $rs->fields["alter_cost"];
		$route_choice["capacity"] = $rs->fields["capacity"];
		$route_choice["next_season_fund_impact"] = $rs->fields["next_season_fund_impact"];
		$route_choice["note"] = $rs->fields["note"];
		
		$route_choice_arr[count($route_choice_arr)] = $route_choice;
	}
	
	return $route_choice_arr;
}





?>

