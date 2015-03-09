<?php
    //$document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
	//define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" >
    <tr><td height="1">{SPACE}</td></tr>
	
	<tr>
	  <td><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#666666" >
		<tr>
		  <td class="infoStatsGrid" width="100">&nbsp;</td>
		  <td class="infoStatsGrid"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#F5F5dc" >
			<tr>
			  <td class="infoTitleStatsGrid">Club</td>
			  <td class="infoStatsGrid" title="(team id: {TEAM_ID})">&nbsp;{CLUB_NAME}</td>
			</tr>
			<tr>
			  <td class="infoTitleStatsGrid">Owner</td>
			  <td class="infoStatsGrid">&nbsp;{USER_NAME} </td>
			</tr>
			<tr>
			  <td class="infoTitleStatsGrid">Rank</td>
			  <td class="infoStatsGrid">&nbsp;({TEAM_POS}) </td>
			</tr>
			<tr>
			  <td class="infoTitleStatsGrid">Division</td>
			  <td class="infoStatsGrid">&nbsp;{DIV_NAME} </td>
			</tr>
			<tr>
			  <td class="infoTitleStatsGrid">Form</td>
			  <td class="infoStatsGrid">&nbsp;{FORM}</td> 
			  <!-- the latest 6 match result -->
			</tr>
			<tr>
			  <td class="infoTitleStatsGrid" width="15%">Awards</td>
			  <td class="infoStatsGrid" width="85%">&nbsp;{AWARDS} </td>
			  </tr>
		  </table></td>
		</tr>
	  </table></td>
	</tr>
	
	<tr><td height="2"></td></tr>
	
	<tr>
	 <td>
	   <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr class="gSGRowEven">
              <td width="90" colspan="{LAST_MATCH_COL_SPAN}">&nbsp;Last Match</td>
			  
              <!-- BEGIN last_match -->
              <td width="15%" align="center" class="OtherTeamText"><a href="/fmol/page/match/match_overview.php?match_id={SCHEDULE_ID}">{SELF_SCORE} - {OPPONENT_SCORE}</a></td>
              <td width="41%" class="OtherTeamText"><a href="/fmol/page/info/club_info.php?team_id={OPPONENT_PRIMARY_TEAM_ID}">{OPPONENT_NAME}</a></td>
              <td width="5%" align="center">{HOME_OR_AWAY}</td>
              <td width="*">&nbsp;{MATCH_TYPE}</td>
              <!-- END last_match -->
			  
            </tr>
            <tr class="gSGRowOdd">
              <td width="90" colspan="{NEXT_MATCH_COL_SPAN}">&nbsp;Next Match </td>
              <!-- BEGIN next_match -->
              <td width="15%" align="center">{SELF_SCORE} - {OPPONENT_SCORE}</td>
              <td width="41%" class="OtherTeamText"><a href="/fmol/page/info/club_info.php?team_id={OPPONENT_PRIMARY_TEAM_ID}">{OPPONENT_NAME}</a></td>
              <td width="5%" align="center">{HOME_OR_AWAY}</td>
              <td width="*">&nbsp;{MATCH_TYPE}</td>
              <!-- END next_match -->
            </tr>
          </table></td>
        </tr>
      </table>
	  
	 </td>
	</tr>
	
	<tr><td height="2"></td></tr>
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;{DIV_NAME} </div></td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td class="gSGSectionColumnHeadings" align="center" width="30">&nbsp;</td>
                    <td class="gSGSectionColumnHeadings" align="left">Pos</td>
                    <td class="gSGSectionColumnHeadings" align="center">Pld</td>
                    <td class="gSGSectionColumnHeadings" align="center">Won</td>
                    <td class="gSGSectionColumnHeadings" align="center">Drw</td>
                    <td class="gSGSectionColumnHeadings" align="center">Lst</td>
                    <td class="gSGSectionColumnHeadings" align="center">Gls</td>
                    <td class="gSGSectionColumnHeadings" align="center">Ags</td>
                    <td class="gSGSectionColumnHeadings" align="center">GD.</td>
                    <td class="gSGSectionColumnHeadings" align="center">Pts</td>
                  </tr>
                  <!-- BEGIN points -->
                  <tr class="gSGRowOdd">
                    <td align="center"><img name="UpNDown" src="/fmol/images/{RANK_IMG_LOCATION}" width="11" height="11" alt=""></td>
                    <td align="left">&nbsp;({TEAM_POS})</td>
                    <td align="center">{GAMES_PLAYED}</td>
                    <td align="center">{GAMES_WON}</td>
                    <td align="center">{GAMES_DRAWN}</td>
                    <td align="center">{GAMES_LOST}</td>
                    <td align="center">{GOALS_FOR}</td>
                    <td align="center">{GOALS_AGAINST}</td>
                    <td align="center">{GOALS_DIFFERENCE}</td>
                    <td align="center">{POINTS}</td>
                  </tr>
                  <!-- BEGIN points -->
				  
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
	</tr>
</table>
