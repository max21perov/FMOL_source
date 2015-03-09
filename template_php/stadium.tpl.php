<script language="javascript" type="text/javascript">
function show_hide_obj(objName, show_or_hide)
{
	var trObj = document.getElementById(objName);
	trObj.style.display = show_or_hide; 
}

// 显示路线说明   
function show_route_choice(formObj)
{
	var o_action = formObj.action; 
	var o_target = formObj.target; 
	formObj.action = "/fmol/page/stadium/route_choice.php";
	formObj.target = "_blank";
	formObj.submit();
	
	// 恢复formObj原来的action和target  
	formObj.action = o_action;
	formObj.target = o_target;
}

// 修改球场的名称   
function change_stadium_name(formObj)
{
	var o_action = formObj.action; 
	var o_target = formObj.target; 
	formObj.action = "/fmol/page/stadium/handle_stadium.php?myaction=changeStadiumName";
	formObj.target = "_self";
	formObj.submit();
	
	// 恢复formObj原来的action和target 
	formObj.action = o_action;  
	formObj.target = o_target;
}


var add_seater_options = [];
// change the option of "add seater" 
function choose_add_seater_option(formObj, addSeaterNum)
{
	for (var i=0; i<add_seater_options.length; ++i) {
		var option = add_seater_options[i];
		if (option[0] == addSeaterNum) {
			formObj.elements["add_seater_num"].value = option[0];
			formObj.elements["add_seater_cost"].value = option[1];
			formObj.elements["add_seater_time"].value = option[2];	
			break;
		}
	}
	
}

// 确认扩建球场（前提是有option可选）  
function confirm_expand_scale(formObj)
{
	// 确认玩家的意见  
	if (confirm("Are you sure to confirm to expand scale?") == false ) 
		return false;
		
	// 确认有选项可选  
	var expand_route_radio = formObj.elements["expand_route_radio"];
	if (expand_route_radio == null) {
		alert("There is not any option can choose to expand the stadium.");
		return;
	}
	
	// 系统提交  
	var o_action = formObj.action; 
	var o_target = formObj.target; 
	formObj.action = "/fmol/page/stadium/handle_stadium.php?myaction=expandStadiumScale";
	formObj.target = "_self";
	formObj.submit();
	
	// 恢复formObj原来的action和target 
	formObj.action = o_action;  
	formObj.target = o_target;
}

// 确认增设坐席  
function add_seater_submit(formObj)
{
	// 确认玩家的意见  
	if (confirm("Are you sure to add seater?") == false ) 
		return false;
	
	// 确认有选项可选   
	var add_seater_radio = formObj.elements["add_seater_radio"];
	if (add_seater_radio == null) {
		alert("There is not any option can choose to add seater.");
		return false;
	}
	
	// 确认增设坐席数量是否在容量范围之内  
	var add_seater_num = parseInt(formObj.elements["add_seater_num"]);
	var stadium_capacity = parseInt(formObj.elements["stadium_capacity"]);
	var stadium_seater = parseInt(formObj.elements["stadium_seater"]);
	var station_seat_num = stadium_capacity-stadium_seater;    // 站席数量
	if (add_seater_num > station_seat_num) {
		alter("The seater number you added is beyond the scope of capacity.");
		return false;
	}
	
	// 系统提交  
	var o_action = formObj.action; 
	var o_target = formObj.target; 
	formObj.action = "/fmol/page/stadium/handle_stadium.php?myaction=addSeater";
	formObj.target = "_self";
	formObj.submit();
	
	// 恢复formObj原来的action和target 
	formObj.action = o_action;  
	formObj.target = o_target;
}

</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" >
<form name="stadium_form" method="post" >
	<input type="hidden" name="primary_stadium_id" value="{PRIMARY_STADIUM_ID}" />
	<input type="hidden" name="add_seater_option_str" value="{ADD_SEATER_OPTION_STR}" />
	<input type="hidden" name="stadium_capacity" value="{STADIUM_CAPACITY}" />
	<input type="hidden" name="stadium_seater" value="{SEATER}" />
	<input type="hidden" name="board_attitude" value="{BOARD_ATTITUDE}" />

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
					<td>name </td>
					<td colspan="3">
					<input type="text" name="stadium_name" class="inputField" style="width:300px " value="{STADIUM_NAME}" />
					<input type="button" class="button" name="" value="change name" onClick="change_stadium_name(this.form)" style="width:100px " /></td>
					</tr>
				
				  <tr>
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>capacity </td>
					<td>{STADIUM_CAPACITY}</td>
					<td>average attendance rate </td>
					<td>{AVERAGE_ATTENDANCE_RATE}</td>
				  </tr>
				  
				  <tr>
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>seater</td>
					<td colspan="3">{SEATER}&nbsp;&nbsp;({SEATER_RATE})</td>
				  </tr>
				  
				  <tr>
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
				  <tr class="gSGRowOdd">
					<td>scale flag</td>
					<td colspan="3">{CURRENT_SCALE_FLAG}</td>
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
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Add Seater</div></td>
            </tr>
            <tr>
              <td bgcolor="#FFFFFF">
			  <table width="100%"  border="0" cellspacing="2" cellpadding="0">
			  <tr>
				<td>
				<div  style='width:97.8%;border: 1px dashed #CCCCCC;font-family: Verdana, Arial, Helvetica, sans-serif;padding: 5px;'>
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
				  <tr class="gSGRowOdd">
					<td >
					&nbsp;<input type="radio" name="add_seater_radio" value="option_1" checked onClick="choose_add_seater_option(this.form, 1000)" />+1,000
					&nbsp;<input type="radio" name="add_seater_radio" value="option_2" onClick="choose_add_seater_option(this.form, 2000)" />+2,000
					&nbsp;<input type="radio" name="add_seater_radio" value="option_3" onClick="choose_add_seater_option(this.form, 3000)" />+3,000
					</td>
					
				    <td rowspan="5" align="center" width="200" ><input type="button" class="button" {ADD_SEATER_DISABLED} value="add seater" onClick="add_seater_submit(this.form)" style="width:100px " /><br> <span class="SelfTeamText">{ADD_SEATER_PROGRESS}</span></td>
				  </tr>
				  
				  <tr bgcolor="#FFFFFF">
					<td  height="8" ></td>
				    </tr>
				  
				  <tr class="gSGRowOdd">
					<td><input type="hidden" name="add_seater_num" value="1000" />
					&nbsp;cost: <input type="text" readonly="true" class="inputNoBorder" name="add_seater_cost" value="30000" style="width:100px " />
					&nbsp;time: <input type="text" readonly="true" class="inputNoBorder" name="add_seater_time" value="2" style="width:100px " />
					</td>
				    </tr>
				  
				  <tr bgcolor="#FFFFFF">
					<td height="8" ></td>
				    </tr>
				  
				  <tr class="gSGRowOdd">
					<td>
					&nbsp;The cost of starting a expansion: <input type="text" readonly="true" class="inputNoBorder" name="start_project_fee" value="20000" style="width:100px " />
					</td>
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
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Expand Scale</div></td>
            </tr>
            <tr>
              <td bgcolor="#FFFFFF">
			  <table width="100%"  border="0" cellspacing="2" cellpadding="0">
			  <tr>
				<td>
				<div  style='width:97.8%;border: 1px dashed #CCCCCC;font-family: Verdana, Arial, Helvetica, sans-serif;padding: 5px;'>
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
				  <tr class="gSGRowOdd">
				    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
					
					<tr>
					<td valign="top">&nbsp;board attitude</td>
					<td valign="top">
					<textarea style="width:300px; overflow:auto;" rows="3" readonly class="inputField">{BOARD_ATTITUDE_STR}</textarea>
					</td>
				    <td width="100">
					<input type="button" class="button" {ASK_TO_EXAPND_DISABLED} name="" onClick="show_hide_obj('tr_ask_to_expand', '')" value="ask to expand" style="width:100px " />
					<span class="SelfTeamText">{EXPAND_STADIUM_PROGRESS}</span>
					</td>
					</tr>
					
					</table>
					</td>
				  </tr>
				  
				  <tr><td height="8"></td></tr>
				  <tr>
				    <td class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  <tr><td height="8"></td></tr>
				  
				   <tr id="tr_ask_to_expand" style="display:none" class="gSGRowOdd">
					<td>
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					
					  <tr style="display:{COST_PRIVILEGE_POINT_DISPLAY} ">
						<td class="gSGRowOdd" >insist to require: force the board to pass expansion(cost <input type="text" readonly="true" class="inputNoBorder" name="cost_privilege_point_num" value="{COST_PRIVILEGE_POINT_NUM}" style="width:10px " /> privilege points)</td>
					  </tr>
					  <tr>
						<td>
						<div  style='width:97.8%;border: 1px dashed #CCCCCC;font-family: Verdana, Arial, Helvetica, sans-serif;padding: 5px;'>
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td class="gSGSectionColumnHeadings" title="scale" >&nbsp;options</td>
							<td class="gSGSectionColumnHeadings" title="route choice" >route </td>
							<td class="gSGSectionColumnHeadings" title="type" >type </td>
							<td class="gSGSectionColumnHeadings" title="finish time" >finish </td>
							<td align="right" class="gSGSectionColumnHeadings" title="cost" >cost</td>
							<td align="right" class="gSGSectionColumnHeadings" title="capacity" >capacity</td>
							<td align="right" class="gSGSectionColumnHeadings" title="next season fund impact" >impact&nbsp;</td>
						  </tr>
						  
						  <!-- BEGIN alter_stadium -->
						  <tr class="gSGRowOdd">
							<td >&nbsp;<input type="radio" name="expand_route_radio" value="{OPTION_VALUE}" {IS_CHECKED} />{OPTION_TEXT}</td>
							<td >{SCALE_FLAG}</td>
							<td >{ALTER_TYPE}</td>
							<td >{FINISH_TIME}</td>
							<td align="right" >{ALTER_COST}</td>
							<td align="right" >{CAPACITY}</td>
							<td align="right" >{NEXT_SEASON_FUND_IMPACT}&nbsp;</td>
						  </tr>
						  <tr>
							<td colspan="9" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
						  </tr>
						  <!-- END alter_stadium -->
				  
						</table>
						</div>
						</td>
					  </tr>
					  
					  <tr>
						<td height="8"></td>
					  </tr>
					  
					  <tr>
						<td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td>&nbsp;</td>
								<td width="150" align="center"><input type="button" class="button" value="route choice" onClick="show_route_choice(this.form)" style="width:100px " /></td>
								<td width="150" align="center"><input type="button" class="button" value="confirm" onClick="confirm_expand_scale(this.form)" style="width:100px " /></td>
								<td width="150" align="center"><input type="button" class="button"  value="cancel" onClick="show_hide_obj('tr_ask_to_expand', 'none')" style="width:100px " /></td>
								<td>&nbsp;</td>
							  </tr>
							</table>
							</td>
					  </tr>
					
					</table>

					</td>
					
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
function init_stadium_page()
{
	var add_seater_option_str = document.forms["stadium_form"].elements["add_seater_option_str"].value;
	var options_arr = add_seater_option_str.split("|");
	for (var i=0; i<options_arr.length; ++i) {
		var item_str = options_arr[i];
		var item_arr = item_str.split(":");
		add_seater_options[add_seater_options.length] = item_arr;
	}	
}

// 执行页面的初始化函数
init_stadium_page();
</script>


