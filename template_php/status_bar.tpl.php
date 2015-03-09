
<style>

.NoColorText {
	FONT-SIZE: 15px; MARGIN: 2px 2px 2px 0px; FONT-FAMILY: verdana, arial, Helvetica, sans-serif;
	background-color:{STATUS_BAR_BGCOLOR};
	color:{STATUS_BAR_FONTCOLOR};
	FONT-WEIGHT: bold;
}
.NoColorText A {
	TEXT-DECORATION: none;
	color:{STATUS_BAR_FONTCOLOR};
}
.NoColorText A:hover {
	TEXT-DECORATION: underline;
	color:{STATUS_BAR_FONTCOLOR};
}
.NoColorText A:visited {
	color:{STATUS_BAR_FONTCOLOR};
	
}

</style>

<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CAD6E8">
{SPACE}
  <tr>
    <td class="NoColorText">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td nowrap width="200" title="club name">&nbsp;<a href="/fmol/page/info/club_info.php?team_id={TEAM_ID}" >{CLUB_NAME}</a></td>
        <td width="*" align="center" nowrap>&nbsp;</td>
        <td width="160" title="manager name">Manager:&nbsp;{NO_USER_NAME}<a href="/fmol/page/system/back_to_self_team.php?target_page=user" >{USER_NAME}</a></td>
		
		<td width="35" nowrap>&nbsp;<a href="/fmol/page/mail/mail.php" style="display:{NEWS_DISPLAY} "><img border="0" src="/fmol/images/mail_new.gif" alt="news"></a>&nbsp;</td>
		
		<form name="form_friendly_arrange" method="get" action="/fmol/page/friendly/friendly_arrange.php">
        <td width="80" align="right" nowrap>
		  <input type="hidden" name="opponent_team_id" value="{OPPONENT_TEAM_ID}" />
		  <input type="hidden" name="team_id" value="{SELF_TEAM_ID}" />
		  <input name="Challenge" type="submit" class="button" value="Challenge" />		  
		</td>
		</form>
		
		<td width="3">&nbsp;</td>
      </tr>
    </table>
	</td>
  </tr>
</table>
