
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
<form name="board_form" method="post" >
	<input type="hidden" name="action_str" value="{ACTION_STR}" />
	
	
    <tr><td height="2">{SPACE}</td></tr>
	
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Match Report </div></td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF">
				<div id="report" style="OVERFLOW:auto; height:460px; width:100%; text-align:left;">
				<table width="95%" border="0" cellpadding="0" cellspacing="0" >
                 
					
					
					<!-- BEGIN match_report -->
					<tr>
							<td class="cBBottom2" colspan="5"><img height="1" src="/fmol/images/blank.gif"></td>
					  </tr>
                    <tr class="gSGRowOdd">
                      <td align="center" width="100" class="gSGRowOrange">&nbsp;{MINUTE}</td>
                      <td >&nbsp;</td>
					  <td align="center" width="100">&nbsp;</td>
                    </tr>
					
					<!-- BEGIN match_report_comment -->
					<tr>
							<td class="cBBottom2" colspan="5"><img height="1" src="/fmol/images/blank.gif"></td>
					  </tr>
					<tr>
                      <td align="center" width="100"></td>
                      <td >{COMMENT_CONTENT}</td>
					  <td align="center" width="100"></td>
					  
                    </tr>
					<!-- END match_report_comment -->
					
                    <!-- END match_report -->
                    
                </table>
				</div>
				</td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
	
	
	
	
  </form>
</table>


