<script language="javascript" type="text/javascript">
function OnClickConfirm(control)
{
	if (confirm("Are you sure to join this friendly match?") == false ) return false;

    return true;
}
</script>


<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
<tr><td height="1">{SPACE}</td></tr>
<tr><td>

	<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="gSGSectionColumnHeadings" width="19%">&nbsp;user</td>
                  <td class="gSGSectionColumnHeadings" width="25%">team</td>
                  <td class="gSGSectionColumnHeadings" width="10%">H/A</td>
                  <td class="gSGSectionColumnHeadings" width="15%">date</td>
                  <td class="gSGSectionColumnHeadings" width="10%">time</td>
                  <td class="gSGSectionColumnHeadings" width="21%">action</td>
                </tr>
				
                <!-- BEGIN friendly_pool -->
				
				 <tr>
					<td colspan="6" class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
			  
                <form method="post" action="/fmol/page/friendly/handle_friendly.php?myaction=joinFriendlyPool" onSubmit="javascript:return OnClickConfirm(this)">
                  <tr class="{FRIENDLY_POOL_TR_CLASS}">
                    <td>&nbsp;{USER_NAME}</td>
                    <td class="{TEAM_CLASS}"><a href="/fmol/page/info/club_info.php?team_id={PRIMARY_TEAM_ID}">{TEAM_NAME}</a></td>
                    <td>{HOME_OR_AWAY}</td>
                    <td>{MATCH_DATE}</td>
                    <td>{MATCH_TIME}</td>
                    <td><input type="hidden" name="friendly_pool_id" value="{FRIENDLY_POOL_ID}">
                        <input type="hidden" name="owner_team_id" value="{OWNER_TEAM_ID}">
                        <input type="hidden" name="home_or_away" value="{HOME_OR_AWAY}">
                        <input type="hidden" name="o_time" value="{O_TIME}">
                        <input name="join_friendly_pool" type="submit" class="button" value="join">
                        <input name="details" type="button" class="button" value="details"></td>
                  </tr>
                </form>
                <!-- END friendly_pool -->
				
                <!-- buttom line -->
                <tr>
                  <td colspan="6" class=cBBottom><img height=1 src="/fmol/images/blank.gif"></td>
                </tr>
				
                <tr>
                  <td colspan="6" class="gSGRowOdd_input">{PAGER_TOOLBAR}</td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
</tr>

<tr><td height="2"></td></tr>

<tr><td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Details</div></td>
      </tr>
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

	</td>
</tr>
</table>
