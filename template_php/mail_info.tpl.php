<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css">
<style>
body {
	overflow:auto;
}
</style>
<script language="javascript">
function delMail(formObj) 
{
	if (confirm("Are you sure to delete this message?") == false ) return false;
	
	var mail_id = formObj.elements["mail_id"].value;
	formObj.action = "/fmol/page/mail/handle_mail.php?myaction=deleteMail&mail_id=" + mail_id;
	formObj.submit();
}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<form target="_parent" action="/fmol/page/mail/handle_mail.php?myaction=replyMail" name="opeFrom" method="post">
<input type="hidden" name="from_team_id" value="{FROM_TEAM_ID}" />
<input type="hidden" name="mail_id" value="{MAIL_ID}" />
<input type="hidden" name="subject" value='{MAIL_SUBJECT}' />
<!-- <input type="hidden" name="content" value='{MAIL_CONTENT}' /> -->
  <tr>
    <td class="gSGSectionTitle"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><div class="gSGSectionTitle" id="cur_mail_date_div">&nbsp;{CUR_MAIL_TIME}</div></td>
          <td><div class="gSGSectionTitle" align="right">&nbsp;
                  <input type="submit" class="button" value="reply" name="reply_mail" title="reply this message" style="width:60px ">
                  <input type="button" class="button" value="del" name="del_mail" onclick="delMail(this.form)" title="delete this message" style="width:60px ">
          </div></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td class="gSGSectionColumnHeadings">{MAIL_SUBJECT}</td>
  </tr>
  <tr>
    <td  height="5"></td>
  </tr>
  <tr>
    <td>{MAIL_CONTENT}</td>
  </tr>
  <tr>
    <td height="45"></td>
  </tr>
</form>
</table>
