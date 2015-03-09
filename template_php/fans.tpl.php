
<script type="text/javascript" language="javascript">
function display_table_cols(tableId, buttonObj)
{
	var buttonValue = (buttonObj.value).toLowerCase(); 
	var display_value = "";
	if (buttonValue == "brief") {
		buttonObj.value = "detail";
		display_value = "none";
	}
	else {
		buttonObj.value = "brief";	
		display_value = "";
	}
	
	// 将id为tableId的table中的列隐藏或显示
	var tableObj = document.getElementById(tableId);  
	var rowObj = tableObj.rows[0]; 
	var len = rowObj.cells.length;
	var cell_arr = [];
	// 查询第一行中的列，把列id为display_td_head的列记录下来，放在数组cell_arr中
	for (var i=0; i<len; ++i) {
		if (rowObj.cells[i].id == "display_td_head") { 
			cell_arr[cell_arr.length] = i;
		}
	}
	// 遍历tableObj，将需要处理的列隐藏或显示
	len = tableObj.rows.length;
	for (var r=0; r<len; ++r) {
		// 只对id为"display_row"的行处理
		if (tableObj.rows[r].id == "display_row") {
			for (var c=0; c<cell_arr.length; ++c) {
				var cell_index = cell_arr[c];  
				tableObj.rows[r].cells[cell_index].style.display = display_value;
			}
		}
	}
	
}

// 显示所有的match，或者显示最近5场match
function change_match_num(buttonObj) {
	var buttonValue = (buttonObj.value).toLowerCase(); 
	var match_num = "";
	if (buttonValue == "all") {
		match_num = "all";
		document.location.href = "/fmol/page/fans/fans.php?match_num=" + match_num;
	}
	else {
		match_num = "5";
		document.location.href = "/fmol/page/fans/fans.php";
	}
	
	
}


var action_arr = [];
// when you change your action select
function action_select_onchange(formObj, selectObj)
{
	var actionTitle = selectObj.value;  
	for (var i=0; i<action_arr.length; ++i) {
		var option = action_arr[i];
		if (option[0] == actionTitle) {
			formObj.elements["action_explain"].value = option[1];
			break;
		}
	}
	
}
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" >

<form name="fans_form" method="post" >
	<input type="hidden" name="action_str" value="{ACTION_STR}" />


    <tr><td height="1">{SPACE}</td></tr>
	
	
	<tr>
	 <td>
	   <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Basic Information </div></td>
            </tr>
            <tr>
              <td bgcolor="#FFFFFF">
			  <table width="100%"  border="0" cellspacing="2" cellpadding="0">
			  <tr>
				<td>
				<div id="Mtext" style='width:97.8%;border: 1px dashed #CCCCCC;font-family: Verdana, Arial, Helvetica, sans-serif;padding: 5px;'>
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
				  <tr class="gSGRowOdd">
					<td>fans number</td>
					<td colspan="3">{FANS_NUMBER}</td>
				  </tr>
				  
				  <tr>
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>season expection </td>
					<td colspan="3">{SEASON_EXPECTION}</td>
				  </tr>
				  
				  <tr>
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>fans satisfaction </td>
					<td>{FANS_SATISFACTION}</td>
					<td>fans core rate </td>
					<td>{FANS_CORE_RATE}</td>
				  </tr>
				  
				  <tr>
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>latest option </td>
					<td colspan="3">{LATEST_OPTION}</td>
				  </tr>
				  
				  <tr>
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>season option </td>
					<td colspan="3">{SEASON_OPTION}</td>
				  </tr>
				</table>

				</div>
				</td>
			  </tr>
			</table>

			    
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
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Recently Enter Stadium Audience Number </div></td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellpadding="0" cellspacing="0" id="match_info_table">
                  <tr id="display_row">
                    <td class="gSGSectionColumnHeadings" align="left" title="match date" id="display_td_head">&nbsp;date</td>
                    <td class="gSGSectionColumnHeadings" align="left" title="match type" id="display_td_head">type </td>
                    <td class="gSGSectionColumnHeadings" align="right" title="VS & Result" >VS & Result </td>
                    <td class="gSGSectionColumnHeadings" align="right" title="audience number" >number </td>
                    <td class="gSGSectionColumnHeadings" align="right" title="receipts" id="display_td_head">receipts &nbsp;</td>
                  </tr>
				  
				  <!-- match info -->
				  
				  <!-- BEGIN match -->
                  <tr class="gSGRowOdd" id="display_row">
                    <td align="left">&nbsp;{MATCH_DATE}</td>
                    <td align="left">{MATCH_TYPE}</td>
                    <td align="right">{VS_RESULT}</td>
                    <td align="right">{AUDIENCE_NUMBER}</td>
                    <td align="right">{RECEIPTS}&nbsp;</td>
                  </tr>
				  <tr>
				    <td colspan="5" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  <!-- END match -->
				  
                  <tr align="center" height="30"  bgcolor="#FFFFFF">
                    <td colspan="5">&nbsp;
                      <input type="button" class="button" value="brief" onClick="display_table_cols('match_info_table', this)" style="width:100px " />&nbsp;
                      <input type="button" class="button" value="{MATCH_NUM_BUTTON_VALUE}" onClick="change_match_num(this)" style="width:100px " />&nbsp;
                      <input type="button" class="button" value="chart" style="width:100px " /></td>
                  </tr>
				  
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
	</tr>
		
	<tr><td height="2"></td></tr>
	
	<tr>
	 <td>
	   <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Action</div></td>
            </tr>
            <tr>
              <td bgcolor="#FFFFFF">
			  <table width="100%"  border="0" cellspacing="2" cellpadding="0">
			  <tr>
				<td>
				<div  style='width:97.8%;border: 1px dashed #CCCCCC;font-family: Verdana, Arial, Helvetica, sans-serif;padding: 5px;'>
				  <table width="100%"  border="0" cellspacing="3" cellpadding="0">
				  <tr class="gSGRowOdd">
					<td>&nbsp;request</td>
					<td>
					<select name="action_select" onChange="action_select_onchange(this.form, this)" >
					<option value="action1">Action 1</option>
					<option value="action2">Action 2</option>
					<option value="action3">Action 3</option>
					<option value="action4">Action 4</option>
					<option value="action5">Action 5</option>
					</select>
					</td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td valign="top">&nbsp;explain</td>
					<td><textarea id="action_explain" style="width:300px; overflow:auto;" rows="2" readonly class="inputField">{ACTION_EXPLAIN}</textarea></td>
				  </tr>
				
				
				  <tr valign="bottom"  bgcolor="#FFFFFF" height="30">
					<td colspan="2" align="center"><input type="button" class="button" name="" value="Confirm" style="width:100px " /></td>
				  </tr>
					
				</table>

				</div>
				</td>
			  </tr>
			</table>

			    
		      </td>
            </tr>
          </table></td>
        </tr>
      </table>
	  
	 </td>
	</tr>

</form>
</table>


<script >

// 页面的初始化函数
function init_fans_page()
{
	var action_str = document.forms["fans_form"].elements["action_str"].value;
	var arr1 = action_str.split("|");
	for (var i=0; i<arr1.length; ++i) {
		var item_str = arr1[i];
		var item_arr = item_str.split(":");
		action_arr[action_arr.length] = item_arr;
	}	
}

// 执行页面的初始化函数
init_fans_page();
</script>

