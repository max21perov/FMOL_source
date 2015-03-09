<script language="javascript" type="text/javascript">
function OnClickConfirm(objForm, type)
{	
    switch (type) {
	case 1:
	    if (confirm("Are you sure to cancel this \"app\" friendly match?") == false ) return false;
	    break;
	case 2:
	    if (confirm("Are you sure to accept this \"rsp\" friendly match?") == false ) return false;
	    break;
	case 3:
	    if (confirm("Are you sure to cancel this \"wait\" friendly match?") == false ) return false;
	    break;
	default:
	    return false;
	}
    
    return true;
}

function changeFriendlyStatus(control) 
{
	var friendly_filter = control.value; 
	document.location.href = "/fmol/page/friendly/friendly_list.php?friendly_filter=" + friendly_filter;
}
</script>

<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
<tr><td height="1">{SPACE}</td></tr>

<tr><td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr class="TextPro">
        <td colspan="3" class="gSGSectionTitle"><div align="right" class="gSGSectionTitle"> Filter
                <select name="friendly_filter_select" onChange="changeFriendlyStatus(this)">
                  <option value="3" {ALL_FRIENDLY_SELECTED}>All</option>
                  <option value="2" {FIX_FRIENDLY_SELECTED}>Fixed</option>
                  <option value="0" {APP_FRIENDLY_SELECTED}>Apply for</option>
                  <option value="1" {RSP_FRIENDLY_SELECTED}>need respose</option>
                  <option value="10" {WAIT_FRIENDLY_SELECTED}>Wait in pool</option>
                </select>
        &nbsp;</div></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="gSGSectionColumnHeadings" width="6%">&nbsp;st.</td>
              <td class="gSGSectionColumnHeadings" width="20%">user</td>
              <td class="gSGSectionColumnHeadings" width="22%">team</td>
              <td class="gSGSectionColumnHeadings" width="8%">H/A</td>
              <td class="gSGSectionColumnHeadings" width="14%">date</td>
              <td class="gSGSectionColumnHeadings" width="5%">time</td>
              <td class="gSGSectionColumnHeadings" width="25%" align="right">action</td>
            </tr>
			
            <!-- BEGIN friendly -->
            {FRIENDLY_ENTIRY}
            <!-- END friendly -->
			
			
			<!-- buttom line -->
			<tr>
			  <td colspan="6" class=cBBottom><img height=1 src="/fmol/images/blank.gif"></td>
			</tr>
				
			<tr class="gSGRowOdd_input">
              <td colspan="7">{PAGER_TOOLBAR}</td>
            </tr>
			
            <tr class="BlackText" bgcolor="#CCCCCC">
              <td colspan="7">You can find a team, and click the &quot;challenge&quot; button on the right conner, to invite him to friendly . Or, you could held a friendly and wait for others to join, in &quot;Friendly pool &quot;.</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>  
  
  </td>
</tr>

  <tr><td height="2"></td></tr>

  <tr><td>

    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="3" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Details</div></td>
          </tr>
          <tr>
            <td  bgcolor="#FFFFFF">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>