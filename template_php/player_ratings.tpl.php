
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
<form name="board_form" method="post" >
	<input type="hidden" name="action_str" value="{ACTION_STR}" />
	
	
    <tr><td height="2">{SPACE}</td></tr>
	
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Team Stats </div> </td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="5" cellpadding="0">
                  <tr>
                    <td><span class="BlackText" ><a href="/fmol/page/info/club_info.php?team_id={HOME_PRIMARY_TEAM_ID}">{HOME_TEAM_NAME}</a></span></td>
                    <td><span class="BlackText" ><a href="/fmol/page/info/club_info.php?team_id={AWAY_PRIMARY_TEAM_ID}">{AWAY_TEAM_NAME}</a></span></td>
                  </tr>
                  <tr>
                    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                      <tr>
                        <td class="gSGSectionColumnHeadings">No.</td>
                        <td class="gSGSectionColumnHeadings">C</td>
                        <td class="gSGSectionColumnHeadings">Name</td>
                        <td class="gSGSectionColumnHeadings">Inf.</td>
                        <td class="gSGSectionColumnHeadings" title="condition">Con</td>
                        <td class="gSGSectionColumnHeadings" title="rating">Rat</td>
                        <td class="gSGSectionColumnHeadings" title="gols">Gls</td>
                      </tr>
					  
                      <!-- BEGIN home_player_ratings -->
                      <tr class="gSGRowOdd">
                        <td>&nbsp;{CLOTH_NUMBER}</td>
                        <td >{CAPTION}</td>
                        <td ><span class="BlackText"><a style="display:{PLAYER_NAME_LINE_DISPLAY} " href="/fmol/page/players/player_info.php?player_id={PRIMARY_PLAYER_ID}">{PLAYER_NAME}</a><span style="display:{PLAYER_NAME_ONLY_DISPLAY} ">{PLAYER_NAME}</span></span></td>
                        <td >{PLAYER_INF}</td>
                        <td >{CONDITION}</td>
                        <td >{RATING}</td>
                        <td >{GOALS}</td>
                      </tr>
                      <tr>
                        <td class="cBBottom2" colspan="25"><img height="1" src="/fmol/images/blank.gif"></td>
                      </tr>
                      <!-- END home_player_ratings -->
                    </table></td>
					
                    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                      <tr>
                        <td class="gSGSectionColumnHeadings">No.</td>
                        <td class="gSGSectionColumnHeadings">C</td>
                        <td class="gSGSectionColumnHeadings">Name</td>
                        <td class="gSGSectionColumnHeadings">Inf.</td>
                        <td class="gSGSectionColumnHeadings" title="condition">Con</td>
                        <td class="gSGSectionColumnHeadings" title="rating">Rat</td>
                        <td class="gSGSectionColumnHeadings" title="gols">Gls</td>
                      </tr>
					  
                      <!-- BEGIN away_player_ratings -->
                      <tr class="gSGRowOdd">
                        <td>&nbsp;{CLOTH_NUMBER}</td>
                        <td >{CAPTION}</td>
                        <td ><span class="BlackText"><a style="display:{PLAYER_NAME_LINE_DISPLAY} " href="/fmol/page/players/player_info.php?player_id={PRIMARY_PLAYER_ID}">{PLAYER_NAME}</a><span style="display:{PLAYER_NAME_ONLY_DISPLAY} ">{PLAYER_NAME}</span></span></td>
                        <td >{PLAYER_INF}</td>
                        <td >{CONDITION}</td>
                        <td >{RATING}</td>
                        <td >{GOALS}</td>
                      </tr>
                      <tr>
                        <td class="cBBottom2" colspan="25"><img height="1" src="/fmol/images/blank.gif"></td>
                      </tr>
                      <!-- END away_player_ratings -->
                    </table></td>
                  </tr>
                </table></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
	
	
	
	
	</form>
</table>


