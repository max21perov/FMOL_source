

<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" >
    <tr><td height="1">{SPACE}</td></tr>
	<form name="save_form" action="/fmol/page/players/handle_players.php?myaction=saveExtendContract" method="post">
	
      <input type="hidden" name="player_id" value="{PLAYER_ID}">
      <input type="hidden" name="player_value" value="{PLAYER_VALUE}">
      <input type="hidden" name="return_page_url" value="{RETURN_PAGE_URL}">
	
	<tr><td height="2"></td></tr>
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Extend Agreement </div></td>
            </tr>
            <tr >
              <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
           
                <tr class="gSGRowOdd">
                  <td align="right">Player value:&nbsp;</td>
                  <td>{PLAYER_VALUE}&nbsp;m</td>
                  <td align="right">Current Salary:&nbsp;</td>
                  <td>{CUR_SALARY}&nbsp;m</td>
                </tr>
				
				<tr>
				  <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				</tr>
				
                <tr class="gSGRowOdd">
                  <td width="150" align="right">New Salary:&nbsp;</td>
                  <td>{NEW_SALARY}&nbsp;m</td>	  
                  <td width="150" align="right">Extend Seasons:&nbsp;</td>
                  <td>
					  <select style="width:125px " name="extend_seasons" >
						<!-- BEGIN extend_seasons_select -->
						<option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
						<!-- END extend_seasons_select -->
					  </select>
					  </td>
                </tr>
				
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
	</tr>
	
	<tr><td height="2"></td></tr>
  
	
	<tr>
	  <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>&nbsp;</td>
          <td height="30" style="width:100px "><input type="submit" name="save" value="save" class="button" style="width:100px " /> </td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
	
	
	<tr><td height="2"></td></tr>
	
	<tr>
	  <td>&nbsp;<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr class="gSGRowOdd">
    <td>&nbsp;{ATTENTION_STR} </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr class="gSGRowOdd">
    <td>&nbsp;<table width="100%"  border="1" cellspacing="0" cellpadding="0">
  <tr align="center">
    <td>age</td>
    <td>range%</td>
  </tr>
  <tr align="center">
    <td>18+</td>
    <td>20</td>
  </tr>
  <tr align="center">
    <td>21+</td>
    <td>10</td>
  </tr>
  <tr align="center">
    <td>26+</td>
    <td>0</td>
  </tr>
  <tr align="center">
    <td>31+</td>
    <td>-10</td>
  </tr>
</table>
</td>
  </tr>
</table>
</td>
	  </tr>
  
  </form>
  
</table>

