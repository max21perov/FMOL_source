<script type="text/javascript" language="javascript">
// "formObj" is the form in the mail content

function acceptGivePrice(formObj) {
	formObj.action = "/fmol/page/transfer/handle_transfer.php?myaction=acceptGivePriceByMail";
	formObj.submit();
}

function declineGivePrice(formObj) {
	formObj.action = "/fmol/page/transfer/handle_transfer.php?myaction=declineGivePriceByMail";
	formObj.submit();	
}
</script>

<table width="100%"  border="0" cellspacing="2" cellpadding="2">

<input type="hidden" name="apply_team_id" value="{APPLY_TEAM_ID}" />
<input type="hidden" name="player_id" value="{PLAYER_ID}" />
<input type="hidden" name="player_name" value="{PLAYER_NAME}" />
<input type="hidden" name="worth" value="{WORTH}" />
<input type="hidden" name="have_given_price" value="0" />

  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="BlackText"><a target="_parent" href="/fmol/page/info/club_info.php?team_id={APPLY_TEAM_ID}">{APPLY_TEAM_NAME}</a> ask the price of your player: <a target="_parent" href="/fmol/page/players/player_info.php?player_id={PLAYER_ID}">{PLAYER_NAME}</a>.</span><br>
        </td>
      </tr>
      <tr>
        <td class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
      </tr>
      <tr>
        <td> You can click the "give price" button to give a price to the applicant; </td>
      </tr>
      <tr>
        <td class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
      </tr>
      <tr>
        <td> you can click the "decline" button to decline to reply. </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><input type="button" name="give_price" value="give price" onClick="acceptGivePrice(this.form)" class="button">
            <input type="button" name="decline_give_price" value="decline" onClick="declineGivePrice(this.form)"class="button">
        </td>
      </tr>
    </table></td>
  </tr>

</table>

