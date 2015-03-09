{SPACE}

<table width="100%"  border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td align="center" class="gSGSectionColumnHeadings">Can Tactics</td>
  </tr>
  <tr class="gSGRowOdd_input">
    <td align="center"><select style="width:125px " name="SetTactics_CanTactics_select" >
      <!-- BEGIN SetTactics_CanTactics_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END SetTactics_CanTactics_select -->
    </select></td>
	
  </tr>
  <tr align="center">
    <td colspan="2">
      <input type="button" class="button" style="width:100px " name="SetTactics_insert" value="insert" onclick="SetTactics_insert_click(this.form)"/>
    </td>
  </tr>
</table>



<script language="javascript" type="text/javascript">
function SetTactics_insert_click(form_obj)
{
	var CanTactics_value = form_obj.elements["SetTactics_CanTactics_select"].value;
	// use the selectedIndex for the can_tactics index: from 0 ~ 2 (can_tactics_1, can_tactics_2, can_tactics_3)
	var CanTactics_selectedIndex = form_obj.elements["SetTactics_CanTactics_select"].selectedIndex;
	
	var time_select_obj = form_obj.elements["time_select"];
	var selected_index = time_select_obj.selectedIndex;
	
	var table_name = cmd_list_table_arr[selected_index];   
	var table_obj = document.getElementById(table_name);   
	
	var time = new Date().getTime();
	var row_id = table_name + "_row_" + time;
	
	// get full command value
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var full_command_value = SetTactics_get_full_command_value(form_obj, CanTactics_selectedIndex);	
	
	/**
	 * add a row into to the table
	 */
	var row_obj = createRow(table_obj);
	row_obj.id = row_id;
	// col 1
	var col_obj = createColumn(row_obj, "center"); 
	col_obj.className = "gSGRowOdd"
	col_obj.width = "25px"
	col_obj.innerHTML = '<input type="checkbox" value="' + row_id + '" />' + 
						'<input type="hidden" name="full_command_value[]" value="' + full_command_value + '" />';
	// col 2
	col_obj = createColumn(row_obj, "left"); 
	col_obj.className = "gSGRowOdd"
	col_obj.innerHTML = "&nbsp;" + SetTactics_get_command_display_str(CanTactics_value);
	
}

// get the full command value
function SetTactics_get_full_command_value(form_obj, CanTactics_selectedIndex)
{
	var time_select_value = form_obj.elements["time_select"].value;
	var cond_select_value = form_obj.elements["cond_select"].value;
	var type_select_value = form_obj.elements["type_select"].value;
	
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var full_command_value = time_select_value + "|" + cond_select_value + "|" +
						 type_select_value + "|" + CanTactics_selectedIndex;
						 
	return full_command_value;	
}

// get command_display_str
function SetTactics_get_command_display_str(CanTactics_value)
{
	var command_display_str = get_can_tactics_str(CanTactics_value) +
							   " => " +
							   "Cur Tactics";;
	
	return command_display_str;							
}

</script>


