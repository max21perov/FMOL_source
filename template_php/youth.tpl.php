

<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" >
    <tr><td height="1">{SPACE}</td></tr>
	
	  
	<tr>
	 <td>
	   <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle" colspan="5"><div class="gSGSectionTitle">&nbsp;Youth </div></td>
            </tr>
			
            <tr class="gSGRowOdd">
              <td width="180">&nbsp;Youth Training Level </td>
              
              <td width="*">&nbsp;{YOUTH_TRAINING_LEVEL}</td>
              
            </tr>
            <tr>
              <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
            </tr>
			
            <tr class="gSGRowOdd">
              <td>&nbsp;Cur Invest </td>
              
              <td width="*">&nbsp;{YOUTH_TRAINING_CUR_INVEST} / {YOUTH_TRAINING_NEXT_LEVEL}</td>
              
            </tr>
            <tr>
              <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
            </tr>
			
            <tr class="gSGRowOdd">
              <td width="260">&nbsp;Increase Invest(Max 10M per Season) </td>
              
              <td width="*"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  	<form name="increase_invest_form" action="/fmol/page/youth/handle_youth.php?myaction=increaseYouthTrainingInvest" method="post">
	
				  <input type="hidden" name="youth_training_level" value="{YOUTH_TRAINING_LEVEL}">
				  <input type="hidden" name="youth_training_cur_invest" value="{YOUTH_TRAINING_CUR_INVEST}">
				  <input type="hidden" name="return_page_url" value="{RETURN_PAGE_URL}">
				  
			      <tr><td>&nbsp;
			  	  <select style="width:100px " name="increase_num" >
                    <!-- BEGIN increase_num_select -->
                    <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
                    <!-- END increase_num_select -->
                  </select> 
				  <input type="submit" class="button" style="display:{INCREASE_DISPLAY} " value="increase" />
				  
				  </td></tr>
				  
				</form>
				  
				  </table>
			  	  </td>
              
            </tr>
            <tr>
              <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
            </tr>
			
            <tr class="gSGRowOdd">
              <td>&nbsp;New Player </td>
              
              <td width="*">
			  
			  <!-- BEGIN new_player -->
			  &nbsp;<a class="{NEW_PLAYER_CLASS}" href="/fmol/page/youth/youth.php?player_id={PLAYER_ID}" >{PLAYER_NAME}</a>
              <!-- END new_player -->
			  
			  </td>
            </tr>
          </table></td>
        </tr>
      </table>
	  
	 </td>
	</tr>
	
	<tr><td height="2"></td></tr>
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Player Ability </div></td>
            </tr>
            <tr class="gSGRowOdd">
              <td>{PLAYER_ABILITY_CONTENT}</td>
            </tr>
          </table></td>
        </tr>
      </table></td>
	</tr>
	
  	
  
</table>

