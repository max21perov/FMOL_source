
<script language="javascript" type="text/javascript">

function viewMailDetail(control, mail_id)
{	
	if (document.getElementById("mail_detail_frame").contentDocument) {
		var frm = document.getElementById("mail_detail_frame");
		frm.src = "/fmol/page/mail/mail_info_maker.php?mail_id=" + mail_id;
	}
	else {
		var frm = document.frames["mail_detail_frame"];
		frm.location.href = "/fmol/page/mail/mail_info_maker.php?mail_id=" + mail_id;
	}
}

function deleteMail(control, mail_id)
{	
	if (confirm("Are you sure to delete this message?") == false ) return false;
	
	var frm = document.forms["mail_form"];
	var next_page = frm.elements["next_page"].value;   
	
	frm.action = "/fmol/page/mail/handle_mail.php?myaction=deleteMail&mail_id=" + mail_id 
					+ "&next_page=" + next_page;
	frm.submit();
}

function deleteAllMessages() {
	var arr = document.getElementsByName("checkbox[]"); 
	if (arr.length == 0) {
		alert("there is not any messages you can delete!");
		return;
	}
	
	if (confirm("Are you sure to delete all the messages?") == false ) return false;
	
	var frm = document.forms["mail_form"];
	frm.action = "/fmol/page/mail/handle_mail.php?myaction=deleteAllMails";
	frm.submit();
}

function deleteSelectedMessages() {
	var frm = document.forms["mail_form"];

	var arr = document.getElementsByName("checkbox[]"); 
	var checked_num = 0;
	for (var i=0; i<arr.length; ++i) {
		if (arr[i].checked == true)
			++checked_num;
	}
	if (checked_num <= 0) {
		alert("you must at least select one message to delete!");
		return false;
	}
	
	if (confirm("Are you sure to delete the selected messages?") == false ) return false;
	
	 
	var next_page = frm.elements["next_page"].value; 
	frm.action = "/fmol/page/mail/handle_mail.php?myaction=deleteSelectedMails"
					+ "&next_page=" + next_page;
	frm.submit();
}

function changeMailType(control) {
	var frm = document.forms["mail_form"];
	var mail_filter = control.value; 
	frm.action = "/fmol/page/mail/mail.php?mail_filter=" + mail_filter;
	
	frm.submit();
}

var curRow = null;
// click one row
function clickRow(control, mail_id) 
{
	if (curRow != null)
		curRow.className = "gSGRowOdd";
	
	curRow = control.parentNode;
	curRow.className = "gSGRowEven";
	
	viewMailDetail(control, mail_id);
}

</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="1">{SPACE}</td>
  </tr>
  <tr>
    <td>
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" >
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
          <tr>
            <td>
			
			<table width="100%"  border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
			<form name="mail_form" method="post" action="/fmol/page/mail/handle_mail.php" >
			  <input type="hidden" name="next_page" value="{NEXT_PAGE}" />
              <tr>
                <td class="gSGSectionTitle">
				<div class="gSGSectionTitle">&nbsp;NewsBox:&nbsp;&nbsp;{MAIL_COUNT} &nbsp;news displayed</div>
				</td>
				<td class="gSGSectionTitle">
				
				<div align="right" class="gSGSectionTitle">
				  Filter:
				  <select name="mail_filter" onChange="changeMailType(this)" >
				    <option value="" {ALL_NEWS_SELECTED}>All News</option>
				    <option value="0" {SYSTEM_NEWS_SELECTED}>System News</option>
				    <option value="1" {GAME_NEWS_SELECTED}>Game News</option>
				    <option value="2" {USER_MESSAGES_SELECTED}>User Messages</option>
				  </select>
				</div>
				
				</td>
				
				<td class="gSGSectionTitle">
				  <div align="right" class="gSGSectionTitle">
				  <input type="button" class="button" onClick="deleteAllMessages()" name="delete_all_messages" value="Del All" title="Delete All Messages" />
 				  <input type="button" class="button" onClick="deleteSelectedMessages()" name="delete_selected_messages" value="Del Sel" title="Delete Selected Messages" />
				  </div>
				</td>
				
              </tr>
			
              <tr>
                <td colspan="3"> <!-- <div id="mail_list" style="overflow:auto; height:{MAIL_LIST_DIV_HEIGHT}px; width:100%; text-align:left;"> -->
				<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td width="25" class="gSGSectionColumnHeadings">&nbsp;</td>
                    <td width="80" class="gSGSectionColumnHeadings">From</td>
                    <td width="220" class="gSGSectionColumnHeadings">Subject</td>
                    <td width="100" class="gSGSectionColumnHeadings">Date</td>
                    <td width="25" class="gSGSectionColumnHeadings" align="center">Del</td>
                    <td width="25" class="gSGSectionColumnHeadings" align="center">View</td>
                    <td width="25" class="gSGSectionColumnHeadings" align="center">Sel</td>
                    <td width="15" class="gSGSectionColumnHeadings">&nbsp;</td>
                  </tr>
                  <!-- BEGIN mail -->
				  <tr style="display:{MAIL_LIST_SEPARATOR}">
				    <td colspan="8" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
                  <tr class="{MAIL_TR_CLASS}" >
                    <td class="BlackText">&nbsp;<img src="/fmol/images/{STATUS_IMG}">&nbsp;</td>
                    <td class="BlackText" onClick="clickRow(this, {MAIL_ID})" style="cursor:hand" title="{FULL_FROM_NAME}">{SHORT_FROM_NAME}</td>
                    <td class="BlackText" onClick="clickRow(this, {MAIL_ID})" style="cursor:hand" title="{FULL_SUBJECT}">{SHORT_SUBJECT}</td>
                    <td class="BlackText" onClick="clickRow(this, {MAIL_ID})" style="cursor:hand" title="{FULL_DATE}">{DATE}</td>
                    <td class="BlackText" align="center"><img name="delete_image" src="/fmol/images/delete.gif" onClick="javascript:deleteMail(this, {MAIL_ID})" title="delete the mail" style="cursor:hand"></td>
                    <td class="BlackText" align="center"><img name="view_image" src="/fmol/images/detail.gif" onClick="javascript:viewMailDetail(this, {MAIL_ID})" title="show the mail detail" style="cursor:hand"></td>
                    <td class="BlackText" align="center"><input id="{CHECK_BOX_ID}" type="checkbox" name="checkbox[]" value="{CHECK_BOX_VALUE}" /></td>
					<td class="BlackText" align="center">&nbsp;</td>
                  </tr>
                  <!-- END mail -->
                </table>
				</div></td>
              </tr>
			  
		    </form>
			
			<!-- buttom line -->
			<tr>
			  <td colspan="3" class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
			</tr>
			
			<tr class="gSGRowOdd_input">
              <td colspan="3">{PAGER_TOOLBAR}</td>
            </tr>
			  
            </table>
			</td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td height="3"></td>
      </tr>
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
          <tr>
            <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><iframe id="mail_detail_frame" src="{LOCATION}" frameborder="0" marginheight="0" marginwidth="0" height="400" width="100%"></iframe></td>
              </tr>
            </table></td>
          </tr>
        </table>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>

