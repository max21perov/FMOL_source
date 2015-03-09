
<style>

.HomeNoColorText {
	FONT-SIZE: 15px; MARGIN: 2px 2px 2px 0px; FONT-FAMILY: verdana, arial, Helvetica, sans-serif;
	background-color:{HOME_THEME_BGCOLOR};
	color:{HOME_THEME_FONTCOLOR};
	FONT-WEIGHT: bold;
}
.HomeNoColorText A {
	TEXT-DECORATION: none;
	color:{HOME_THEME_FONTCOLOR};
}
.HomeNoColorText A:hover {
	TEXT-DECORATION: underline;
	color:{HOME_THEME_FONTCOLOR};
}
.HomeNoColorText A:visited {
	color:{HOME_THEME_FONTCOLOR};
	
}


.AwayNoColorText {
	FONT-SIZE: 15px; MARGIN: 2px 2px 2px 0px; FONT-FAMILY: verdana, arial, Helvetica, sans-serif;
	background-color:{AWAY_THEME_BGCOLOR};
	color:{AWAY_THEME_FONTCOLOR};
	FONT-WEIGHT: bold;
}
.AwayNoColorText A {
	TEXT-DECORATION: none;
	color:{AWAY_THEME_FONTCOLOR};
}
.AwayNoColorText A:hover {
	TEXT-DECORATION: underline;
	color:{AWAY_THEME_FONTCOLOR};
}
.AwayNoColorText A:visited {
	color:{AWAY_THEME_FONTCOLOR};
	
}



.ScoreBarText {
	FONT-SIZE: 15px; MARGIN: 2px 2px 2px 0px; FONT-FAMILY: verdana, arial, Helvetica, sans-serif;
	background-color:#a9a40c;
	color:#565d10;
	FONT-WEIGHT: bold;
}
</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0" >
<form name="board_form" method="post" >
	<input type="hidden" name="action_str" value="{ACTION_STR}" />
	
	
    <tr><td height="1">{SPACE}</td></tr>
	
	
	<tr>
	 <td>
	   <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td bgcolor="#FFFFFF" ><table width="100%" border="1" cellpadding="0" cellspacing="2" >
				
        
                <tr height="35" style="MARGIN-TOP: 2px; FONT-SIZE: 14px; MARGIN-BOTTOM: 2px; FONT-WEIGHT: bold; FONT-FAMILY: verdana,arial; BACKGROUND-COLOR: #ffffff ">

                  <td width="40%" class="HomeNoColorText">
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td align="right">({HOME_POS})&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/fmol/page/info/club_info.php?team_id={HOME_PRIMARY_TEAM_ID}">{HOME_TEAM}</a></td>
	<td width="5"></td>
  </tr>
</table>

				  </td>
				  
                  <td width="20%" class="ScoreBarText">
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5"></td>
    <td>{HOME_SCORE}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">{AWAY_SCORE}</td>
	<td width="5"></td>
  </tr>
</table>

				  </td>
				
                  <td width="40%" class="AwayNoColorText">
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5"></td>
    <td><a href="/fmol/page/info/club_info.php?team_id={AWAY_PRIMARY_TEAM_ID}">{AWAY_TEAM}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({AWAY_POS})</td>

    <td>&nbsp;</td>
  </tr>
</table>
				  </td>
				
                </tr>
     				
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
	  
	 </td>
	</tr>
	
	<tr><td height="2"></td></tr>
	
	
	
	
	</form>
</table>


