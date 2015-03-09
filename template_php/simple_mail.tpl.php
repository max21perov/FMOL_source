<script type="text/javascript" language="javascript">

function clickRow(control, mail_id) 
{
	document.location.href = "/fmol/page/mail/mail.php?mail_id=" + mail_id;
}

</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr><td height="2">{SPACE}</td></tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td colspan="3" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;News </div></td>
            </tr>
            <tr>
              <td class="gSGRowOdd" ><div class="FixWidth_simple_mail"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td width="30" class="gSGSectionColumnHeadings">&nbsp;</td>
                    <td width="20%" class="gSGSectionColumnHeadings">From</td>
                    <td width="49%" class="gSGSectionColumnHeadings">Subject</td>
                    <td width="26%" class="gSGSectionColumnHeadings">Date</td>
                  </tr>
				  
                  <!-- BEGIN mail -->
				  <tr style="display:{MAIL_LIST_SEPARATOR}">
				    <td colspan="8" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
                  <tr class="{MAIL_TR_CLASS}" style="cursor:hand" title="to the mail box"  onClick="clickRow(this, {MAIL_ID})">
                    <td class="BlackText">&nbsp;<img src="/fmol/images/{STATUS_IMG}" >&nbsp;</td>
                    <td class="BlackText" title="{FULL_FROM_NAME}">{SHORT_FROM_NAME}</td>
                    <td class="BlackText" title="{FULL_SUBJECT}">{SHORT_SUBJECT}</td>
                    <td class="BlackText">{DATE}</td>
                  </tr>
				  
                  <!-- END mail -->
				  
              </table></div></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
