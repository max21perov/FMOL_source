<?php 



/**
 * get Match Team Arr
 *
 * @param [db]						db	
 * @param [match_type]				match_type
 * @param [match_id]				match_id
 * @param [full_highlight]			full_highlight	
 	0 - full comment
	1 - highlight comment
 *
 * @return  $match_team_arr
 */	
function getCommentInfo($db, $match_type, $match_id, $full_highlight)
{
	$comment_info = array();
	
	$query = sprintf(
				" SELECT id AS comment_id, minutes, comment " .
				" FROM comment " .
				" WHERE match_type='%s' AND match_id='%s' AND full_highlight='%s' " ,  
				$match_type, $match_id, $full_highlight);
	
	$rs = &$db->Execute($query);
	
	if (!$rs) {
		$error_message = "Database Error!"; //  $db->ErrorMsg();
	}
	else {
		if ($rs->RecordCount() > 0) {
			$comment_info["comment_id"] = $rs->fields['comment_id'];
			$comment_info["minutes"] = $rs->fields['minutes'];
			$comment_info["comment"] = $rs->fields['comment'];
		}
	}		
	
	return $comment_info;

	
}


/**
 * display the match report
 *
 * @param [db]					db	
 * @param [tpl]					tpl
 * @param [minute_arr]			minute_arr	
 *
 * @return  void
 */	
function displayMatchReport($db, $tpl, $comment_info)
{	
	
	//$minute_arr = explode('||', $comment_info["minutes"]);
	
	$seperater_big = "||";
	$seperater_middle = "**";
	$seperater_small = "^^";
	$comment_big_arr = explode($seperater_big, $comment_info["comment"]);
	
	$len = count($comment_big_arr);  
	$cur_minute_comment = "";
	for ($i=0; $i<$len; ++$i) {
		$minute_and_comment = $comment_big_arr[$i];
		
		$comment_middle_arr = explode($seperater_middle, $minute_and_comment);
		
		$minute = $comment_middle_arr[0];
		$comment_str = $comment_middle_arr[1];    
		$comment_str = substr($comment_str, 0, (strlen($comment_str)-strlen($seperater_small)));

		$comment_small_arr = explode($seperater_small, $comment_str);
		
		
		$len_comment = count($comment_small_arr);  
		for ($j=0; $j<$len_comment; ++$j) {
			$comment_content = iconv("GBK", "UTF-8", $comment_small_arr[$j]);
			
			//$comment_content =  $comment_small_arr[$j];
			$tpl->setCurrentBlock("match_report_comment") ;
			
			$tpl->setVariable("COMMENT_CONTENT", $comment_content) ;		
			
			$tpl->parseCurrentBlock("match_report_comment") ;
		}
		
		// 
		$tpl->setCurrentBlock("match_report") ;
		
		$tpl->setVariable("MINUTE", $minute) ;		
		
		$tpl->parseCurrentBlock("match_report") ;
	}
			
}

/**
 * form report script code
 *
 * @param [comment_info]		comment_info
 *
 * @return  $script_code
 */	
function formReportScriptCode($comment_info)
{
	$script_code = "";
	$comment_id = $comment_info['comment_id'];
	$minutes = $comment_info['minutes'];
	
	if ($minutes != "") {
		// form the script code
		$script_code  = "var minutes = \"" . $minutes . "\";";
		$script_code .= "var minutesArr = minutes.split(\"||\");";
		$script_code .= "var comment_id = \"" . $comment_id . "\";" ;
		$script_code .= "var cur_minute_index = 0;" ;
		$script_code .= "var last_minute_value = 0; " ; 
	}
	
	return $script_code;
	
}


?>
