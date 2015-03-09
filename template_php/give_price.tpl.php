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

</script>

<form name="opeForm" method="post" action="/fmol/page/transfer/handle_transfer.php?myaction=givePrice" onSubmit="javascript:return OnGivePriceConfirm(this)">
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
         <td colspan="3" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Transfer confirm</div></td>
      </tr>
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
            <td>&nbsp;start price</td>
            <td>{START_PRICE} &nbsp;( {START_PRICE_PERCENT} % )</td>
          </tr>
          <tr>
            <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;input your expectant price</td>
            <td><select name="price_percent" onChange="pricePercentChange(this.form, this)">
                <option value="0.4" selected>40%</option>
                <option value="0.5">50%</option>
                <option value="0.6">60%</option>
                <option value="0.7">70%</option>
                <option value="0.8">80%</option>
                <option value="0.9">90%</option>
                <option value="1.0">100%</option>
                <option value="1.1">110%</option>
                <option value="1.2">120%</option>
                <option value="1.2">120%</option>
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
          <tr>
            <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
		  
          <tr class="gSGRowOdd">
            <td>&nbsp;Contract Seasons</td>
            <td><select style="width:60px " name="contract_seasons" >
              <!-- BEGIN contract_seasons_select -->
              <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
              <!-- END contract_seasons_select -->
            </select></td>
          </tr>
          <tr>
            <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
		  
          <tr class="gSGRowOdd">
            <td>&nbsp;Contract Salary</td>
            <td>{CONTRACT_SALARY}&nbsp;m</td>
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