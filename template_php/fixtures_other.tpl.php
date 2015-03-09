<script language="javascript" type="text/javascript">


function changeFixturesFilter(formObj) 
{
	var finish_filter = formObj.elements["finish_filter_select"].value; 
	var type_filter = formObj.elements["type_filter_select"].value;
	
	var team_id = formObj.elements["team_id"].value;
	document.location.href = "/fmol/page/fixtures/fixtures.php?team_id=" + team_id 
							+ "&finish_filter=" + finish_filter
							+ "&type_filter=" + type_filter;
}

</script>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">

<form name="save_form" method="post">
<input type="hidden" name="team_id" value="{TEAM_ID}" />

  <tr>
	<td height="1">{SPACE}</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9" >
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
		 <tr>
			<td colspan="7" class="gSGSectionTitle"><div align="right" class="gSGSectionTitle"> Filter&nbsp;:&nbsp;
					finish flag: <select name="finish_filter_select" onChange="changeFixturesFilter(this.form)">
					  <option value="10" {ALL_FF_FIXTURES_SELECTED}>All</option>
					  <option value="0" {FUTURE_FIXTURES_SELECTED}>Future</option>
					  <option value="1" {FINISH_FIXTURES_SELECTED}>Finish</option>
					</select>
					&nbsp;&nbsp;match type: <select name="type_filter_select" onChange="changeFixturesFilter(this.form)">
                      <option value="0" {ALL_FIXTURES_SELECTED}>All</option>
                      <option value="1" {LEAGUE_FIXTURES_SELECTED}>League</option>
                      <option value="2" {FRIENDLY_FIXTURES_SELECTED}>Friendly</option>
                    </select> 
					&nbsp;</div></td>
		  </tr>
		  
            <tr>
              <td class="gSGSectionColumnHeadings" width="35%">&nbsp;opponent.name</td>
              <td class="gSGSectionColumnHeadings" width="10%">H/A</td>
              <td class="gSGSectionColumnHeadings" width="15%">date</td>
              <td class="gSGSectionColumnHeadings" width="10%">time</td>
              <td class="gSGSectionColumnHeadings" width="*">matchtype</td>
			  <td class="gSGSectionColumnHeadings" width="15%" align="center">result</td>
            </tr>
            <!-- BEGIN fixtures -->
            <tr class="{FIXTURES_TR_CLASS}">
              <td class="OtherTeamText">&nbsp;<a href="/fmol/page/info/club_info.php?team_id={OPPONENT_PRIMARY_TEAM_ID}">{OPPONENT_NAME}</a></td>
              <td>{HOME_OR_AWAY}</td>
              <td>{MATCH_DATE}</td>
              <td>{MATCH_TIME}</td>
              <td>{MATCH_TYPE_STR} &nbsp;</td>
			  <td class="OtherTeamText" align="center">
			  	<a href="/fmol/page/match/match_hl_report.php?match_type={MATCH_TYPE}&match_id={MATCH_ID}" style="display:{HAVE_PLEYED_DISPLAY} ">{SELF_SCORE} - {OPPONENT_SCORE}</a>
				<span style="display:{NOT_PLAYED_DISPLAY} ">N/A</span>
			  </td>
            </tr>
            <!-- END fixtures -->
			
			
        </table></td>
      </tr>
    </table></td>
  </tr>

</form>

</table>
