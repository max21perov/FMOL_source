<?php
// get the "team_player_cookie" from cookie
// and parse it to "team_arr" and "player_arr"
$cookie_value = $HTTP_COOKIE_VARS["team_player_cookie"]; 
$team_arr = Array();
$player_arr = Array();
if ($cookie_value != null && $cookie_value != "") {
	$big_arr = explode("|", $cookie_value);
	$big_len = count($big_arr);   
	for ($i=0; $i<$big_len; ++$i) {
		$item_value = $big_arr[$i]; 
		$little_arr = explode(":", $item_value);
		if (count($little_arr) != 3) continue;
		if ($little_arr[0] == "0")
			$team_arr[count($team_arr)] = $little_arr;
		else if ($little_arr[0] == "1")
			$player_arr[count($player_arr)] = $little_arr;
			
	}
}

// team
$teamHTML  = "";
$teamHTML .= '<table width="100%"  border="0" cellpadding="0" cellspacing="0">';
$teamHTML .= '<tr>';
$teamHTML .= '<td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Team</div></td>';
$teamHTML .= '</tr>';	
$len = count($team_arr);  
for ($i=0; $i<$len; ++$i) {			
	$teamHTML .= '<tr>';
	$teamHTML .= '<td class="gSGRowOdd"><span class="BlackText">&nbsp;<a href="/fmol/page/info/club_info.php?team_id=' . $team_arr[$i][1] . '">'. $team_arr[$i][2] .'</a></span></td>';
	$teamHTML .= '</tr>';	
	if ($i < ($len-1))
		$teamHTML .= '<tr><td class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td></tr>';
}
$teamHTML .= '</table>';

// player
$playerHTML  = "";
$playerHTML .= '<table width="100%"  border="0" cellpadding="0" cellspacing="0">';
$playerHTML .= '<tr>';
$playerHTML .= '<td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Player</div></td>';
$playerHTML .= '</tr>';	
$len = count($player_arr);
for ($i=0; $i<$len; ++$i) {				
	$playerHTML .= '<tr>';
	$playerHTML .= '<td class="gSGRowOdd"><span class="BlackText">&nbsp;<a href="/fmol/page/players/player_info.php?player_id=' . $player_arr[$i][1] . '">'. $player_arr[$i][2] .'</a></span></td>';
	$playerHTML .= '</tr>';	
	if ($i < ($len-1))
		$playerHTML .= '<tr><td class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td></tr>';
}
$playerHTML .= '</table>';

$objHTML  = "";
$objHTML .= '<table width="100%"  border="0" cellspacing="0" cellpadding="0">';
$objHTML .= '<tr><td height="1"></td></tr>';
$objHTML .= '<tr>';
$objHTML .= '<td valign="top">';
$objHTML .= '<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">';
$objHTML .= '<tr><td>';
$objHTML .=  $teamHTML ;
$objHTML .= '</td></tr></table>';
$objHTML .= '</td>';
$objHTML .= '<td width="2">&nbsp;</td>';
$objHTML .= '<td valign="top">';
$objHTML .= '<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">';
$objHTML .= '<tr><td>';
$objHTML .=  $playerHTML ;
$objHTML .= '</td></tr></table>';
$objHTML .= '</td>';
$objHTML .= '</tr>';	
$objHTML .= '</table>';

echo $objHTML;
		
?>

