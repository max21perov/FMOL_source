<?php

class Session {
	protected $db = "";
	protected $user_id = "";
	protected $ip_address = "";
	protected $last_activity = "";
	
	
	function Session($db) {
		$this->db = $db;
	}
	
	function insert($user_id, $ip_address, $last_activity) {
		
		$query = sprintf(
				" INSERT INTO session (user_id, ip_address, last_activity) " .
				" VALUES ('%s', '%s', '%s') " ,
				$user_id, $ip_address, $last_activity );
				
		$rs = &$this->db->Execute($query); 
		if (!$rs) {
			//print $this->db->ErrorMsg(); 
			return -1;
		}
		else if ($rs->RecordCount() < 0 ){
			return -2;
		}
		else {
			return 0;
		}
	}

	
	function update($user_id, $ip_address, $last_activity) {

		$query = sprintf(
				" UPDATE session SET ip_address='%s', last_activity='%s' " .
				" WHERE user_id='%s' " ,
				$ip_address, $last_activity, $user_id );
				
		$rs = &$this->db->Execute($query);
		if (!$rs) {
			return -1;
		}
		else if ($rs->RecordCount() < 0 ){
			return -2;
		}
		else {
			return 0;
		}
	}
	
	
	function delete($condition) {
	
		$query = sprintf(
				" DELETE FROM session " .
				" WHERE %s " ,
				$condition );
			
		$rs = &$this->db->Execute($query);
		if (!$rs) { 
			return -1;
		}
		else if ($rs->RecordCount() < 0 ){
			return -2;
		}
		else {
			return 0;
		}
	}
	
	
	function get_from_condition($condition) {
	
		$query = sprintf(
				" SELECT id, user_id, ip_address, last_activity " .
				" FROM session " .
				" WHERE %s " ,
				$condition );
				
		$rs = &$this->db->Execute($query);
		
		if (!$rs) {
			return -1;
		}
		else if ($rs->RecordCount() < 0 ) {
			return -2;
		}
		else {
			return $rs;
		}
	}
}

?>
