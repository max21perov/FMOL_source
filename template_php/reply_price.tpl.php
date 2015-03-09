<script language="javascript" type="text/javascript">
function OnGivePriceConfirm(formObj) 
{
	// reply price must be bigger than the given price 
	var have_given_price = formObj.elements["have_given_price"].value;
	var given_price = formObj.elements["given_price"].value;
	var reply_price = formObj.elements["reply_price"].value;

	if (have_given_price !=  "0") {
		if (parseInt(reply_price) <= parseInt(given_price)) {
			alert("Your reply price must bigger than the given price of the buyer!");
			return false;
		}
	}

	return true;
}


function pricePercentChange(formObj, control) {  
	var worth = formObj.elements["worth"].value;
	var percent = control.value;
	var new_price = worth * percent;  
	new_price = parseInt(new_price); 
	formObj.elements["reply_price"].value = new_price;
}


</script>

<form name="opeForm" method="post" action="/fmol/page/transfer/handle_transfer.php?myaction=replyPriceByMail" onSubmit="javascript:return OnGivePriceConfirm(this)">
<input type="hidden" name="mail_id" value="{MAIL_ID}" />
<input type="hidden" name="subject" value="{SUBJECT}" />
<input type="hidden" name="player_id" value="{PLAYER_ID}" />
<input type="hidden" name="player_name" value="{PLAYER_NAME}" />
<input type="hidden" name="apply_team_id" value="{APPLY_TEAM_ID}" />
<input type="hidden" name="worth" value="{WORTH}" />
<input type="hidden" name="given_price" value="{GIVEN_PRICE}" />
<input type="hidden" name="have_given_price" value="{HAVE_GIVEN_PRICE}" />
{SPACE}
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
         <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Transfer confirm</div></td>
      </tr>
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" id="give_price_table">
          <tr>
            <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
          <tr class="gSGRowOdd">
            <td width="200">&nbsp;player name </td>
            <td>{PLAYER_NAME}</td>
          </tr>
          <tr>
            <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;worth</td>
            <td>{WORTH}</td>
          </tr>
          <tr>
            <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;the given price of buyer</td>
            <td>{GIVEN_PRICE}</td>
          </tr>
          <tr>
            <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;input your reply price</td>
            <td><select name="price_percent" onChange="pricePercentChange(this.form, this)">
                <option value="1.2" selected>120%</option>
                <option value="1.3">130%</option>
                <option value="1.4">140%</option>
                <option value="1.5">150%</option>
                <option value="1.6">160%</option>
                <option value="1.7">170%</option>
                <option value="1.8">180%</option>
                <option value="1.9">190%</option>
                <option value="2.0">200%</option>
              </select>
      * {WORTH} =
      <input type="text" class="inputField" name="reply_price" value="{REPLY_PRICE}" readonly="true" style="width:90px"/></td>
          </tr>
        </table></td>
      </tr>
	  <tr><td class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td></tr>
	  <tr><td class="gSGRowOdd"></td></tr>
      <tr class="gSGRowOdd">
        <td align="center" valign="bottom" height="22" class="toolBar" ><input type="submit" name="give_price" class="button" value="submit" style="width:100px" /></td>
      </tr>
    </table></td>
  </tr>
</table>

</form>