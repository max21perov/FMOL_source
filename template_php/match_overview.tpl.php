
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
<form name="board_form" method="post" >
	<input type="hidden" name="action_str" value="{ACTION_STR}" />
	
	
    <tr><td height="2">{SPACE}</td></tr>
	
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Goal Flashs </div></td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="1" cellspacing="1" >
                    <tr>
                      <td width="50%" valign="top">
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" class="gSGSectionColumnHeadings"><span class="{HOME_TEAM_CLASS}"><a href="/fmol/page/info/club_info.php?team_id={HOME_PRIMARY_TEAM_ID}">{HOME_TEAM}</a></span> </td>
                          </tr>
						  
						  <!-- BEGIN home_team_goal -->
						  
                          <tr>
                            <td class="BlackText">&nbsp;<a href="/fmol/page/match/goal_flash.php?goal_id={GOAL_ID}&match_type={MATCH_TYPE}&match_id={MATCH_ID}">Min {MINUTE}: {PLAYER_NAME} goal. </a></td>
                          </tr>
						  <tr>
							<td class="cBBottom2"><img height="1" src="/fmol/images/blank.gif"></td>
						  </tr>
						  <!-- END home_team_goal -->
						  
						  
                        </table></td>
                   
                      <td width="50%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" class="gSGSectionColumnHeadings"><span class="{AWAY_TEAM_CLASS}"><a href="/fmol/page/info/club_info.php?team_id={AWAY_PRIMARY_TEAM_ID}">{AWAY_TEAM}</a></span> </td>
                          </tr>
                          
						  <!-- BEGIN away_team_goal -->
						  
                          <tr>
                            <td class="BlackText">&nbsp;<a href="/fmol/page/match/goal_flash.php?goal_id={GOAL_ID}&match_type={MATCH_TYPE}&match_id={MATCH_ID}">Min {MINUTE}: {PLAYER_NAME} goal. </a></td>
                          </tr>
						  <tr>
							<td class="cBBottom2"><img height="1" src="/fmol/images/blank.gif"></td>
						  </tr>
						  <!-- END away_team_goal -->
						  
						  
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


