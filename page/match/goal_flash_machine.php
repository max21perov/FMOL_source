<?php

// ����$goal_id�ӱ�move_action�л�ȡ��Ա����ʱ���˶�·��
// �����Ҫ���û������ˡ�play����ť�Ժ�ŷ���

// ��ô�����phpҳ���У�$goal_id�������ȡ�أ�
// ��������� ��goal_flash.tpl.php�е�play.swf������ϡ�?goal_id={GOAL_ID}����
//             ��������flash�о���һ��goal_id�ı���������ֵΪ{GOAL_ID}

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
//require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");   // ע�⣬���ﲻ��ʹ��adodb�ķ������������ݿ⣬ֻ��ʹ����ԭʼ�ķ���
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

//require_once(DOCUMENT_ROOT . "/lib/db_connect_normal.inc.php");






//----------------------------------------------------------------------------	
// get the data from POST
//----------------------------------------------------------------------------
/*
 * ȡ�ô�flash���͹���������
 */
$goal_id = sql_quote($_POST["goal_id"]); //�������������ı���

//----------------------------------------------------------------------------	
// get the data from database
//----------------------------------------------------------------------------	
$move_action_str = getMoveActionOfGoal($goal_id);
//$move_action_str = "a1";
/*
 * �����ݷ��ص�flash��
 */
print "move_action_str=" . $move_action_str;

exit(0);



//----------------------------------------------------------------------------	
// functions
//----------------------------------------------------------------------------	

/**
 * get the move action of goal
 *
 * @param [goal_id]				goal_id
 * @param [cur_season]			cur_season
 *
 * @return  $move_action_str
 */	

function getMoveActionOfGoal($goal_id)
{
	$move_action_str = "";	
	
	//----------------------------------------------------------------------------	
	// use the normal method to visit the database
	//----------------------------------------------------------------------------	
	
	$link = mysql_connect("localhost", "fmolphp", "123") ;  //or die("Could not connect: " . mysql_error()); 
	mysql_select_db("fmol");
	
	
	$query = sprintf(
				" select m.player_id, m.player_name, p.custom_given_name as given_name, p.custom_family_name as family_name, " .
				" m.player_pitch_x, m.player_pitch_y, m.action_type, " . 
				" m.result_type, m.shot_type, m.shot_power, " .
				" m.shot_angle, m.shot_hit_bar, m.shot_hit_post, " .
				" m.is_turned, m.turned_pitch_x, m.turned_pitch_y, " .
				" m.ball_pitch_x, m.action_id, p.cloth_number " .
  				" from move_action m " .
  				" LEFT JOIN player p ON m.player_id=p.player_id " .
				" where m.goal_id='%s' " .
				" order by m.action_id ",
				$goal_id);

	$result = mysql_query($query);
	
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$full_name = "";
		$cloth_number = "";
		if ($row['player_id'] == "") {  // is [gray player]
			$full_name = $row['player_name'];
			
			$cloth_number = "GP";
		}
		else {
			if ($row['given_name'] == "") {
				$full_name = $row['family_name'];
			}
			else {
				$full_name = $row['given_name'] . " . " . $row['family_name'];
			}
			
			$cloth_number = $row["cloth_number"];
		}
		
		
			
		$move_action_entity_str = "";
		$move_action_entity_str = $full_name . "," . 
								 $cloth_number . "," .  
								 $row["player_pitch_x"] . "," . 
								 $row["player_pitch_y"] . "," .  
		 						 $row["action_type"] . "," .  
								 $row["result_type"] . "," .  
								 $row["shot_type"] . "," .  
								 $row["shot_power"] . "," .  
								 $row["shot_angle"] . "," .  
								 $row["shot_hit_bar"] . "," .  
								 $row["shot_hit_post"] . "," .  
								 $row["is_turned"] . "," .  
								 $row["turned_pitch_x"] . "," .  
								 $row["turned_pitch_y"] . "," .  
								 $row["ball_pitch_x"];
		
		$move_action_str .= "|" . $move_action_entity_str;

	}
		
	if (strlen($move_action_str) > 0) {
		$move_action_str = substr($move_action_str, 1);
	}
		
	return $move_action_str;
	
}



/**
 * get the move action of goal
 *
 * @param [db]					db	
 * @param [goal_id]				goal_id
 *
 * @return  $move_action_str
 */	
/*
function getMoveActionOfGoal($goal_id)
{
	$move_action_str = "";	
	
	$query = sprintf(
				" select m.player_id, p.custom_given_name as given_name, p.custom_family_name as family_name, " .
				" m.x_coordinate, m.y_coordinate, m.action_type, " . 
				" m.result_type, m.shot_type, m.shot_power, " .
				" m.shot_angle, m.shot_hit_bar, m.shot_hit_post, " .
				" m.action_id " .
  				" from move_action m, player p " .
				" where m.goal_id='%s' " .
				" and m.player_id=p.player_id " .
				" order by m.action_id ",
				$goal_id);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		print "Database error.";  // $db->ErrorMsg(); // Displays the error message if no results could be returned
	}
	else {
		
		for (; !$rs->EOF; $rs->MoveNext()) {
					
			
			$full_name = "";
			if ($rs->fields['given_name'] == "") {
				$full_name = $rs->fields['family_name'];
			}
			else {
				$full_name = $rs->fields['given_name'] . " . " . $rs->fields['family_name'];
			};
		
			$move_action_entity_str = "";
			$move_action_entity_str = $full_name . "," . 
									 $rs->fields["x_coordinate"] . "," .  
									 $rs->fields["y_coordinate"] . "," .  
			 						 $rs->fields["action_type"] . "," .  
									 $rs->fields["result_type"] . "," .  
									 $rs->fields["shot_type"] . "," .  
									 $rs->fields["shot_power"] . "," .  
									 $rs->fields["shot_angle"] . "," .  
									 $rs->fields["shot_hit_bar"] . "," .  
									 $rs->fields["shot_hit_post"];
			
			$move_action_str .= "|" . $move_action_entity_str;
			
		}
	}
	
	if (strlen($move_action_str) > 0) {
		$move_action_str = substr($move_action_str, 1);
	}
		
	return $move_action_str;
	
}
*/




?>
