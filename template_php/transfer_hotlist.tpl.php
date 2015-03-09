<script type="text/javascript" language="javascript">

function changePositionType(control) {
	var frm = document.forms["transfer_hotlist_form"];
	var position_filter = control.value; 
	frm.action = "/fmol/page/transfer/transfer_hotlist.php?position_filter=" + position_filter;
	
	frm.submit();
}

var curRow = null;
// click one row
function clickRow(control) 
{
	if (curRow != null)
		curRow.className = "gSGRowOdd";
	
	curRow = control;
	curRow.className = "gSGRowEven";
}



// set the check_flag of all check_box in the min_table
function set_all_checkbox_check_flag(form_obj, pre_name, select_obj)
{ 
	var len = form_obj.length;
	var check_flag = select_obj.checked;
	for (var i=len-1; i>=0; --i) {  
		if((form_obj.elements[i].type).toUpperCase()=="CHECKBOX" && 
		    form_obj.elements[i].name.indexOf(pre_name)!=-1) {
			
			form_obj.elements[i].checked = check_flag;
		}
	}
}



// delete selected rows
function delete_selected_rows(form_obj)
{ 
	
	var arr = document.getElementsByName("hotlist_checkbox[]"); 
	var checked_num = 0;
	for (var i=0; i<arr.length; ++i) {
		if (arr[i].checked == true)
			++checked_num;
	}
	if (checked_num <= 0) {
		alert("you must at least select one line to delete!");
		return false;
	}
	
	if (confirm("Are you sure to delete the selected lines?") == false ) return false;
	
	 
	form_obj.action = "/fmol/page/transfer/handle_hotlist.php?myaction=deleteSelectedHotlists";
	form_obj.submit();
}


</script>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr><td height="1">{SPACE}</td></tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
		   <form name="transfer_hotlist_form" method="post" action="/fmol/page/transfer/handle_transfer.php" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Transfer Hot List </div></td>
			  <td class="gSGSectionTitle">
			    <input type="button" class="button" style="width:90px " value="del selected" onClick="delete_selected_rows(this.form)" />
			  </td>
			  
			   <td class="gSGSectionTitle">
				<div align="right" class="gSGSectionTitle">
				  Filter:
				  <select name="position_filter_select" onChange="changePositionType(this)" >
				    <option value="" {ALL_SELECTED}>All</option>
				    <option value="0" {GOAL_KEEPERS_SELECTED}>Goal Keepers</option>
				    <option value="1" {DEFENDERS_SELECTED}>Defenders</option>
				    <option value="2" {MIDFIELDERS_SELECTED}>Midfielders</option>
				    <option value="3" {STRIKERS_SELECTED}>Strikers</option>
				  </select>
				</div>
				
				</td>
            </tr>
            <tr>
              <td class="ProText" colspan="3"><table id="command_list_table" width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td class="gSGSectionColumnHeadings" >
                      <input type="checkbox" name="all_checkbox" onclick="set_all_checkbox_check_flag(this.form, 'hotlist', this)" />
                    </td>
                    <td class="gSGSectionColumnHeadings" nowrap title="Player Name">&nbsp;Player Name</td>
				    <td class="gSGSectionColumnHeadings">Parent Team</td>
                    <td class="gSGSectionColumnHeadings" >Age</td>
                    <td class="gSGSectionColumnHeadings" >Pos</td>
                    <td class="gSGSectionColumnHeadings">Worth</td>
                  </tr>
                  <!-- BEGIN transfer_hotlist -->
				  <tr style="display:{LIST_SEPARATOR}">
				    <td colspan="8" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
                  <tr class="{TRAN_TR_CLASS}"  onClick="clickRow(this)" >
				  	<td class="BlackText"><input type="checkbox" name="hotlist_checkbox[]" value="{HOTLIST_ID}" /></td>
                    <td class="BlackText">&nbsp;<a href="/fmol/page/players/player_info.php?player_id={PLAYER_ID}" >{PLAYER_NAME}</a></td>
					<td class="BlackText"><a style="display:{TEAM_NAME_LINK_DISPLAY}" href="/fmol/page/info/club_info.php?team_id={TEAM_ID}" >{TEAM_NAME}</a> <span style="display:{TEAM_NAME_ONLY_DISPLAY}">{TEAM_NAME}</span></td>
                    <td class="BlackText">{AGE}</td>
                    <td class="BlackText">{POS}</td>
					<td class="BlackText">{WORTH}</td>
                  </tr>
                  <!-- END transfer_hotlist -->
				  
				
              </table></td>
            </tr>
			</form>
			
			<!-- buttom line -->
			<tr>
			  <td colspan="3" class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
			</tr>
		  
			<tr class="gSGRowOdd_input">
			  <td colspan="3">{PAGER_TOOLBAR}</td>
			</tr>
			
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

