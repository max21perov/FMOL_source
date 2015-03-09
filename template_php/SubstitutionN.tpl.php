

{SPACE}

<table width="100%"  border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td align="right" class="gSGSectionColumnHeadings">POPer</td>
    <td class="gSGSectionColumnHeadings">Suber</td>
  </tr>
  <tr class="gSGRowOdd_input">
    <td align="right"><select style="width:125px " name="SubstitutionN_POPer_select" >
      <!-- BEGIN SubstitutionN_POPer_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END SubstitutionN_POPer_select -->
    </select></td>
	
    <td><select style="width:125px " name="SubstitutionN_Suber_select" >
      <!-- BEGIN SubstitutionN_Suber_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END SubstitutionN_Suber_select -->
    </select></td>
  </tr>
  <tr align="center"  class="gSGRowOdd_input">
    <td colspan="2"><table width="100%"  border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td width="30" align="right"><span class="gSGSectionColumnHeadings">Row</span></td>
        <td style="width:90px "><select style="width:80px " name="SubstitutionN_row_select" onchange="SubstitutionN_row_col_select_change(this.form)">
          <!-- BEGIN SubstitutionN_row_select -->
          <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
          <!-- END SubstitutionN_row_select -->
        </select></td>
        <td width="30" align="right">Col</td>
        <td style="width:90px "><select style="width:80px " name="SubstitutionN_col_select"  onchange="SubstitutionN_row_col_select_change(this.form)">
          <!-- BEGIN SubstitutionN_col_select -->
          <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
          <!-- END SubstitutionN_col_select -->
        </select></td>
        <td width="60" align="right" title="New Place">Place </td>
        <td style="width:80px ">
		<input type="text" class="inputField" style="width:50px " name="SubstitutionN_place_str" />
		<input type="hidden"  name="SubstitutionN_place_value" />
		</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr align="center">
    <td colspan="2">
      <input type="button" class="button" style="width:100px " name="SubstitutionN_insert" value="insert" onClick="SubstitutionN_insert_click(this.form)" />
    </td>
  </tr>
</table>

<script type="text/javascript" language="javascript">

function SubstitutionN_row_col_select_change(form_obj)
{
	var row_selectedIndex = form_obj.elements["SubstitutionN_row_select"].selectedIndex;
	var col_selectedIndex = form_obj.elements["SubstitutionN_col_select"].selectedIndex;
	
	var place_index = row_selectedIndex*5 + col_selectedIndex;
	
	var place_str = place_arr[place_index];
	
	form_obj.elements["SubstitutionN_place_str"].value = place_str;
	form_obj.elements["SubstitutionN_place_value"].value = 25 - place_index;
}


function SubstitutionN_init()
{
	var form_obj = document.forms["command_list_form"];
	SubstitutionN_row_col_select_change(form_obj);
}

function SubstitutionN_insert_click(form_obj)
{
	var POPer_value = form_obj.elements["SubstitutionN_POPer_select"].value;
	var Suber_value = form_obj.elements["SubstitutionN_Suber_select"].value;
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var POPer_selectedIndex = form_obj.elements["SubstitutionN_POPer_select"].selectedIndex;
	var Suber_selectedIndex = form_obj.elements["SubstitutionN_Suber_select"].selectedIndex;
	var row_index = form_obj.elements["SubstitutionN_row_select"].value;  // ndy
	var col_index = form_obj.elements["SubstitutionN_col_select"].value;  // ndx
	
	var time_select_obj = form_obj.elements["time_select"];
	var selected_index = time_select_obj.selectedIndex;
	
	var table_name = cmd_list_table_arr[selected_index];   
	var table_obj = document.getElementById(table_name);   
	
	var time = new Date().getTime();
	var row_id = table_name + "_row_" + time;
	
	// get full command value
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var full_command_value = SubstitutionN_get_full_command_value(form_obj, POPer_value, Suber_value, row_index, col_index);	
	
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
	col_obj.innerHTML = "&nbsp;" + SubstitutionN_get_command_display_str(POPer_value, Suber_value, row_index, col_index);
}

// get the full command value
function SubstitutionN_get_full_command_value(form_obj, POPer_value, Suber_value, row_index, col_index)
{
	var time_select_value = form_obj.elements["time_select"].value;
	var cond_select_value = form_obj.elements["cond_select"].value;
	var type_select_value = form_obj.elements["type_select"].value;
	
	// use the selectedIndex for the tpop index: from 0 ~ 9
	var full_command_value = time_select_value + "|" + cond_select_value + "|" +
						 type_select_value + "|" + Suber_value + "|" + POPer_value + "|" + 
						 col_index + "|" + row_index;
						 
	return full_command_value;	
}

// get command_display_str
function SubstitutionN_get_command_display_str(POPer_value, Suber_value, row_index, col_index)
{
	var command_display_str = get_player_sub_name(Suber_value) +
							   " <=> " +
							  get_player_on_field_name(POPer_value) +
							   " => " +
							  get_row_str(row_index) + get_col_str(col_index);
	
	return command_display_str;							
}

</script>

<script language="javascript" type="text/javascript">

SubstitutionN_init();
</script>

