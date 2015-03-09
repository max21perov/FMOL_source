<script type="text/javascript" language="javascript">
// "formObj" is the form in the mail content

function acceptThePrice(formObj) {
	formObj.action = "/fmol/page/transfer/handle_transfer.php?myaction=acceptThePriceByMail";
	formObj.submit();
}

function giveAnotherPrice(formObj) {
	formObj.action = "/fmol/page/transfer/handle_transfer.php?myaction=giveAnotherPriceByMail";
	formObj.submit();
}

function declineThePrice(formObj) {
	formObj.action = "/fmol/page/transfer/handle_transfer.php?myaction=declineThePriceByMail";
	formObj.submit();	
}
</script>


<table width="100%"  border="0" cellspacing="2" cellpadding="2">

<input type="hidden" name="apply_team_id" value="{APPLY_TEAM_ID}" />
<input type="hidden" name="player_id" value="{PLAYER_ID}" />
<input type="hidden" name="player_name" value="{PLAYER_NAME}" />
<input type="hidden" name="worth" value="{WORTH}" />
<input type="hidden" name="price" value="{PRICE}" />
<input type="hidden" name="have_given_price" value="1" />

  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="BlackText"><a target="_parent" href="/fmol/page/info/club_info.php?team_id={APPLY_TEAM_ID}">{APPLY_TEAM_NAME}</a> give a price of your player: <a target="_parent" href="/fmol/page/players/player_info.php?player_id={PLAYER_ID}">{PLAYER_NAME}</a>.</span><br>
        </td>
      </tr>
      <tr>
        <td class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
      </tr>
      <tr>
        <td><span class="BlackText">He gave a price at: {PRICE}.</span><br>
        </td>
      </tr>
      <tr>
        <td class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
      </tr>
      <tr>
        <td> You can click the "accept" button to accept the price given by the applicant; </td>
      </tr>
      <tr>
        <td class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
      </tr>
      <tr>
        <td> You can click the "give another price" button to give another price to the applicant; </td>
      </tr>
      <tr>
        <td class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
      </tr>
      <tr>
        <td> you can click the "decline" button to decline to accept. </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><input name="accept_the_price" type="button" class="button" id="accept" onClick="acceptThePrice(this.form)" value="accept">
        <input name="give_another_price" type="button" class="button" id="give_another_price" onClick="giveAnotherPrice(this.form)" value="give another price">
            <input type="button" name="decline_the_price" value="decline" onClick="declineThePrice(this.form)"class="button">
        </td>
      </tr>
    </table></td>
  </tr>

  
</table>


