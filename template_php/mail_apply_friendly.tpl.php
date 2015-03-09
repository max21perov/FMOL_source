<script type="text/javascript" language="javascript">
// "formObj" is the form in the mail content

function acceptRspFriendly(formObj) {
	formObj.action = "/fmol/page/friendly/handle_friendly.php?myaction=acceptRspFriendlyByMail";
	formObj.submit();
}

function declineRspFriendly(formObj) {
	formObj.action = "/fmol/page/friendly/handle_friendly.php?myaction=declineRspFriendlyByMail";
	formObj.submit();	
}
</script>

{SPACE}
<input type="hidden" name="friendly_id" value="{FRIENDLY_ID}" />
<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="BlackText"><a target="_parent" href="/fmol/page/info/club_info.php?team_id={FROM_ID}">{FROM_NAME}</a> invite you to have a friendly match.</span><br>
        </td>
      </tr>
      <tr>
        <td class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
      </tr>
      <tr>
        <td> You can click the "submit" button to accept the ivitation; </td>
      </tr>
      <tr>
        <td class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
      </tr>
      <tr>
        <td> you can click the "decline" button to decline the ivitation. </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><input type="button" name="accept_rsp_friendly" value="accept" onClick="acceptRspFriendly(this.form)" class="button">
            <input type="button" name="decline_rsp_friendly" value="decline" onClick="declineRspFriendly(this.form)"class="button">
        </td>
      </tr>
    </table></td>
  </tr>
</table>

