<?php 
session_start();

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

$s_primary_team_id = sql_quote($_SESSION['s_primary_team_id']);

$query = sprintf(
			" SELECT d.div_id AS primary_div_id " . 
			" FROM division d, team_in_div tid " . 
			" WHERE tid.team_id='%s' AND tid.div_id=d.div_id " ,
			$s_primary_team_id);

$rs = &$db->Execute($query);

if (!$rs) {
    print 'Database error.'; // Displays the error message if no results could be returned
}
else {
    if ($rs->RecordCount() > 0) {
	    $_SESSION['s_primary_div_id'] = $rs->fields['primary_div_id']; 
		$s_primary_div_id = $rs->fields['primary_div_id'];  // change the "primary_div_id" this time
    }
}		

?>
