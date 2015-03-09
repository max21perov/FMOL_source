<script language="javascript" type="text/javascript">
function OnGivePriceConfirm(control) 
{
	return true;
}


function pricePercentChange(formObj, control) {  
	var worth = formObj.elements["worth"].value;
	var percent = control.value;
	var new_price = worth * percent;  
	new_price = parseInt(new_price); 
	formObj.elements["price"].value = new_price;
}

function actionTypeChange(formObj, control) { 
	var value = control.value;
	var tableObj = document.getElementById("give_price_table");
	if (value == "0") {
		tableObj.style.display = "none";		
	}
	else {
		tableObj.style.display = "";	
	}
}


</script>

<form name="opeForm" method="post" action="/fmol/page/transfer/handle_transfer.php?myaction=askPrice" onSubmit="javascript:return OnGivePriceConfirm(this)">
<input type="hidden" name="player_id" value="{PLAYER_ID}" />
<input type="hidden" name="player_name" value="{PLAYER_NAME}" />
<input type="hidden" name="team_id" value="{TEAM_ID}" />
<input type="hidden" name="team_name" value="{TEAM_NAME}" />
<input type="hidden" name="owner_team_id" value="{OWNER_TEAM_ID}" />
<input type="hidden" name="worth" value="{WORTH}" />
{SPACE}
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
         <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Transfer confirm</div></td>
      </tr>
      <tr>
		 <td align="center" class="toolBar">&nbsp;Action Type &nbsp;&nbsp;
		 <select name="action_type" onChange="actionTypeChange(this.form, this)">
				<option value="0" >ask price</option>
				<option value="1" selected>give price</option>
			  </select>
		 </td>
      </tr>
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" id="give_price_table">
          <tr class="gSGRowOdd">
            <td width="200">&nbsp;team name</td>
            <td>{TEAM_NAME}</td>
          </tr>
          <tr>
            <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;player name </td>
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
            <td>&nbsp;input your expectant price</td>
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
      <input type="text" class="inputField" name="price" value="{PRICE}" readonly="true" style="width:90px"/></td>
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