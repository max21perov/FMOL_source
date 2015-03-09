
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
<form name="board_form" method="post" >
	<input type="hidden" name="action_str" value="{ACTION_STR}" />
	
	
    <tr><td height="2">{SPACE}</td></tr>
	
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Match Stats </div></td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td width="20%" align="center" class="gSGSectionColumnHeadings"><span class="BlackText" ><a href="/fmol/page/info/club_info.php?team_id={HOME_PRIMARY_TEAM_ID}">{HOME_TEAM}</a></span></td>
						
                   <td width="60%" align="center" class="gSGSectionColumnHeadings">Statistic</td>
						
                      <td width="20%" align="center" class="gSGSectionColumnHeadings"><span class="BlackText" ><a href="/fmol/page/info/club_info.php?team_id={AWAY_PRIMARY_TEAM_ID}">{AWAY_TEAM}</a></span></td>
                    </tr>
					
					
					<!-- BEGIN match_stats -->
					<tr>
							<td class="cBBottom2" colspan="3"><img height="1" src="/fmol/images/blank.gif"></td>
					  </tr>
                    <tr class="{STATS_TR_CLASS}">
                      <td align="center">&nbsp;{HOME_TEAM_STATS}</td>
                      <td align="center">{STATS_ITEM_NAME}</td>
                      <td align="center">{AWAY_TEAM_STATS}&nbsp;</td>
                    </tr>
                    <!-- END match_stats -->
                    
                </table>
				</td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
	
	
	
	
	</form>
</table>


