
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
<form name="board_form" method="post" >
	<input type="hidden" name="action_str" value="{ACTION_STR}" />
	
	
    <tr><td height="2">{SPACE}</td></tr>
	
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Match Logs </div></td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td align="center" class="gSGSectionColumnHeadings">time</td>
						
                      <td align="center" class="gSGSectionColumnHeadings">team</td>
						
                      <td align="center" class="gSGSectionColumnHeadings">type_event</td>
                      <td align="center" class="gSGSectionColumnHeadings">player1</td>
                      <td align="center" class="gSGSectionColumnHeadings">player2</td>
                    </tr>
					
					
					<!-- BEGIN match_logs -->
					<tr>
							<td class="cBBottom2" colspan="5"><img height="1" src="/fmol/images/blank.gif"></td>
					  </tr>
                    <tr class="gSGRowOdd">
                      <td align="center">&nbsp;{LOG_TIME}</td>
                      <td align="center"><span class="BlackText"><a href="/fmol/page/info/club_info.php?team_id={PRIMARY_TEAM_ID}">{TEAM_NAME}</a></span></td>
                      <td align="center" style="color: {TYPE_EVENT_TD_COLOR}">{TYPE_EVENT}</td>
					  <td align="center"><span class="BlackText"><a style="display:{PLAYER1_NAME_LINE_DISPLAY} " href="/fmol/page/players/player_info.php?player_id={PRIMARY_PLAYER1_ID}">{PLAYER1_NAME}</a><span style="display:{PLAYER1_NAME_ONLY_DISPLAY} ">{PLAYER1_NAME}</span></span></td>
					  <td align="center"><span class="BlackText"><a style="display:{PLAYER2_NAME_LINE_DISPLAY} " href="/fmol/page/players/player_info.php?player_id={PRIMARY_PLAYER2_ID}">{PLAYER2_NAME}</a><span style="display:{PLAYER2_NAME_ONLY_DISPLAY} ">{PLAYER1_NAME}</span></span>&nbsp;</td>
                    </tr>
                    <!-- END match_logs -->
                    
                </table>
				</td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
	
	
	
	
	</form>
</table>


