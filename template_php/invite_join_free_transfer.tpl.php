<script language="javascript" type="text/javascript">
function OnGivePriceConfirm(control) 
{
	return true;
}



</script>

<form name="opeForm" method="post" action="/fmol/page/transfer/handle_free_transfer.php?myaction=inviteJoinFreeTransfer" onSubmit="javascript:return OnGivePriceConfirm(this)">
	<input type="hidden" name="player_id" value="{PLAYER_ID}" />
	<input type="hidden" name="player_name" value="{PLAYER_NAME}" />
	<input type="hidden" name="worth" value="{WORTH}" />
	<input type="hidden" name="return_page_url" value="{RETURN_PAGE_URL}">
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
            <td>&nbsp;Contract Salary&nbsp;({SALARY_PERCENT})</td>
            <td>{FULL_SALARY} * {SALARY_PERCENT} = {CONTRACT_SALARY} &nbsp;m</td>
          </tr>
        </table></td>
      </tr>
	  <tr><td class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td></tr>
	  <tr><td class="gSGRowOdd"></td></tr>
      <tr class="gSGRowOdd">
        <td align="center" valign="bottom" height="22" class="toolBar" ><input type="submit" name="ask_join" class="button" value="submit" style="width:100px" /></td>
      </tr>
    </table></td>
  </tr>
</table>

</form>