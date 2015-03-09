<?php
session_start();

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");

$s_self_user_name = $_SESSION['s_self_user_name'];
$s_self_club_name = $_SESSION['s_self_club_name'];
//$s_opponent_primary_team_id = $_SESSION['s_opponent_primary_team_id'];
$s_opponent_primary_team_id = sql_quote($_GET['team_id']);
// unregister the session variable 's_opponent_primary_team_id'
//session_unregister('s_opponent_primary_team_id');

?>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9" >
  <tr>
	<td height="20" align="center" bgcolor="#CCCCCC"><a href="/fmol/page/system/back_to_self_team.php?target_page=user" ><?php echo $s_self_user_name; ?> </a></td>
  </tr>
  <tr>
	<td height="20" align="center" bgcolor="#CCCCCC"><a href="/fmol/page/info/club_info.php" ><?php echo $s_self_club_name; ?></a></td>
  </tr>
  
  <form name="form2" method="get" action="/fmol/page/friendly/friendly_arrange.php">
  <tr>
	<td height="25" align="center" bgcolor="#CCCCCC">
	  <input type="hidden" name="team_id" value="<?=$s_opponent_primary_team_id;?>" />
	  <input name="Challenge" type="submit" class="button" value="Challenge" />
	</td>
  </tr>
  </form>
  
  <tr><td bgcolor="#cccccc">&nbsp;</td>
  </tr>
</table>