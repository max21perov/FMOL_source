			<!-- fix_friendly -->
			  
			 <tr>
				<td colspan="7" class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
			  </tr>
			  
            <form method="post" action="/fmol/page/friendly/handle_friendly.php&next_page={NEXT_PAGE}&friendly_filter={FRIENDLY_FILTER}">
              <tr class="{FIX_FRIENDLY_TR_CLASS}">
                <td>&nbsp;{STATUS}</td>
                <td>{USER_NAME}</td>
                <td class="OtherTeamText"><a href="/fmol/page/info/club_info.php?team_id={OPPONENT_PRIMARY_TEAM_ID}">{TEAM_NAME}</a></td>
                <td>{HOME_OR_AWAY}</td>
                <td>{MATCH_DATE}</td>
                <td>{MATCH_TIME}</td>
                <td align="right"><input type="hidden" name="friendly_id" value="{FRIENDLY_ID}">
                    <input type="hidden" name="opponent_id" value="{OPPONENT_ID}">
                    <input name="details" type="button" class="button" value="details">
      &nbsp;</td>
              </tr>
            </form>
			