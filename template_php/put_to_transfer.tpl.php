<script language="javascript" type="text/javascript">
function startPricePercentChange(formObj, control) {
	var worth = formObj.elements["worth"].value;
	var percent = control.value;
	var new_price = parseInt(worth * percent);
	formObj.elements["price"].value = new_price;
}

function putToTransferSubmit(formObj) {
	var transfer_flag = formObj.elements["transfer_flag"].value;
	if (transfer_flag != "0") {
		alert("In this season, the player has been put to transfer market more the one time, \nso you can not put him to transfer again!");
		return false;
	}
	
	return true;
}
</script>

{SPACE}

<form name="opeForm" method="post" action="/fmol/page/transfer/handle_transfer.php?myaction=putToTransfer" onSubmit="return putToTransferSubmit(this)" >
<input type="hidden" name="player_id" value="{PLAYER_ID}" />
<input type="hidden" name="team_id" value="{TEAM_ID}" />
<input type="hidden" name="transfer_flag" value="{TRANSFER_FLAG}" />
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
         <td colspan="3" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Transfer confirm</div></td>
      </tr>
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr class="gSGRowOdd">
            <td width="150">&nbsp;team name</td>
            <td>{TEAM_NAME}</td>
          </tr>
		  <tr><td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td></tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;player name </td>
            <td>{PLAYER_NAME}</td>
          </tr>
		  <tr><td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td></tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;worth</td> 
            <td>{WORTH} <input type="hidden" name="worth" value="{WORTH}" /></td>
          </tr>
		  <tr><td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td></tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;price</td>
            <td>
			  <select name="start_price_percent" onChange="startPricePercentChange(this.form, this)">
			    <option value="0.4" selected>40%</option>
				<option value="0.8">80%</option>
			  </select> 
			  * {WORTH} = <input type="text" class="inputField" name="price" value="{PRICE}" readonly="true" style="width:90px"/>
			</td>
          </tr>
        </table></td>
      </tr>
	  <tr><td class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td></tr>
	   <tr><td height="10" class="gSGRowOdd"></td></tr>
      <tr class="gSGRowOdd">
        <td align="center"><input type="submit" name="put_to_transfer" class="button" value="submit" style="width:100px" /></td>
      </tr>
    </table></td>
  </tr>
</table>

</form>