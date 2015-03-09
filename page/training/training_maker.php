<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//----------------------------------------------------------------------------	
// create a new template
//----------------------------------------------------------------------------
$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("training.tpl.php", true, true); 

//----------------------------------------------------------------------------	
// get the data from GET
//----------------------------------------------------------------------------
$team_id = sql_quote($_GET["team_id"]);

//----------------------------------------------------------------------------	
// get the data from database and show them into the template, 
// then show the template
//----------------------------------------------------------------------------	
$item_num = 0;
$training_items = array();

// ----- begin of 初始化训练界面的左手部分的下拉框 -----


$personal_trainings = array();


/**
 * get the training content
 */
$training_contents = getTrainingContent($db);


/**
 * get the training coach
 */
$training_coaches = getTrainingCoach($db, $team_id);

/**
 * get the team training
 */
$query = sprintf(
			" SELECT id, training_content_id, first_coach_id, second_coach_id " . 
			" FROM team_training " . 
			" WHERE team_id='%s' ",
			$team_id); 
$rs = &$db->Execute($query);
if (!$rs) {
    print "Database Error."; // Displays the error message if no results could be returned
	exit(0);
}
else { 
    if ($rs->RecordCount() > 0) {  
    	$training_content_id = $rs->fields['training_content_id'];
    	$first_coach_id = $rs->fields['first_coach_id'];
    	$second_coach_id = $rs->fields['second_coach_id'];
    	    	
    	$tpl->setVariable("PK_OF_TEAM_TRAINING", $rs->fields['id']);
    	
    	$item_index = 0;
    	$block_name = "content_select_team";
    	produceContentSelect($tpl, $training_contents, $training_content_id, $item_index, $block_name);
    	
    	$block_name = "coach_select_first_team";
    	produceCoachSelect($tpl, $training_coaches, $first_coach_id, $block_name);
    	$block_name = "coach_select_second_team";
    	produceCoachSelect($tpl, $training_coaches, $second_coach_id, $block_name);
    	
    }
}

/**
 * get the personal training
 */
$query = sprintf(
			" SELECT id, personal_training_id, training_content_id, first_coach_id, second_coach_id " . 
			" FROM personal_training " . 
			" WHERE team_id='%s' " . 
			" ORDER BY personal_training_id ASC " ,
			$team_id); 
$rs = &$db->Execute($query);
if (!$rs) {
    print "Database Error."; // Displays the error message if no results could be returned
	exit(0);
}
else { 
    for (; !$rs->EOF; $rs->MoveNext()) {  
    	$personal_training_id = $rs->fields['personal_training_id'];
    	$training_content_id = $rs->fields['training_content_id'];
    	$first_coach_id = $rs->fields['first_coach_id'];
    	$second_coach_id = $rs->fields['second_coach_id'];
    	$item_index = str_replace("item ", "", $personal_training_id); 
    	
    	$tpl->setVariable("PK_OF_ITEM_" . $item_index, $rs->fields['id']);
    	
    	$block_name = "content_select_" . $item_index;
    	produceContentSelect($tpl, $training_contents, $training_content_id, $item_index, $block_name);
    	
    	$block_name = "coach_select_first_" . $item_index;
    	produceCoachSelect($tpl, $training_coaches, $first_coach_id, $block_name);
    	$block_name = "coach_select_second_" . $item_index;
    	produceCoachSelect($tpl, $training_coaches, $second_coach_id, $block_name);
    	
    	$personal_trainings[$rs->fields['id']] = $personal_training_id;
    }
}
// ----- end of 初始化训练界面的左手部分的下拉框 ------


/**
 * script code
 */
$script_code = "
	limit_min_x = 1;
	limit_max_x = 1 + 350 + 153; // 280 + 153;
	limit_min_y = 2;
	limit_max_y = 2 + 450 + 85;
";

/**
 * player value
 */
$player_id_list = ''; 

$left_coordinate = 355; // 285;
$ic_left_coordinate = $left_coordinate + 2;
$index_left_coordinate =  $ic_left_coordinate + 18;
$player_name_left_coordinate = $index_left_coordinate + 35;
$o_top_coordinate  = 10 + 16;
$top_coordinate  = 10 + 16;
$index_width = 30;
$player_name_width = 120;
$info_left_coordinate = 50;
$info_top_coordinate = 100;
$img_width = 22;
$img_height = 22;

for ($i=1; $i<=5; ++$i) {
	$player_property_script .= "ppTable_" . $i . " = new Array();
	";
}
$player_property_script .= "ppTable_" . "free" . " = new Array();
";
	

/**
 * show the training items
 */
$query = sprintf(
			" SELECT p.player_id, pt.personal_training_id, p.custom_given_name AS given_name, p.custom_family_name AS family_name, " . 
			" p.cloth_number " . 
			" FROM player p " . 
			" LEFT JOIN player_training pt ON p.player_id=pt.player_id " . 
			" WHERE p.team_id='%s' " . 
			" ORDER BY pt.personal_training_id, p.cloth_number ASC " ,
			$team_id);

$rs = &$db->Execute($query);

if (!$rs) {
    print "Database Error."; // Displays the error message if no results could be returned
	exit(0);
}
else {
	$item_num = 1;
	$players_number = $rs->RecordCount();
    for (; !$rs->EOF; $rs->MoveNext(), $item_num+=1, $top_coordinate += 26) {
		$player_id = $rs->fields['player_id'];
		$primary_personal_training_id = $rs->fields['personal_training_id'];
    	//produceItemSelect($tpl, $training_items, $team_training_id);
    	
		// form the "$player_id_list"
		if ($item_num != 1) {
			$player_id_list .= ',';
		}
		$player_id_list .= $rs->fields['player_id'];
		// form the add_drag and put it into the "$script_code"
		$script_code .= "add_drag('p" . $rs->fields['player_id'] . "');
        " ; 
		$script_code .= "add_drag('player_div_" . $rs->fields['player_id'] . "');
        " ; 
		
		if ($primary_personal_training_id == "") {
			$player_property_script .= "ppTable_". "free". "[ppTable_" . 
		        "free" . ".length] = " . $player_id . ";
		    ";
		}
		else { 
			$personal_training_id = $personal_trainings[$primary_personal_training_id];
			$personal_training_id = ereg_replace("item ", "", $personal_training_id);
			$player_property_script .= "ppTable_". $personal_training_id. "[ppTable_" . 
		        $personal_training_id . ".length] = " . $player_id . ";
		    ";
		}
			
		// produce the training_player 	
		$tpl->setCurrentBlock("training_player") ;
		
		$tpl->setVariable("TRAINING_ITEM_TR_CLASS", 'gSGRowEven_input') ;
		if (intval($rs->fields['cloth_number']) < 10) { 
			$tpl->setVariable("INDEX", "&nbsp;" . $rs->fields['cloth_number']);
		}
		else {
			$tpl->setVariable("INDEX", $rs->fields['cloth_number']);
		}
		$tpl->setVariable("PLAYER_ID", $player_id);
		$tpl->setVariable("LEFT_COORDINATE", $left_coordinate);
		$tpl->setVariable("INDEX_LEFT_COORDINATE", $index_left_coordinate);
		$tpl->setVariable("IC_LEFT_COORDINATE", $ic_left_coordinate);
		$tpl->setVariable("PLAYER_NAME_LEFT_COORDINATE", $player_name_left_coordinate);
		$tpl->setVariable("TOP_COORDINATE", $top_coordinate);
		$tpl->setVariable("INDEX_WIDTH_VALUE", $index_width);
		$tpl->setVariable("PLAYER_NAME_WIDTH_VALUE", $player_name_width);
		$tpl->setVariable("WIDTH_VALUE", $img_width);
		$tpl->setVariable("HEIGHT_VALUE", $img_height);
		$tpl->setVariable("IMG_NAME", 'img.gif');
		
		if ($rs->fields['given_name'] == "") {
			$tpl->setVariable("PLAYER_NAME", $rs->fields['family_name']);
		}
		else {
			$tpl->setVariable("PLAYER_NAME", substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name']);
		}
		
		$tpl->parseCurrentBlock("training_player") ;
    }
}		

// "ft" is the top of the display ground
$script_code .= $player_property_script;
$script_code .= "
  //init_training(ft, fl, fx, fy, gw,  gh,  sw, sh, pw, ph, iw,  ih, md, mm, mf, with_count, top_coordinate, left_coordinate, place_left_coordinate, ic_left_coordinate, player_name_left_coordinate)
    init_training(97,  2,  10, 11, 350, 450, 22, 21, 30, 30, 182, 342, 2,  2, 1,  true, $o_top_coordinate, $left_coordinate, $index_left_coordinate, $ic_left_coordinate, $player_name_left_coordinate); 
";
$tpl->setVariable("SCRIPT_CODE", $script_code);
$tpl->setVariable("PLAYERS_VALUE", $player_id_list);
$tpl->setVariable("PLAYERS_NUMBER", $players_number);



//----------------------------------------------------------------------------	
// print the output
//----------------------------------------------------------------------------
// add a space in the page to show the page any time 
$tpl->setVariable("SPACE", " ");

$tpl->show();


//----------------------------------------------------------------------------	
// 自定义函数部分
//----------------------------------------------------------------------------
/**
 * get the training content
 *
 * @param [db]		db
 *
 * @return  $training_contents
 */	
function getTrainingContent($db)
{
	$training_contents = array();
	
	$query = sprintf(
				" SELECT id, content_name, content_db_name, content_type " . 
				" FROM training_content " . 
				" ORDER BY id " );
	$rs = &$db->Execute($query);
	if (!$rs) {
	    print "Database Error."; // Displays the error message if no results could be returned
		exit(0);
	}
	else { 
	    for (; !$rs->EOF; $rs->MoveNext()) {
	    	$content_item = array();
	    	$content_item["training_content_id"] = $rs->fields['id'];
	    	$content_item["content_name"] = $rs->fields['content_name'];
	    	$content_item["content_db_name"] = $rs->fields['content_db_name'];
	    	$content_item["content_type"] = $rs->fields['content_type'];
			$training_contents[count($training_contents)] = $content_item;
	    }
	}
	
	return $training_contents;
}

/**
 * get the training coach
 *
 * @param [db]		db
 *
 * @return  $training_coaches
 */	
function getTrainingCoach($db, $team_id)
{
	$training_coaches = array();
	// the first coach
	$coach_item = array();
	$coach_item["coach_id"] = "NULL"; //-1;
	$coach_item["coach_name"] = "none";
	$training_coaches[count($training_coaches)] = $coach_item;
	
	
	$query = sprintf(
			" SELECT coach_id, custom_given_name AS given_name, custom_family_name AS family_name " . 
			" FROM coach " . 
			" WHERE team_id='%s' " ,
			$team_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
	    print "Database Error."; // Displays the error message if no results could be returned
		exit(0);
	}
	else {
	    for (; !$rs->EOF; $rs->MoveNext()) { 
	    	$coach_item = array();
	    	$coach_item["coach_id"] = $rs->fields['coach_id'];
	    	if ($rs->fields['given_name'] == "") {
				$coach_name = $rs->fields['family_name'];
			}
			else {
				$coach_name = substr($rs->fields['given_name'],0,1). "." . $rs->fields['family_name'];
			}
	    	$coach_item["coach_name"] = $coach_name;
			$training_coaches[count($training_coaches)] = $coach_item;
	    }
	}

	return $training_coaches;
}
	 
/**
	 * 构造 content_select
	 *
	 * @param [tpl]						php 模板变量
	 * @param [training_contents]		training_contents
	 * @param [training_content_id]		training_content_id
	 * @param [item_num]		item_num
	 *
	 * @return  no
	 */	
function produceContentSelect($tpl, $training_contents, $training_content_id, $item_index, $block_name)
{ 
	// 首先显示在阵型中的位置
	foreach ($training_contents as $key => $value) {  
		$content_item = $value;
		if ($item_index == 0) {
			// Team Proficiency
			if ($content_item["content_type"] != "auto" && $content_item["content_type"] != "Team Proficiency")
				continue;
		}
		else if ($item_index == 5) {
			// GK
			if ($content_item["content_type"] != "auto" && $content_item["content_type"] != "GK")
				continue;
		}
		else {
			// not GK, not Team Proficiency
			if ($content_item["content_type"] == "GK" || $content_item["content_type"] == "Team Proficiency")
				continue;
		}
		
	
		$tpl->setCurrentBlock($block_name) ;
		$tpl->setVariable("CONTENT_OPTION_VALUE", $content_item["training_content_id"]);
		$tpl->setVariable("CONTENT_OPTION_TEXT", $content_item["content_name"]);
		if ($content_item["training_content_id"] == $training_content_id){
			$tpl->setVariable("CONTENT_SELECTED", "selected");
		}
		
		$tpl->parseCurrentBlock($block_name) ;
	}
}

/**
	 * 构造 coach_select
	 *
	 * @param [tpl]						php 模板变量
	 * @param [training_contents]		training_contents
	 * @param [training_content_id]		training_content_id
	 * @param [item_num]		item_num
	 *
	 * @return  no
	 */	
function produceCoachSelect($tpl, $training_coaches, $coach_id, $block_name)
{
	// 首先显示在阵型中的位置
	foreach ($training_coaches as $key => $value) {
		$content_item = $value;

		$tpl->setCurrentBlock($block_name) ;
		$tpl->setVariable("COACH_OPTION_VALUE", $content_item["coach_id"]);
		$tpl->setVariable("COACH_OPTION_TEXT", $content_item["coach_name"]);
		if ($content_item["coach_id"] == $coach_id){
			$tpl->setVariable("COACH_SELECTED", "selected");
		}
		
		$tpl->parseCurrentBlock($block_name) ;
	}
}


?>

