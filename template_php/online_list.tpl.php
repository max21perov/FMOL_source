

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr><td height="2">{SPACE}</td></tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
		<form name="mail_form" method="post" action="/fmol/page/mail/handle_mail.php" >
            <tr>
              <td colspan="3" align="center" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Managers Currently Online: {ONLINE_COUNT}</div></td>
            </tr>
            <tr>
              <td class="ProText"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td class="gSGSectionColumnHeadings">&nbsp;User Name</td>
                    <td class="gSGSectionColumnHeadings">Team Name</td>
                    <td class="gSGSectionColumnHeadings">Message</td>
                    <td class="gSGSectionColumnHeadings">Challenge</td>
                    <td class="gSGSectionColumnHeadings">Country</td>
                    <td class="gSGSectionColumnHeadings">Sel</td>
                  </tr>
                  <!-- BEGIN online -->
				  
                  <tr class="{ONLINE_TR_CLASS}" >
                    <td class="BlackText"><a href="/fmol/page/info/club_info.php?team_id={TEAM_ID}" >&nbsp;{USER_NAME}</a></td>
                    <td class="BlackText"><a href="/fmol/page/info/club_info.php?team_id={TEAM_ID}" >{TEAM_NAME}</a></td>
                    <td class="BlackText"><a href="/fmol/page/mail/send_mail.php?team_id={TEAM_ID}" >Message</a></td>
                    <td class="BlackText"><a href="/fmol/page/friendly/friendly_arrange.php?team_id={TEAM_ID}" >Challenge</a></td>
                    <td class="BlackText">{COUNTRY}</td>
                    <td class="BlackText"><input type="checkbox" name="checkbox[]" value="{TEAM_ID}" /></td>
                  </tr>
				  
                  <!-- END online -->
              </table></td>
            </tr>
			</form>
			
			<!-- buttom line -->
			<tr>
			  <td class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
			</tr>
		  
			<tr class="gSGRowOdd_input">
			  <td>{PAGER_TOOLBAR}</td>
			</tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

