{SPACE}

<table width="100%"  border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td align="right" class="gSGSectionColumnHeadings">POPer1</td>
    <td class="gSGSectionColumnHeadings">POPer2</td>
  </tr>
  <tr class="gSGRowOdd_input">
    <td align="right"><select style="width:125px " name="POPSwitch_POPer1_select" >
      <!-- BEGIN POPSwitch_POPer1_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END POPSwitch_POPer1_select -->
    </select></td>
	
    <td><select style="width:125px " name="POPSwitch_POPer2_select" >
      <!-- BEGIN POPSwitch_POPer2_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END POPSwitch_POPer2_select -->
    </select></td>
  </tr>
  <tr align="center">
    <td colspan="2">
      <input type="button" class="button" style="width:100px " name="POPSwitch_insert" value="insert" onClick="POPSwitch_insert_click(this.form)" />
    </td>
  </tr>
</table>

<script language="javascript" type="text/javascript">
function POPSwitch_insert_click(form_obj)
{
	var POPer1_value = form_obj.elements["POPSwitch_POPer1_select"].value;
	var POPer2_value = form_obj.elements["POPSwitch_POPer2_select"].value;
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var POPer1_selectedIndex = form_obj.elements["POPSwitch_POPer1_select"].selectedIndex;
	var POPer2_selectedIndex = form_obj.elements["POPSwitch_POPer2_select"].selectedIndex;
	if (POPer1_value == POPer2_value) {
		alert("The original place and the final place can not be the same!");
		return;
	}
	
	var time_select_obj = form_obj.elements["time_select"];
	var selected_index = time_select_obj.selectedIndex;
	
	var table_name = cmd_list_table_arr[selected_index];   
	var table_obj = document.getElementById(table_name);  
	
	var time = new Date().getTime();
	var row_id = table_name + "_row_" + time;
 
	// get full command value
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var full_command_value = POPSwitch_get_full_command_value(form_obj, POPer1_value, POPer2_value);	

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
	col_obj.innerHTML = "&nbsp;" + POPSwitch_get_command_display_str(POPer1_value, POPer2_value);
}

// get the full command value
function POPSwitch_get_full_command_value(form_obj, POPer1_value, POPer2_value)
{     
	var time_select_value = form_obj.elements["time_select"].value;
	var cond_select_value = form_obj.elements["cond_select"].value;
	var type_select_value = form_obj.elements["type_select"].value;
	
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var full_command_value = time_select_value + "|" + cond_select_value + "|" +
						 type_select_value + "|" + POPer1_value + "|" + POPer2_value;
					
	return full_command_value;	
}

// get command_display_str
function POPSwitch_get_command_display_str(POPer1_value, POPer2_value)
{
	var command_display_str = get_player_on_field_name(POPer1_value) +
							   " <=> " +
							  get_player_on_field_name(POPer2_value);
	
	return command_display_str;							
}

</script>

