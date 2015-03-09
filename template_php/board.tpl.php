<script type="text/javascript" language="javascript">
var action_arr = [];
// when you change the action_select
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
<form name="board_form" method="post" >
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
					<td>season expection </td>
					<td>{SEASON_EXPECTION}</td>
				  </tr>
				  
				  <tr>
				    <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>satisfaction </td>
					<td>{BOARD_SATISFACTION}</td>
				  </tr>
				  
				  <tr>
				    <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>latest option </td>
					<td>{LATEST_OPTION}</td>
				  </tr>
				  
				  <tr>
				    <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>season option </td>
					<td>{SEASON_OPTION}</td>
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
                      <td><select name="action_select" onChange="action_select_onchange(this.form, this)">
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
function init_board_page()
{
	var action_str = document.forms["board_form"].elements["action_str"].value;
	var arr1 = action_str.split("|");
	for (var i=0; i<arr1.length; ++i) {
		var item_str = arr1[i];
		var item_arr = item_str.split(":");
		action_arr[action_arr.length] = item_arr;
	}	
}

// 执行页面的初始化函数
init_board_page();
</script>
