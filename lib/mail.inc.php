<?php 

function sendMail($db, $from_id, $from_name, $to_id, $subject, $content, $type) 
{
	// change the club name
	$query  = " INSERT INTO mail(from_id, from_name, to_id, subject, content, time, status, type) ";
	$query .= " VALUES ('$from_id', '$from_name', '$to_id', ";
	$query .= " '$subject', '$content', now(), '0', '$type') ";
	
	$rs = &$db->Execute($query);
									  
    if (!$rs) { 
		return -1;
	}
	else if ($rs->RecordCount() < 0) {
		return -1;
	}
	else {
		return 1;
	}			
}

function updateMailContent($db, $mail_id, $mail_content)
{	

	$query = sprintf(
			" UPDATE mail " . 
			" SET status='3', content='%s' " . 
			" WHERE id='%s' " , 
			$mail_content, $mail_id);
			
			
	$rs = &$db->Execute($query);
									  
    if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0) {
		return "There is not this right record in the database.";
	}
	else {
		return "0";
	}		
}

?>
