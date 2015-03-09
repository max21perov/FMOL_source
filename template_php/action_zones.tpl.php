<script language="javascript" type="text/javascript">
// display deferent PR
function change_PR_display(selectObj, formObj)
{
	var PT_type = selectObj.value; 
	var optionsArr = selectObj.options;   
 	
	var agt = navigator.userAgent.toLowerCase();
	var is_ie = (agt.indexOf("msie") != -1);
	var is_ie5 = (agt.indexOf("msie 5") != -1);
	var is_opera = (agt.indexOf("opera") != -1);
	var is_mac = (agt.indexOf("mac") != -1);
	var is_gecko = (agt.indexOf("gecko") != -1);
	var is_safari = (agt.indexOf("safari") != -1);
	var all_elements = (is_ie || is_ie5) ? document.all : document.getElementsByTagName( "*" );
	
	var len = all_elements.length;  
	for (var i=0; i<len; ++i) { 
		if (!(all_elements[i].id) || all_elements[i].id == "")
			continue;
			
		if (all_elements[i].id.substr(0, PT_type.length) == PT_type) {
			all_elements[i].style.display = "block";
		}
		else {
			for (var j=0; j<optionsArr.length; ++j) {
				var o_value = optionsArr[j].value;
				
				if (o_value == PT_type) continue;
				
				if (all_elements[i].id.substr(0, o_value.length) == o_value) {
					all_elements[i].style.display = "none";
					break;
				}
			}
			
		}
	}
	
}

</script>


<table width="100%" border="0" cellpadding="0" cellspacing="0" >
<form name="board_form" method="post" >
	<input type="hidden" name="action_str" value="{ACTION_STR}" />
	
	
    <tr><td height="2">{SPACE}</td></tr>
	
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Action Zones <select onChange="change_PR_display(this, this.form)"><option value="FMB" selected>FMB</option><option value="LCR">LCR</option><option value="PROZ">PROZ</option></select></div> 
				</td>
              </tr>
              <tr>
                <td bgcolor="#CCCCCC"><table width="100%" border="0" cellpadding="2" cellspacing="2" >
                    <tr>
                      <td width="722" height="517" align="left" valign="top" background="/fmol/images/field_action_zones.gif" ><table  border="0" cellpadding="0" cellspacing="0" bordercolor="#FF9900">
                        <tr>
                          <td width="37" height="37">&nbsp;</td>
                          <td width="200">&nbsp;</td>
                          <td width="230">&nbsp;</td>
                          <td width="200">&nbsp;</td>
                          <td width="37">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="142">&nbsp;</td>
                          <td align="center" valign="bottom"><span id="PROZ_6" style="display:none; "><span style="color:{HOME_PROZ_FONT_COLOR}">{HOME_PROZ_6}</span> - <span style="color:{AWAY_PROZ_FONT_COLOR}">{AWAY_PROZ_2}</span></span></td>
                          <td align="center" valign="bottom"><img style="display:none " src="/fmol/page/match/image_maker.php?match_type={MATCH_TYPE}&match_id={MATCH_ID}&PR_type=PR_L" alt=""  border="0" id="LCR_PR_L" ><span id="PROZ_3" style="display:none; "><span style="color:{HOME_PROZ_FONT_COLOR}">{HOME_PROZ_3}</span> - <span style="color:{AWAY_PROZ_FONT_COLOR}">{AWAY_PROZ_5}</span></span></td>
                          <td align="center" valign="bottom"><span id="PROZ_0" style="display:none; "><span style="color:{HOME_PROZ_FONT_COLOR}">{HOME_PROZ_0}</span> - <span style="color:{AWAY_PROZ_FONT_COLOR}">{AWAY_PROZ_8}</span></span></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="143">&nbsp;</td>
                          <td align="center" valign="bottom"><span id="PROZ_7" style="display:none; "><span style="color:{HOME_PROZ_FONT_COLOR}">{HOME_PROZ_7}</span> - <span style="color:{AWAY_PROZ_FONT_COLOR}">{AWAY_PROZ_1}</span></span></td>
                          <td align="center" valign="bottom"><img style="display:none " src="/fmol/page/match/image_maker.php?match_type={MATCH_TYPE}&match_id={MATCH_ID}&PR_type=PR_C" border="0" alt="" id="LCR_PR_C" ><span id="PROZ_4" style="display:none; "><span style="color:{HOME_PROZ_FONT_COLOR}">{HOME_PROZ_4}</span> - <span style="color:{AWAY_PROZ_FONT_COLOR}">{AWAY_PROZ_4}</span></span></td>
                          <td align="center" valign="bottom"><span id="PROZ_1" style="display:none; "><span style="color:{HOME_PROZ_FONT_COLOR}">{HOME_PROZ_1}</span> - <span style="color:{AWAY_PROZ_FONT_COLOR}">{AWAY_PROZ_7}</span></span></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="142">&nbsp;</td>
                          <td align="center" valign="bottom"><img src="/fmol/page/match/image_maker.php?match_type={MATCH_TYPE}&match_id={MATCH_ID}&PR_type=PR_B" border="0" alt="" id="FMB_PR_B" > <span id="PROZ_8" style="display:none; "><span style="color:{HOME_PROZ_FONT_COLOR}">{HOME_PROZ_8}</span> - <span style="color:{AWAY_PROZ_FONT_COLOR}">{AWAY_PROZ_0}</span></span></td>
                          <td align="center" valign="bottom"><img style="display:none " src="/fmol/page/match/image_maker.php?match_type={MATCH_TYPE}&match_id={MATCH_ID}&PR_type=PR_R" border="0" alt="" id="LCR_PR_R" ><img src="/fmol/page/match/image_maker.php?match_type={MATCH_TYPE}&match_id={MATCH_ID}&PR_type=PR_M" border="0" alt="" id="FMB_PR_M" ><span id="PROZ_5" style="display:none; "><span style="color:{HOME_PROZ_FONT_COLOR}">{HOME_PROZ_5}</span> - <span style="color:{AWAY_PROZ_FONT_COLOR}">{AWAY_PROZ_3}</span></span></td>
                          <td align="center" valign="bottom"><img src="/fmol/page/match/image_maker.php?match_type={MATCH_TYPE}&match_id={MATCH_ID}&PR_type=PR_F" border="0" alt="" id="FMB_PR_F" ><span id="PROZ_2" style="display:none; "><span style="color:{HOME_PROZ_FONT_COLOR}">{HOME_PROZ_2}</span> - <span style="color:{AWAY_PROZ_FONT_COLOR}">{AWAY_PROZ_6}</span></span></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="38">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
					
                    
                </table>
				</td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
	
	
	
	
	</form>
</table>


