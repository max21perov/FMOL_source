<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr><td height="1">{SPACE}</td></tr>
  <tr><td>
  	<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>{EMPTY_VALUE}
	  <td colspan="3" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Round {ROUND}</div></td>
	</tr>
	<tr>
	  
	  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td class="gSGSectionTitleStatsGridOne">&nbsp;Results</td>
		</tr>
		<tr>
		  <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
			<tr>
			  <td class="gSGSectionColumnHeadings" width="7%">&nbsp;pos</td>
			  <td width="31%" align="right" class="gSGSectionColumnHeadings">team</td>
			  <td width="15%" align="center" class="gSGSectionColumnHeadings">result</td>
			  <td class="gSGSectionColumnHeadings" width="31%">team</td>
			  <td class="gSGSectionColumnHeadings" width="7%">pos</td>
			</tr>
			
					   
			<!-- BEGIN cur_round -->  
			<tr class="{CUR_ROUND_TR_CLASS}">
			  <td width="7%">&nbsp;({HOME_POS})</td>
			  <td width="31%" align="right" class="{HOME_TEAM_CLASS}"><a href="/fmol/page/info/club_info.php?team_id={HOME_PRIMARY_TEAM_ID}">{HOME_TEAM}</a></td>
			  <td width="15%" align="center" class="OtherTeamText"><a href="/fmol/page/match/match_overview.php?match_id={MATCH_ID}">{HOME_SCORE} - {AWAY_SCORE}</a></td>
			  <td width="31%" class="{AWAY_TEAM_CLASS}"><a href="/fmol/page/info/club_info.php?team_id={AWAY_PRIMARY_TEAM_ID}">{AWAY_TEAM}</a></td>
			  <td width="7%">({AWAY_POS})</td>
			</tr>
			<!-- END cur_round -->  
			
		   
		  </table></td>
	
		  
		  
		</tr>
	  </table></td>
	  
	</tr>
	
  </table></td>
  </tr>
</table>

  </td></tr>
  
  <tr><td height="2">
  </td></tr>
  
  <tr><td>
	<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="3" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Table</div></td>
          </tr>
          <tr>
            <td class="ProText"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                <tr>
				  <td class="gSGSectionColumnHeadings" align="center" width="30">&nbsp;</td>
                  <td width="7%" class="gSGSectionColumnHeadings">&nbsp;Pos</td>
                  <td class="gSGSectionColumnHeadings">Team</td>
                  <td class="gSGSectionColumnHeadings" width="7%" align="right">P</td>
                  <td class="gSGSectionColumnHeadings" width="7%" align="right">W</td>
                  <td class="gSGSectionColumnHeadings" width="7%" align="right">D</td>
                  <td class="gSGSectionColumnHeadings" width="7%" align="right">L</td>
                  <td class="gSGSectionColumnHeadings" width="7%" align="right">F</td>
                  <td class="gSGSectionColumnHeadings" width="7%" align="right">A</td>
                  <td class="gSGSectionColumnHeadings" width="7%" align="right">GD</td>
                  <td class="gSGSectionColumnHeadings" width="7%" align="right">Pts</td>
                  <td class="gSGSectionColumnHeadings" width="2%"></td>
                </tr>
                <!-- BEGIN points -->
                <tr class="{POINTS_TR_CLASS}">
				  <td align="center"><img name="UpNDown" src="/fmol/images/{RANK_IMG_LOCATION}" width="11" height="11" alt=""></td>
                  <td width="7%">&nbsp;({POS})</td>
                  <td class="{TEAM_CLASS}"><a href="/fmol/page/info/club_info.php?team_id={PRIMARY_TEAM_ID}">{TEAM_NAME}</a>&nbsp;</td>
                  <td width="7%" align="right">{GAMES_PLAYED}</td>
                  <td width="7%" align="right">{GAMES_WON}</td>
                  <td width="7%" align="right">{GAMES_DRAWN}</td>
                  <td width="7%" align="right">{GAMES_LOST}</td>
                  <td width="7%" align="right">{GOALS_FOR}</td>
                  <td width="7%" align="right">{GOALS_AGAINST}</td>
                  <td width="7%" align="right">{GOALS_DIFFERENCE}</td>
                  <td width="7%" align="right">{POINTS}</td>
                  <td width="2%"></td>
                </tr>
                <!-- END points -->
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  
  <tr><td height="2">
  </td></tr> 
  
  <tr><td>
	<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
          <tr>
            <td colspan="3" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Next Round </div></td>
          </tr>
          <tr>
            <td class="ProText"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                <tr>
                  <td class="gSGSectionColumnHeadings" width="7%">&nbsp;pos</td>
                  <td width="31%" align="right" class="gSGSectionColumnHeadings">team</td>
                  <td width="15%" align="center" class="gSGSectionColumnHeadings">result</td>
                  <td class="gSGSectionColumnHeadings" width="31%">team</td>
                  <td class="gSGSectionColumnHeadings" width="7%">pos</td>
                </tr>
                <!-- BEGIN next_round -->
                <tr class="{NEXT_ROUND_TR_CLASS}">
                  <td width="7%">&nbsp;({HOME_POS})</td>
                  <td width="31%" align="right" class="{HOME_TEAM_CLASS}"><a href="/fmol/page/info/club_info.php?team_id={HOME_PRIMARY_TEAM_ID}">{HOME_TEAM}</a></td>
                  <td width="15%" align="center">{HOME_SCORE} - {AWAY_SCORE} </td>
                  <td width="31%" class="{AWAY_TEAM_CLASS}"><a href="/fmol/page/info/club_info.php?team_id={AWAY_PRIMARY_TEAM_ID}">{AWAY_TEAM}</a></td>
                  <td width="7%">({AWAY_POS})</td>
                </tr>
                <!-- END next_round -->
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

	
