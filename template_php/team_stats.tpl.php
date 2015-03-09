
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
<form name="board_form" method="post" >
	<input type="hidden" name="action_str" value="{ACTION_STR}" />
	
	
    <tr><td height="2">{SPACE}</td></tr>
	
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Team Stats <span class="WhiteText"><a href="/fmol/page/info/club_info.php?team_id={PRIMARY_TEAM_ID}">{TEAM_NAME}</a></span></div> </td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td class="gSGSectionColumnHeadings" title="cloth number">No.</td>						
                      <td class="gSGSectionColumnHeadings">C</td>
					  <td class="gSGSectionColumnHeadings">Name</td>
                      <td class="gSGSectionColumnHeadings">Inf.</td>
                      <td class="gSGSectionColumnHeadings" title="unsaves">Usv</td>
                      <td class="gSGSectionColumnHeadings" title="saves">Sv</td>
                      <td class="gSGSectionColumnHeadings" title="holds">Hld</td>
                      <td class="gSGSectionColumnHeadings" title="looses">Los</td>
                      <td class="gSGSectionColumnHeadings" title="tipouts">Tipo</td>
                      <td class="gSGSectionColumnHeadings" title="rsomad">Rsom</td>
                      <td class="gSGSectionColumnHeadings" title="rsofal">Rsof</td>
                      <td class="gSGSectionColumnHeadings" title="intmad">Intm</td>
                      <td class="gSGSectionColumnHeadings" title="intfal">Intf</td>
                      <td class="gSGSectionColumnHeadings" title="misses">Mis</td>
                      <td class="gSGSectionColumnHeadings" title="asts">Ast</td>
                      <td class="gSGSectionColumnHeadings" title="condition">Con</td>
                      <td class="gSGSectionColumnHeadings" title="rating">Rat</td>
                      <td class="gSGSectionColumnHeadings" title="gols">Gls</td>
                    </tr>
					
					
					<!-- BEGIN gk_team_stats -->
                    <tr class="gSGRowOdd">
                      <td>&nbsp;{CLOTH_NUMBER}</td>
                      <td >{CAPTION}</td>
					  <td ><span class="BlackText"><a style="display:{PLAYER_NAME_LINE_DISPLAY} " href="/fmol/page/players/player_info.php?player_id={PRIMARY_PLAYER_ID}">{PLAYER_NAME}</a><span style="display:{PLAYER_NAME_ONLY_DISPLAY} ">{PLAYER_NAME}</span></span></td>
					  <td >{PLAYER_INF}</td>
					  <td >{UNSAVES}</td>
					  <td >{SAVES}</td>
					  <td >{HOLDS}</td>
					  <td >{LOOSES}</td>
					  <td >{TIP_OUTS}</td>
					  <td >{RSO_MADE}</td>
					  <td >{RSO_FALSE}</td>
					  <td >{INT_MADE}</td>
					  <td >{INT_FALSE}</td>
					  <td >{MISSES}</td>
					  <td >{ASSISTS}</td>
					  <td >{CONDITION}</td>
					  <td >{RATING}</td>
					  <td >{GOALS}</td>
                    </tr>
					<tr>
							<td class="cBBottom2" colspan="25"><img height="1" src="/fmol/images/blank.gif"></td>
					  </tr>
                    <!-- END gk_team_stats -->
                    
                </table>
				</td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF">&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td class="gSGSectionColumnHeadings" title="cloth number">No.</td>
                    <td class="gSGSectionColumnHeadings">C</td>
                    <td class="gSGSectionColumnHeadings">Name</td>
                    <td class="gSGSectionColumnHeadings">Inf.</td>
                    <td class="gSGSectionColumnHeadings" title="passesatm">Pas</td>
                    <td class="gSGSectionColumnHeadings" title="passesmad">Mad</td>
                    <td class="gSGSectionColumnHeadings" title="keypasses">Key</td>
                    <td class="gSGSectionColumnHeadings" title="tacklesatm">Tck</td>
                    <td class="gSGSectionColumnHeadings" title="tacklesmad">Mad</td>
                    <td class="gSGSectionColumnHeadings" title="keytackles">Key</td>
                    <td class="gSGSectionColumnHeadings" title="headersatm">Hea</td>
                    <td class="gSGSectionColumnHeadings" title="headersmad">Mad</td>
                    <td class="gSGSectionColumnHeadings" title="keyheaders">Key</td>
                    <td class="gSGSectionColumnHeadings" title="interceptions">Int</td>
                    <td class="gSGSectionColumnHeadings" title="runs">Run</td>
                    <td class="gSGSectionColumnHeadings" title="offs">Off</td>
                    <td class="gSGSectionColumnHeadings" title="fous">Fou</td>
                    <td class="gSGSectionColumnHeadings" title="flds">Fld</td>
                    <td class="gSGSectionColumnHeadings" title="asts">Ast</td>
                    <td class="gSGSectionColumnHeadings" title="shotsatm">Sht</td>
                    <td class="gSGSectionColumnHeadings" title="shotson">Sho</td>
                    <td class="gSGSectionColumnHeadings" title="condition">Con</td>
                    <td class="gSGSectionColumnHeadings" title="rating">Rat</td>
                    <td class="gSGSectionColumnHeadings" title="gols">Gls</td>
                  </tr>
                  <!-- BEGIN player_team_stats -->
                  <tr>
                    <td class="cBBottom2" colspan="25"><img height="1" src="/fmol/images/blank.gif"></td>
                  </tr>
                  <tr class="gSGRowOdd">
                    <td>&nbsp;{CLOTH_NUMBER}</td>
                    <td >{CAPTION}</td>
                    <td ><span class="BlackText"><a style="display:{PLAYER_NAME_LINE_DISPLAY} " href="/fmol/page/players/player_info.php?player_id={PRIMARY_PLAYER_ID}">{PLAYER_NAME}</a><span style="display:{PLAYER_NAME_ONLY_DISPLAY} ">{PLAYER_NAME}</span></span></td>
                    <td >{PLAYER_INF}</td>
                    <td >{PASSES_ATTEMPT}</td>
                    <td >{PASSES_MADE}</td>
                    <td >{KEY_PASSES}</td>
                    <td >{TACKLES_ATTEMPT}</td>
                    <td >{TACKLES_MADE}</td>
                    <td >{KEY_TACKLES}</td>
                    <td >{HEADERS_ATTEMPT}</td>
                    <td >{HEADERS_MADE}</td>
                    <td >{KEY_HEADERS}</td>
                    <td >{INTERCEPTION}</td>
                    <td >{RUNS}</td>
                    <td >{OFFSIDES}</td>
                    <td >{FOULS}</td>
                    <td >{FOULEDS}</td>
                    <td >{ASSISTS}</td>
                    <td >{SHOTS_ATTEMPT}</td>
                    <td >{SHOTS_ON}</td>
                    <td >{CONDITION}</td>
                    <td >{RATING}</td>
                    <td >{GOALS}</td>
                  </tr>
                  <!-- END player_team_stats -->
				  
                </table></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
	
	
	
	
	</form>
</table>


