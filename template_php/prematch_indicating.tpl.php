

<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" >
    <tr><td height="1">{SPACE}</td></tr>
	<form name="save_form" action="/fmol/page/tactics/handle_prematch_indicating.php?myaction=savePreMatchIndicating"  onSubmit="return before_prematch_indicating_save(this)" method="post">
	
      <input type="hidden" name="return_page_url" value="{RETURN_PAGE_URL}">
	  
	<tr>
	 <td>
	   <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle" colspan="5"><div class="gSGSectionTitle">&nbsp;Next Match Info </div></td>
            </tr>
			
            <tr class="gSGRowOdd">
              <td width="90" colspan="{NEXT_MATCH_COL_SPAN}">&nbsp;Next Match </td>
              <!-- BEGIN next_match -->
              <td width="15%" align="center">{SELF_SCORE} - {OPPONENT_SCORE}</td>
              <td width="41%" class="OtherTeamText"><a href="/fmol/page/info/club_info.php?team_id={OPPONENT_PRIMARY_TEAM_ID}">{OPPONENT_NAME}</a></td>
              <td width="5%" align="center">{HOME_OR_AWAY}</td>
              <td width="*">&nbsp;{MATCH_TYPE}</td>
              <!-- END next_match -->
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
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Opponent Tactics Indicating </div></td>
            </tr>
            <tr class="gSGRowOdd">
              <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="4">&nbsp;<input type="checkbox" name="is_indicating_in_use" {IS_INDICATING_IN_USE_CHECKED} onClick="whether_use_indicating()" /> Use Indicating</td>
                  </tr>
                <tr>
                  <td width="150" align="right">Amount of Forward&nbsp;</td>
                  <td>				  
					  <select style="width:125px " name="opp_F_num" >
						<!-- BEGIN opp_F_num_select -->
						<option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
						<!-- END opp_F_num_select -->
					  </select>
				  
                  <td width="150" align="right">Amount of Defender&nbsp;</td>
                  <td>
					  <select style="width:125px " name="opp_D_num" >
						<!-- BEGIN opp_D_num_select -->
						<option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
						<!-- END opp_D_num_select -->
					  </select>
					  
                </tr>
				
                <tr>
                  <td align="right">Use AMC&nbsp;</td>
                  <td>
					  <select style="width:125px " name="is_opp_AMC" >
						<!-- BEGIN is_opp_AMC_select -->
						<option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
						<!-- END is_opp_AMC_select -->
					  </select>
				  </td>
                  <td align="right">Use DMC</td>
                  <td>
					  <select style="width:125px " name="is_opp_DMC" >
						<!-- BEGIN is_opp_DMC_select -->
						<option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
						<!-- END is_opp_DMC_select -->
					  </select>
				  </td>
                </tr>
				
                <tr>
                  <td align="right">AD Mentality&nbsp;</td>
                  <td>
					  <select style="width:125px " name="opp_AD_mentality" >
						<!-- BEGIN opp_AD_mentality_select -->
						<option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
						<!-- END opp_AD_mentality_select -->
					  </select>
				  </td>
                  <td align="right">Tempo</td>
                  <td>
					  <select style="width:125px " name="opp_tempo" >
						<!-- BEGIN opp_tempo_select -->
						<option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
						<!-- END opp_tempo_select -->
					  </select>
				  </td>
                </tr>
				
				
                <tr>
                  <td align="right">Offside Trap&nbsp;</td>
                  <td>
					  <select style="width:125px " name="is_opp_OST" >
						<!-- BEGIN is_opp_OST_select -->
						<option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
						<!-- END is_opp_OST_select -->
					  </select>
				  </td>
                  <td align="right">Counter Attack</td>
                  <td>
					  <select style="width:125px " name="is_opp_CA" >
						<!-- BEGIN is_opp_CA_select -->
						<option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
						<!-- END is_opp_CA_select -->
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
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Spec Opponent </div></td>
              </tr>
              <tr class="gSGRowOdd">
                <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td colspan="4">&nbsp;
                          <input type="checkbox" name="is_spec_opp_in_use" {IS_SPEC_OPP_IN_USE_CHECKED} onClick="whether_use_spec_opponent()" />
                  Use Spec Opponent</td>
                    </tr>
                    <tr>
                      <td width="150" align="right">Danger Player&nbsp;</td>
                      <td><select style="width:125px " name="spec_opp_player_id" >
                          <!-- BEGIN spec_opp_player_id_select -->
                          <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
                          <!-- END spec_opp_player_id_select -->
                        </select>
                      </td>
                      <td width="150" align="right">&nbsp;</td>
                      <td>&nbsp;
                      </td>
                    </tr>
                    <tr>
                      <td align="right">Heavy Tackling&nbsp;</td>
                      <td><select style="width:125px " name="is_heavy_tackling" >
                          <!-- BEGIN is_heavy_tackling_select -->
                          <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
                          <!-- END is_heavy_tackling_select -->
                        </select>
                      </td>
                      <td align="right">Heavy Pressing</td>
                      <td><select style="width:125px " name="is_heavy_pressing" >
                          <!-- BEGIN is_heavy_pressing_select -->
                          <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
                          <!-- END is_heavy_pressing_select -->
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td align="right">&nbsp;</td>
                      <td>&nbsp;</td>
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
  
  </form>
  
</table>

<script type="text/javascript" language="javascript">
// before save
function before_prematch_indicating_save(form_obj)
{
	var disabled_or_not = false;
	
	form_obj.elements["opp_F_num"].disabled = disabled_or_not;
	form_obj.elements["opp_D_num"].disabled = disabled_or_not;
	form_obj.elements["is_opp_AMC"].disabled = disabled_or_not;
	form_obj.elements["is_opp_DMC"].disabled = disabled_or_not;
	form_obj.elements["opp_AD_mentality"].disabled = disabled_or_not;
	form_obj.elements["opp_tempo"].disabled = disabled_or_not;
	form_obj.elements["is_opp_OST"].disabled = disabled_or_not;
	form_obj.elements["is_opp_CA"].disabled = disabled_or_not;
	
	form_obj.elements["spec_opp_player_id"].disabled = disabled_or_not;
	form_obj.elements["is_heavy_tackling"].disabled = disabled_or_not;
	form_obj.elements["is_heavy_pressing"].disabled = disabled_or_not;
	
	return true;
}



// check whether use indicating
function whether_use_indicating()
{
	var form_obj = document.forms["save_form"]; 
	var is_indicating_in_use = form_obj.elements["is_indicating_in_use"].checked;
	 
	var disabled_or_not = false;
	if (is_indicating_in_use)
		disabled_or_not = true;

	form_obj.elements["opp_F_num"].disabled = disabled_or_not;
	form_obj.elements["opp_D_num"].disabled = disabled_or_not;
	form_obj.elements["is_opp_AMC"].disabled = disabled_or_not;
	form_obj.elements["is_opp_DMC"].disabled = disabled_or_not;
	form_obj.elements["opp_AD_mentality"].disabled = disabled_or_not;
	form_obj.elements["opp_tempo"].disabled = disabled_or_not;
	form_obj.elements["is_opp_OST"].disabled = disabled_or_not;
	form_obj.elements["is_opp_CA"].disabled = disabled_or_not;
	
}

// check whether use sepc opponent
function whether_use_spec_opponent()
{
	var form_obj = document.forms["save_form"];
	var is_spec_opp_in_use = form_obj.elements["is_spec_opp_in_use"].checked;
	
	var disabled_or_not = false;
	if (is_spec_opp_in_use)
		disabled_or_not = true;
	
	form_obj.elements["spec_opp_player_id"].disabled = disabled_or_not;
	form_obj.elements["is_heavy_tackling"].disabled = disabled_or_not;
	form_obj.elements["is_heavy_pressing"].disabled = disabled_or_not;
	
}
 
// page onload function
function indicating_page_onload()
{
	whether_use_indicating();
	
	whether_use_spec_opponent();
}


// page onload
indicating_page_onload();

</script>
