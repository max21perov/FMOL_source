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
$tpl->loadTemplatefile('finance.tpl.php', true, true); 
		
//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);
$s_primary_club_id = sql_quote($_SESSION['s_primary_club_id']);


//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	

/**
 * get the current season of this division where the team is in 
 * then calculate the last_season ans last_last_season
 */
$current_season = getSeasonOfDivision($db, DOCUMENT_ROOT, $s_primary_team_id);
$last_season = $this_season - 1;
$last_last_season = $this_season - 2;

/**
 * get the finance of current_season, last_season ans last_last_season
 */
$current_finance_arr = getFinanceOfCurrentSeason($db, DOCUMENT_ROOT, $s_primary_club_id);
$last_finance_arr = getFinanceOfPastSeason($db, DOCUMENT_ROOT, $s_primary_club_id, $last_season);
$last_last_finance_arr = getFinanceOfPastSeason($db, DOCUMENT_ROOT, $s_primary_club_id, $last_last_season);

if (count($current_finance_arr) > 0) {	
	// -------- income -------- 
	$current_income_arr = $current_finance_arr["income"];
	$last_income_arr = $last_finance_arr["income"];
	$last_last_income_arr = $last_last_finance_arr["income"];
	$len = count($current_income_arr);
	$index = 0;
	foreach ($current_income_arr as $key=>$value) {
		$tpl->setCurrentBlock("income") ;
		if ($index < ($len -1)) {
			$tpl->setVariable("INCOME_TR_ID", "income_tr_" . $index) ;
			$tpl->setVariable("INCOME_SEPARATOR_ID", "income_separator_" . $index) ;
		}
		$tpl->setVariable("INCOME_ITEM_NAME", $key) ;
		// current season
		$tpl->setVariable("CURRENT_INCOME_ITEM_VALUE", $value) ;
		// last season
		if (count($last_income_arr) <= 0) {
			// if there is not last season income in database
			$tpl->setVariable("LAST_INCOME_ITEM_VALUE", 0) ;
		}
		else {
			$tpl->setVariable("LAST_INCOME_ITEM_VALUE", $last_income_arr[$key]) ;
		}
		// last last season
		if (count($last_last_income_arr) <= 0) {
			// if there is not last last season income in database
			$tpl->setVariable("LAST_LAST_INCOME_ITEM_VALUE", 0) ;
		}
		else {
			$tpl->setVariable("LAST_LAST_INCOME_ITEM_VALUE", $last_last_income_arr[$key]) ;
		}
		$tpl->parseCurrentBlock("income") ;
		
		++ $index;
	}
	
	// -------- expenditure -------- 
	$current_expenditure_arr = $current_finance_arr["expenditure"];
	$last_expenditure_arr = $last_finance_arr["expenditure"];
	$last_last_expenditure_arr = $last_last_finance_arr["expenditure"];
	$len = count($current_expenditure_arr);
	$index = 0;
	foreach ($current_expenditure_arr as $key=>$value) {
		$tpl->setCurrentBlock("expenditure") ;
		if ($index < ($len -1)) {
			$tpl->setVariable("EXPENDITURE_TR_ID", "expenditure_tr_" . $index) ;
			$tpl->setVariable("EXPENDITURE_SEPARATOR_ID", "expenditure_separator_" . $index) ;
		}
		$tpl->setVariable("EXPENDITURE_ITEM_NAME", $key) ;
		// current season
		$tpl->setVariable("CURRENT_EXPENDITURE_ITEM_VALUE", $value) ;
		// last season
		if (count($last_expenditure_arr) <= 0) {
			// if there is not last season expenditure in database
			$tpl->setVariable("LAST_EXPENDITURE_ITEM_VALUE", 0) ;
		}
		else {
			$tpl->setVariable("LAST_EXPENDITURE_ITEM_VALUE", $last_expenditure_arr[$key]) ;
		}
		// last last season
		if (count($last_last_expenditure_arr) <= 0) {
			// if there is not last last season expenditure in database
			$tpl->setVariable("LAST_LAST_EXPENDITURE_ITEM_VALUE", 0) ;
		}
		else {
			$tpl->setVariable("LAST_LAST_EXPENDITURE_ITEM_VALUE", $last_last_expenditure_arr[$key]) ;
		}
		$tpl->parseCurrentBlock("expenditure") ;
		
		++ $index;
	}
	
	// -------- assets -------- 
	$current_assets_arr = $current_finance_arr["assets"];
	$last_assets_arr = $last_finance_arr["assets"];
	$last_last_assets_arr = $last_last_finance_arr["assets"];
	$len = count($current_assets_arr);
	$index = 0;
	foreach ($current_assets_arr as $key=>$value) {
		$tpl->setCurrentBlock("assets") ;
		if ($index < ($len -1)) {
			$tpl->setVariable("ASSETS_TR_ID", "assets_tr_" . $index) ;
			$tpl->setVariable("ASSETS_SEPARATOR_ID", "assets_separator_" . $index) ;
		}
		$tpl->setVariable("ASSETS_ITEM_NAME", $key) ;
		// current season
		$tpl->setVariable("CURRENT_ASSETS_ITEM_VALUE", $value) ;
		// last season
		if (count($last_assets_arr) <= 0) {
			// if there is not last season assets in database
			$tpl->setVariable("LAST_ASSETS_ITEM_VALUE", 0) ;
		}
		else {
			$tpl->setVariable("LAST_ASSETS_ITEM_VALUE", $last_assets_arr[$key]) ;
		}
		// last last season
		if (count($last_last_assets_arr) <= 0) {
			// if there is not last last season assets in database
			$tpl->setVariable("LAST_LAST_ASSETS_ITEM_VALUE", 0) ;
		}
		else {
			$tpl->setVariable("LAST_LAST_ASSETS_ITEM_VALUE", $last_last_assets_arr[$key]) ;
		}
		$tpl->parseCurrentBlock("assets") ;
		
		++ $index;
	}
	
	// -------- balance -------- 
	$current_balance_arr = $current_finance_arr["balance"];
	$last_balance_arr = $last_finance_arr["balance"];
	$last_last_balance_arr = $last_last_finance_arr["balance"];
	$len = count($current_balance_arr);
	$index = 0;
	foreach ($current_balance_arr as $key=>$value) {
		$tpl->setCurrentBlock("balance") ;
		if ($index != 1) {
			$tpl->setVariable("BALANCE_TR_ID", "balance_tr_" . $index) ;
			$tpl->setVariable("BALANCE_SEPARATOR_ID", "balance_separator_" . $index) ;
			
		}
		$tpl->setVariable("BALANCE_ITEM_NAME", $key) ;
		
		if (strpos($key, "rate") != false) {
			// 名字带有rate的显示，要乘以100然后加上%号
			// current season
			$tpl->setVariable("CURRENT_BALANCE_ITEM_VALUE", (floatval($value)*100) . "%") ;
			// last season
			if (count($last_balance_arr) <= 0) {
				// if there is not last season balance in database
				$tpl->setVariable("LAST_BALANCE_ITEM_VALUE", "0%") ;
			}
			else {
				$tpl->setVariable("LAST_BALANCE_ITEM_VALUE", (floatval($last_balance_arr[$key])*100) . "%") ;
			}
			// last last season
			if (count($last_last_balance_arr) <= 0) {
				// if there is not last last season balance in database
				$tpl->setVariable("LAST_LAST_BALANCE_ITEM_VALUE", "0%") ;
			}
			else {
				$tpl->setVariable("LAST_LAST_BALANCE_ITEM_VALUE", (floatval($last_last_balance_arr[$key])*100) . "%") ;
			}
		}
		else {
			// current season
			$tpl->setVariable("CURRENT_BALANCE_ITEM_VALUE", $value) ;
			// last season
			if (count($last_balance_arr) <= 0) {
				// if there is not last season balance in database
				$tpl->setVariable("LAST_BALANCE_ITEM_VALUE", 0) ;
			}
			else {
				$tpl->setVariable("LAST_BALANCE_ITEM_VALUE", $last_balance_arr[$key]) ;
			}
			// last last season
			if (count($last_last_balance_arr) <= 0) {
				// if there is not last last season balance in database
				$tpl->setVariable("LAST_LAST_BALANCE_ITEM_VALUE", 0) ;
			}
			else {
				$tpl->setVariable("LAST_LAST_BALANCE_ITEM_VALUE", $last_last_balance_arr[$key]) ;
			}
		}
		$tpl->parseCurrentBlock("balance") ;
		
		++ $index;
	}
}
/**
 * get the fund: season_begin_fund and current_fund
 */
$fund_arr = getFundOfCurrentSeason($db, DOCUMENT_ROOT, $s_primary_club_id, $current_season);
$season_begin_fund = $fund_arr["season_begin_fund"];
$current_fund = $fund_arr["current_fund"];
$tpl->setVariable("SEASON_BEGIN_FUND", number_format($season_begin_fund, null, null, ",")) ;
$tpl->setVariable("CURRENT_FUND", number_format($current_fund, null, null, ",")) ;

//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------	
// 为了防止页面不显示，所以加了一个空格
$tpl->setVariable("SPACE", " ");
$tpl->show();



//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------	
/**
  * get the current season of this division where the team is in 
  *
  * @param [db]				database
  * @param [document_root]	document_root
  * @param [team_id]		team id
  *
  * @return season
  */
function getSeasonOfDivision($db, $document_root, $team_id)
{ 
	$query = sprintf(
				" SELECT d.season " .
				" FROM division d, team_in_div tid " .
				" WHERE d.div_id=tid.div_id " .
				" AND tid.team_id='%s' " ,
				$team_id);
				
	$rs = &$db->Execute($query);
	if (!$rs) {		
		$error_message = "Database error.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else if ($rs->RecordCount() <= 0 ){
		$error_message = "There is not this right record in the database.";
		require ("$document_root/page/system/error_maker.php");
		exit(0);
	}
	else {
		return intval($rs->fields['season']);
	}
}

/**
  * get the finance of current_season
  *
  * @param [db]				database
  * @param [document_root]	document_root
  * @param [club_id]		club id
  *
  * @return finanace arr
  */
function getFinanceOfCurrentSeason($db, $document_root, $club_id)
{
	$all_finance_arr = array();
	$income_finance_arr = array();
	$expenditure_finance_arr = array();
	$assets_finance_arr = array();
	$balance_finance_arr = array();
	
	$query = sprintf(
				" SELECT " .
				" gate_receipts, season_tickets, prize_money, sponsorship, tv_revenue, " . 
				" players_sold, merchandising, other_income, total_income, " . 
				" facility, wages, fine, players_bought, signing_on_fees, " . 
				" young_training, interest, other_expenditure, total_expenditure, " . 
				" stadium_assets, commercial_installation_assets, training_installation_assets, " . 
				" player_values, intangible_assets, total_assets, " . 
				" fund_balance, fund_balance_rate, assets_change, assets_change_rate " . 
				" FROM finance " . 
				" WHERE club_id='%s' " ,
				$club_id);  
				
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
	else if ($rs->RecordCount() == 0 ){
		return $all_finance_arr;
	}
	else {
		// income
		$income_finance_arr["gate receipts"] = $rs->fields["gate_receipts"];
		$income_finance_arr["season tickets"] = $rs->fields["season_tickets"];
		$income_finance_arr["prize money"] = $rs->fields["prize_money"];
		$income_finance_arr["sponsorship"] = $rs->fields["sponsorship"];
		$income_finance_arr["tv revenue"] = $rs->fields["tv_revenue"];
		$income_finance_arr["players sold"] = $rs->fields["players_sold"];
		$income_finance_arr["merchandising"] = $rs->fields["merchandising"];
		$income_finance_arr["other income"] = $rs->fields["other_income"];
		$income_finance_arr["total income"] = $rs->fields["total_income"];
		
		// $expenditure
		$expenditure_finance_arr["facility"] = $rs->fields["facility"];
		$expenditure_finance_arr["wages"] = $rs->fields["wages"];
		$expenditure_finance_arr["fine"] = $rs->fields["fine"];
		$expenditure_finance_arr["players bought"] = $rs->fields["players_bought"];
		$expenditure_finance_arr["signing on fees"] = $rs->fields["signing_on_fees"];
		$expenditure_finance_arr["young training"] = $rs->fields["young_training"];
		$expenditure_finance_arr["interest"] = $rs->fields["interest"];
		$expenditure_finance_arr["other expenditure"] = $rs->fields["other_expenditure"];
		$expenditure_finance_arr["total expenditure"] = $rs->fields["total_expenditure"];
		
		// assets
		$assets_finance_arr["stadium assets"] = $rs->fields["stadium_assets"];
		$assets_finance_arr["commercial installation assets"] = $rs->fields["commercial_installation_assets"];
		$assets_finance_arr["training installation assets"] = $rs->fields["training_installation_assets"];
		$assets_finance_arr["player values"] = $rs->fields["player_values"];
		$assets_finance_arr["intangible assets"] = $rs->fields["intangible_assets"];
		$assets_finance_arr["total assets"] = $rs->fields["total_assets"];
		
		// balance
		$balance_finance_arr["fund balance"] = $rs->fields["fund_balance"];
		$balance_finance_arr["fund balance rate"] = $rs->fields["fund_balance_rate"];
		$balance_finance_arr["assets change"] = $rs->fields["assets_change"];
		$balance_finance_arr["assets change rate"] = $rs->fields["assets_change_rate"];
		
		$all_finance_arr["income"] = $income_finance_arr;
		$all_finance_arr["expenditure"] = $expenditure_finance_arr;
		$all_finance_arr["assets"] = $assets_finance_arr;
		$all_finance_arr["balance"] = $balance_finance_arr;
		
		return $all_finance_arr;
	}
}

/**
  * get the finance of last_season ans last_last_season
  *
  * @param [db]				database
  * @param [document_root]	document_root
  * @param [club_id]		club id
  * @param [season]			season
  *
  * @return finanace arr
  */
function getFinanceOfPastSeason($db, $document_root, $club_id, $season)
{
	$all_finance_arr = array();
	$income_finance_arr = array();
	$expenditure_finance_arr = array();
	$assets_finance_arr = array();
	$balance_finance_arr = array();
	
	$query = sprintf(
				" SELECT " .
				" gate_receipts, season_tickets, prize_money, sponsorship, tv_revenue, " . 
				" players_sold, merchandising, other_income, total_income, " . 
				" facility, wages, fine, players_bought, signing_on_fees, " . 
				" young_training, interest, other_expenditure, total_expenditure, " . 
				" stadium_assets, commercial_installation_assets, training_installation_assets, " . 
				" player_values, intangible_assets, total_assets, " . 
				" fund_balance, fund_balance_rate, assets_change, assets_change_rate " . 
				" FROM finance_history " . 
				" WHERE club_id='%s' " .   
				" AND season='%s' " ,
				$club_id, $season);  
				
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
	else if ($rs->RecordCount() == 0 ){
		return $all_finance_arr;
	}
	else {
		// income
		$income_finance_arr["gate receipts"] = $rs->fields["gate_receipts"];
		$income_finance_arr["season tickets"] = $rs->fields["season_tickets"];
		$income_finance_arr["prize money"] = $rs->fields["prize_money"];
		$income_finance_arr["sponsorship"] = $rs->fields["sponsorship"];
		$income_finance_arr["tv revenue"] = $rs->fields["tv_revenue"];
		$income_finance_arr["players sold"] = $rs->fields["players_sold"];
		$income_finance_arr["merchandising"] = $rs->fields["merchandising"];
		$income_finance_arr["other income"] = $rs->fields["other_income"];
		$income_finance_arr["total income"] = $rs->fields["total_income"];
		
		// $expenditure
		$expenditure_finance_arr["facility"] = $rs->fields["facility"];
		$expenditure_finance_arr["wages"] = $rs->fields["wages"];
		$expenditure_finance_arr["fine"] = $rs->fields["fine"];
		$expenditure_finance_arr["players bought"] = $rs->fields["players_bought"];
		$expenditure_finance_arr["signing on fees"] = $rs->fields["signing_on_fees"];
		$expenditure_finance_arr["young training"] = $rs->fields["young_training"];
		$expenditure_finance_arr["interest"] = $rs->fields["interest"];
		$expenditure_finance_arr["other expenditure"] = $rs->fields["other_expenditure"];
		$expenditure_finance_arr["total expenditure"] = $rs->fields["total_expenditure"];
		
		// assets
		$assets_finance_arr["stadium assets"] = $rs->fields["stadium_assets"];
		$assets_finance_arr["commercial installation assets"] = $rs->fields["commercial_installation_assets"];
		$assets_finance_arr["training installation assets"] = $rs->fields["training_installation_assets"];
		$assets_finance_arr["player values"] = $rs->fields["player_values"];
		$assets_finance_arr["intangible assets"] = $rs->fields["intangible_assets"];
		$assets_finance_arr["total assets"] = $rs->fields["total_assets"];
		
		// balance
		$balance_finance_arr["fund balance"] = $rs->fields["fund_balance"];
		$balance_finance_arr["fund balance rate"] = $rs->fields["fund_balance_rate"];
		$balance_finance_arr["assets change"] = $rs->fields["assets_change"];
		$balance_finance_arr["assets change rate"] = $rs->fields["assets_change_rate"];
		
		$all_finance_arr["income"] = $income_finance_arr;
		$all_finance_arr["expenditure"] = $expenditure_finance_arr;
		$all_finance_arr["assets"] = $assets_finance_arr;
		$all_finance_arr["balance"] = $balance_finance_arr;
		
		return $all_finance_arr;
	}
}

/**
  * get the fund from dababase
  * the fund include "season_begin_fund" and "current_fund"
  *
  * @param [db]				database
  * @param [document_root]	document_root
  * @param [club_id]		club id
  *
  * @return finanace arr
  */
function getFundOfCurrentSeason($db, $document_root, $club_id)
{
	$fund_arr = array(); 
	
	$query = sprintf(
				" SELECT season_begin_fund, current_fund " . 
				" FROM finance " . 
				" WHERE club_id='%s' ",
				$club_id); 
				
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
	else if ($rs->RecordCount() > 0 ){
		$fund_arr["season_begin_fund"] = $rs->fields["season_begin_fund"];
		$fund_arr["current_fund"] = $rs->fields["current_fund"];
	}
	
	return $fund_arr;
}



?>

