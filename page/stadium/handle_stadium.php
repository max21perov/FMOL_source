<?php

session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/common.inc.php");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");


if($_SERVER["REQUEST_METHOD"] != "POST")    //check the page is request by method "POST"
{	
	// go back to the page "stadium.php"
	goToPageInTime(0, "/fmol/page/stadium/stadium.php");
}

$myaction = sql_quote($_GET["myaction"]);
if ("changeStadiumName" == $myaction) {
	performChangeStadiumName($db, DOCUMENT_ROOT);
}
else if ("expandStadiumScale" == $myaction) {
	performExpandStadiumScale($db, DOCUMENT_ROOT);
}
else if ("addSeater" == $myaction) {
	performAddSeater($db, DOCUMENT_ROOT);
}
else {
	goToPageInTime(0, "/fmol/page/stadium/stadium.php");
}

//----------------------------------------------------------------------------	
// ʹ�õ���(perform)����
//----------------------------------------------------------------------------
	/**
	 * �����޸��򳡵����Ƶ��¼�
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performChangeStadiumName($db, $document_root)
{
	$stadium_id = sql_quote($_POST["primary_stadium_id"]);
	$stadium_name = sql_quote($_POST["stadium_name"]);
	
	// �޸��򳡵�����
	$returnValue = updateStadiumName($db, $stadium_id, $stadium_name);
	if ($returnValue != "0") {
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}

	$error_message = "Operate Success.";
	require ("$document_root/page/system/error.php"); 
	
	// ��󣬷��� stadium.php ҳ��
	goToPageInTime(2, "/fmol/page/stadium/stadium.php");	
	
	return true;
}

	/**
	 * �����򳡵Ĺ�ģ
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performExpandStadiumScale($db, $document_root)
{
	$stadium_id = sql_quote($_POST["primary_stadium_id"]);
	$expand_route = sql_quote($_POST["expand_route_radio"]);
	$board_attitude = sql_quote($_POST["board_attitude"]);
	$club_id = sql_quote($_SESSION["s_primary_club_id"]);
	$team_id = sql_quote($_SESSION["s_primary_team_id"]);
	$start_time = date("Y-m-d H:i:s");
	
	// �������¼�����򳡵�route������¼��$radio_expand_route�У�
	// ��������5�������
	// ������ɹ��Ժ�ҳ���ϵ�ask to expand��ťΪ��ɫ��
	
	// ����ʼ
	$db->BeginTrans();
	
	if ($board_attitude == "A" || $board_attitude == "B") {
		// ��1����A��B̬�ȣ������Ҫ����5����Ȩǿ��ͨ��
		$cost_privilege_point_num = sql_quote($_POST["cost_privilege_point_num"]);
		$returnValue = reducePrivelegePoint($db, $club_id, $cost_privilege_point_num);
		if ($returnValue != "0") {
			$db->RollbackTrans(); 
			$error_message = $returnValue;
			require ("$document_root/page/system/error.php");
			
			goBackInTime(3500, -1); 
		}
	}
	else if ($board_attitude == "C") {
		// ��A��B̬�ȣ���Ҳ���Ҫ������Ȩ		
	}
	
	// ��2����ѡ�������·�߷��롰��ʱ�¼�����
	$project_type = "1";    // 1 ��ʾ������
	// ȡ���������������season
	$set_expand_season = "0";
	$returnValue = getCurrentSeason($db, $team_id, $set_expand_season);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	$returnValue = insertIntoAlterStadiumBuffer2($db, $stadium_id, $start_time, $project_type, $set_expand_season, $expand_route);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// ��3������ GameNews
	$from_id = 0;
	$from_name =  "system";
	$to_id = $team_id;
	$Mail_subject = "Congratulation! Set expand stadium success.";
	$mail_content = "Congratulation! Set expand stadium success.";
	$status = 0;
	$type = "1";  // 1 ��ʾGAME NEWS
	$small_type = "1";  // 1 ��ʾ��STADIUM���
	$returnValue = insertIntoMail($db, $from_id, $from_name, $to_id, $Mail_subject, $mail_content, $status, $type, $small_type);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// ��4���ı䶭�»��̬��
	$board_attitude = 'D';  // D ��ʾ�������̽�����
	$returnValue = updateBoardStadium($db, $stadium_id, $board_attitude);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	
	// commit
	$db->CommitTrans();
	
	$error_message = "confirm expand stadium Success.";
	require ("$document_root/page/system/error.php");
	
	
	// ��󣬷��� stadium.php ҳ��
	goToPageInTime(2, "/fmol/page/stadium/stadium.php");	
	
	return true;
}

	/**
	 * ������ϯ
	 *
	 * @param [db]				db
	 * @param [document_root]	document_root
	 *
	 * @return return true or false
	 */	
function performAddSeater($db, $document_root)
{
	$club_id = sql_quote($_SESSION["s_primary_club_id"]);
	$team_id = sql_quote($_SESSION["s_primary_team_id"]);
	$stadium_id = sql_quote($_POST["primary_stadium_id"]);
	$add_seater_num = sql_quote($_POST["add_seater_num"]);
	$add_seater_cost = sql_quote($_POST["add_seater_cost"]);
	$add_seater_time = sql_quote($_POST["add_seater_time"]);
	$start_project_fee = sql_quote($_POST["start_project_fee"]);
	$project_type = "0";    // 0 ��ʾ������ϯ
	$start_time = date("Y-m-d H:i:s");
	$db->RollbackTrans(); 
	// ����ʼ
	$db->BeginTrans();
	
	// ��1����������ϯ�����ʱ�¼���
	$returnValue = insertIntoAlterStadiumBuffer($db, $stadium_id, $start_time, $project_type, $add_seater_time, $add_seater_num);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// ��2������ GameNews
	$from_id = 0;
	$from_name =  "system";
	$to_id = $team_id;
	$Mail_subject = "Congratulation! Set add seater success.";
	$mail_content = "Congratulation! Set add seater success. The project will accomplish in $add_seater_time.";
	$status = 0;
	$type = "1";  // 1 ��ʾGAME NEWS
	$small_type = "1";  // 1 ��ʾ��STADIUM���
	$returnValue = insertIntoMail($db, $from_id, $from_name, $to_id, $Mail_subject, $mail_content, $status, $type, $small_type);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	
	// ȡ���������������season
	$season = "0";
	$returnValue = getCurrentSeason($db, $team_id, $season);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}	
	// ��3����������ϯ����Ҫ�ķ��ü�¼��finance����
	$returnValue = handleAddSeaterFee($db, $club_id, $team_id, $season, $add_seater_cost, $start_project_fee);
	if ($returnValue != "0") {
		$db->RollbackTrans(); 
		$error_message = $returnValue;
		require ("$document_root/page/system/error.php");
		
		goBackInTime(3500, -1); 
	}
	
	// commit
	$db->CommitTrans();
	
	$error_message = "confirm add seater Success.";
	require ("$document_root/page/system/error.php");
	
	// ��󣬷��� stadium.php ҳ��
	goToPageInTime(2, "/fmol/page/stadium/stadium.php");	
	
	return true;
}


//----------------------------------------------------------------------------	
// ʹ�õ��ĺ���
//----------------------------------------------------------------------------
	/**
	 * �޸�������
	 *
	 * @param [db]					database
	 * @param [stadium_id]			stadium ������
	 * @param [stadium_name]		stadium_name
	 *
	 * @return return error message
	 */	
function updateStadiumName($db, $stadium_id, $stadium_name)
{
	$query = sprintf(
				" UPDATE stadium SET " . 
				" name='%s' " . 
				" WHERE id='%s' " ,
				$stadium_name, $stadium_id);
	$rs = &$db->Execute($query);
	
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * ��������ϯ�����ʱ�¼���
	 *
	 * @param [db]					database
	 * @param [stadium_id]			stadium ������
	 * @param [start_time]			start_time
	 * @param [project_type]		���̵����ͣ�0��ʾ������ϯ��1��ʾ������
	 * @param [cost_time]			���ĵ�ʱ��
	 * @param [add_seater_num]		���project_typeΪ0�Ļ�����ʾ���ӵ���ϯ����
	 *
	 * @return return error message
	 */	
function insertIntoAlterStadiumBuffer($db, $stadium_id, $start_time, $project_type, $cost_time, $add_seater_num)
{
	$query = sprintf(
				" INSERT INTO alter_stadium_buffer (stadium_id, start_time, remaining_time, project_type, add_seater_num) " .
				" VALUES ('%s', '%s', '%s', '%s', '%s') " ,
				$stadium_id, $start_time, $cost_time, $project_type, $add_seater_num);
	$rs = &$db->Execute($query);

	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * �������������ʱ�¼���
	 *
	 * @param [db]					database
	 * @param [stadium_id]			stadium ������
	 * @param [start_time]			start_time
	 * @param [project_type]		���̵����ͣ�0��ʾ������ϯ��1��ʾ������
	 * @param [set_expand_season]	ȷ�������򳡵�season
	 * @param [expand_route]		������·��ѡ��
	 *
	 * @return return error message
	 */	
function insertIntoAlterStadiumBuffer2($db, $stadium_id, $start_time, $project_type, $set_expand_season, $expand_route)
{
	$query = sprintf(
				" INSERT INTO alter_stadium_buffer (stadium_id, start_time, project_type, set_expand_season, expand_route) " .
				" VALUES ('%s', '%s', '%s', '%s', '%s') " ,
				$stadium_id, $start_time, $project_type, $set_expand_season, $expand_route);
	$rs = &$db->Execute($query);
	
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}


/**
	 * ȡ�õ�ǰ��season
	 *
	 * @param [db]					database
	 * @param [team_id]				team ������
	 * @param [season]				season�����ŵĵط�
	 *
	 * @return return error message
	 */	
function getCurrentSeason($db, $team_id, &$season)
{
	// get the current season
	$query = sprintf(
				" SELECT d.season " . 
				" FROM division d, team_in_div tid  " . 
				" WHERE d.div_id=tid.div_id AND tid.team_id='%s' " ,
				$team_id);

	$rs = &$db->Execute($query);
	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	else if ($rs->RecordCount() > 0 ) {
		$season = $rs->fields["season"];
	}	
	
	return "0";
}

	/**
	 * ��������ϯ����Ҫ�ķ��ü�¼��finance����
	 *
	 * @param [db]					database
	 * @param [club_id]				club ������
	 * @param [team_id]				team ������
	 * @param [season]				���������������ǰ��season
	 * @param [add_seater_cost]		������ϯ��Ҫ�ķ���
	 * @param [start_project_fee]	ÿ�ζ�����Ҫ�������ķ���
	 *
	 * @return return error message
	 */	
function handleAddSeaterFee($db, $club_id, $team_id, $season, $add_seater_cost, $start_project_fee)
{

	
	// update the fee into finance
	$all_fee = doubleval($add_seater_cost) + doubleval($start_project_fee);
	$query = sprintf(
				" UPDATE finance SET " . 
				" facility=facility+$all_fee, total_expenditure=total_expenditure+$all_fee " . 
				" WHERE club_id='%s' AND season='%s' " ,
				$club_ids, $season);
	$rs = &$db->Execute($query);

	if (!$rs) {		
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
		
	return "0";
}

	/**
	 * ��A��B̬�ȣ������Ҫ����5����Ȩǿ��ͨ��
	 *
	 * @param [db]					database
	 * @param [club_id]				club ������
	 * @param [team_id]				team ������
	 * @param [cost_privilege_point_num]		���ѵ���Ȩ����
	 *
	 * @return return error message
	 */	
function reducePrivelegePoint($db, $club_id, $cost_privilege_point_num)
{
	$query = sprintf(
				" UPDATE club SET privilege_point_num=privilege_point_num-$cost_privilege_point_num " .
				" WHERE club_id='%s' " ,
				$club_id);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	
	return "0";
}

	/**
	 * ���¶��»���������򳡵����
	 *
	 * @param [db]					database
	 * @param [stadium_id]			stadium ������
	 * @param [board_attitude]		���»���������򳡵��������
	 *
	 * @return return error message
	 */	
function updateBoardStadium($db, $stadium_id, $board_attitude)
{
	$query = sprintf(
				" UPDATE stadium SET board_attitude='%s' " .
				" WHERE id='%s' " ,
				$board_attitude, $stadium_id);  
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ) {
		return "There is not this right record in the database.";
	}
	
	return "0";
}

	/**
	 * insert a record into mail
	 *
	 * @param [db]					database
	 * @param [from_id]				from_id
	 * @param [from_name]			from_name
	 * @param [to_id]				to_id	
	 * @param [subject]				subject	
	 * @param [content]				content	
	 * @param [status]				status	
	 * @param [type]				type	
	 * @param [small_type]			small_type	
	 *
	 * @return return "0", or other error msg
	 */	
function insertIntoMail($db, $from_id, $from_name, $to_id, $subject, $content, $status, $type, $small_type="1")
{
	$query = sprintf(
				" INSERT INTO mail (from_id, from_name, to_id, subject, content, time, status, type, small_type) " .
				" VALUES ('%s', '%s', '%s', '%s', '%s', now(), '%s', '%s', '%s') " ,
				$from_id, $from_name, $to_id, $subject, $content, $status, $type, $small_type);
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	
	return "0";
}



?>


