{SPACE}

<table width="100%"  border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td align="right" class="gSGSectionColumnHeadings">POPer</td>
    <td class="gSGSectionColumnHeadings">Direction</td>
  </tr>
  <tr class="gSGRowOdd_input">
    <td align="right"><select style="width:125px " name="POPChangeD_POPer_select" >
      <!-- BEGIN POPChangeD_POPer_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END POPChangeD_POPer_select -->
    </select></td>
	
    <td><select style="width:125px " name="move_direction_select" >
      <!-- BEGIN move_direction_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END move_direction_select -->
    </select></td>
  </tr>
  <tr align="center">
    <td colspan="2">
      <input type="button" class="button" style="width:100px " name="POPChangeD_insert" value="insert" onclick="POPChangeD_insert_click(this.form)"/>
    </td>
  </tr>
</table>



<script language="javascript" type="text/javascript">
function POPChangeD_insert_click(form_obj)
{
	var POPer_value = form_obj.elements["POPChangeD_POPer_select"].value;
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var POPer_selectedIndex = form_obj.elements["POPChangeD_POPer_select"].selectedIndex;
	var move_direction_value = form_obj.elements["move_direction_select"].value;
	
	var time_select_obj = form_obj.elements["time_select"];
	var selected_index = time_select_obj.selectedIndex;
	
	var table_name = cmd_list_table_arr[selected_index];   
	var table_obj = document.getElementById(table_name);   
	
	var time = new Date().getTime();
	var row_id = table_name + "_row_" + time;
	
	// get full command value
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var full_command_value = POPChangeD_get_full_command_value(form_obj, POPer_value, move_direction_value);	
	
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
	col_obj.innerHTML = "&nbsp;" + POPChangeD_get_command_display_str(POPer_value, move_direction_value);
	
}

// get the full command value
function POPChangeD_get_full_command_value(form_obj, POPer_value, move_direction_value)
{
	var time_select_value = form_obj.elements["time_select"].value;
	var cond_select_value = form_obj.elements["cond_select"].value;
	var type_select_value = form_obj.elements["type_select"].value;
	
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var full_command_value = time_select_value + "|" + cond_select_value + "|" +
						 type_select_value + "|" + POPer_value + "|" + move_direction_value;
						 
	return full_command_value;	
}

// get command_display_str
function POPChangeD_get_command_display_str(POPer_value, move_direction_value)
{
	var command_display_str = get_player_on_field_name(POPer_value) +
							   " => " +
							  get_move_direction_str(move_direction_value);
	
	return command_display_str;							
}

</script>


