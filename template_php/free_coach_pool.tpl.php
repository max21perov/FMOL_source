<script type="text/javascript" language="javascript">


var curRow = null;
// click one row
function clickRow(control) 
{
	if (curRow != null)
		curRow.className = "gSGRowOdd";
	
	curRow = control;
	curRow.className = "gSGRowEven";
}


// 
function signCoach(control, coach_id)
{	
	if (confirm("Are you sure to sign this coach?") == false ) return false;
	
	var frm = document.forms["free_coach_pool_form"];  

	frm.action = "/fmol/page/transfer/handle_coach.php?myaction=signCoach&coach_id=" + coach_id;
	frm.submit();
}

</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr><td height="1">{SPACE}</td></tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
		  <form name="free_coach_pool_form" method="post" action="/fmol/page/transfer/handle_transfer.php" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Free Coach Pool </div></td>
			  <td class="gSGSectionTitle">
				
				<div align="right" class="gSGSectionTitle">				  </div>
				
				</td>
            </tr>
            <tr>
              <td class="ProText" colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td class="gSGSectionColumnHeadings" nowrap title="Player Name">&nbsp;Name</td>
                    <td class="gSGSectionColumnHeadings" >Age</td>
                    <td class="gSGSectionColumnHeadings" >Salary</td>
                    <td class="gSGSectionColumnHeadings">bids</td>
                    <td class="gSGSectionColumnHeadings">&nbsp;</td>
                  </tr>
                  <!-- BEGIN free_coach_pool -->
				  <tr style="display:{LIST_SEPARATOR}">
				    <td colspan="8" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
                  <tr class="{TRAN_TR_CLASS}"  onClick="clickRow(this)" >
                    <td class="BlackText"><a href="/fmol/page/staff/coach_info.php?coach_id={COACH_ID}" >&nbsp;{COACH_NAME}</a></td>
					<td class="BlackText">{AGE}</td>
                    <td class="BlackText">{SALARY}</td>
                    <td class="BlackText">{BIDS}</td>
                    <td class="BlackText"><span onClick="javascript:signCoach(this, {COACH_ID})" style="cursor:hand ">sign</span></td>
                  </tr>
                  <!-- END free_coach_pool -->
              </table></td>
            </tr>
			</form>
			
			<!-- buttom line -->
			<tr>
			  <td colspan="2" class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
			</tr>
		  
			<tr class="gSGRowOdd_input">
			  <td colspan="2">{PAGER_TOOLBAR}</td>
			</tr>
			
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

