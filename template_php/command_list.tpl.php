

<script language="javascript" type="text/javascript">

// global variables and functions
var place_arr = [
                  "FL", "FCL", "FC", "FCR", "FR",
				  "AML", "AMCL", "AMC", "AMCR", "AMR",
				  "ML", "MCL", "MC", "MCR", "MR",
				  "DML", "DMCL", "DMC", "DMCR", "DMR",
				  "DL", "DCL", "DC", "DCR", "DR"];
				  
var cmd_list_table_arr = [
                  "zero_min_table", "fifteen_min_table", "thirty_min_table",
				  "fourty_five_min_table", "sisty_min_table", "seventy_five_min_table",
				  "ninety_min_table"];


// create row of table
function createRow(table) { 
	var rowNode = table.insertRow(-1);
	rowNode.className = "gSGRowEven";
	return rowNode;
}

// create column of row
function createColumn(row, align_flag) {
	var colNode = row.insertCell(-1);
	colNode.align = align_flag;
	colNode.wrap = true;
	
	return colNode;
}  	

// delete row of table
function deleteRow(table, row_id) { 
	table.deleteRow(row_id);
}			  

</script>

<span style="display:none " >
    <select style="width:125px " id="common_player_on_field_select" >
      <!-- BEGIN common_player_on_field_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END common_player_on_field_select -->
    </select>
    <select style="width:125px " id="common_player_sub_select" >
      <!-- BEGIN common_player_sub_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END common_player_sub_select -->
    </select>
    <select style="width:125px " id="common_move_direction_arr_select" >
      <!-- BEGIN common_move_direction_arr_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END common_move_direction_arr_select -->
    </select>
    <select style="width:125px " id="common_row_arr_select" >
      <!-- BEGIN common_row_arr_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END common_row_arr_select -->
    </select>
    <select style="width:125px " id="common_col_arr_select" >
      <!-- BEGIN common_col_arr_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END common_col_arr_select -->
    </select>
    <select style="width:125px " id="common_can_tactics_arr_select" >
      <!-- BEGIN common_can_tactics_arr_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END common_can_tactics_arr_select -->
    </select>
  </span>	

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr><td height="1">{SPACE}</td></tr>
  
  <form name="command_list_form" action="/fmol/page/tactics/handle_command_list.php?myaction=saveCommandList" method="post" >
  
  <input type="hidden" name="tactics_id" value="{TACTICS_ID}" />
  
	
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            
			<td width="49%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Operation</div></td>
              </tr>
              <tr>
                <td ><table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr class="gSGRowEven_input">
    <td align="right">&nbsp;<input type="checkbox" disabled checked /></td>
    <td>1 DC </td> 
    <td align="right" title="amount of DC striction">amount (DC)&nbsp;</td>
    <td><input type="text" class="inputField" style="width:50px " name="amount_of_DC_striction" /></td>
  </tr>
  <tr class="gSGRowOdd_input">
    <td align="right">target diff&nbsp;</td>
    <td><select style="width:50px " name="target_diff_select">
      <!-- BEGIN target_diff_select -->
      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
      <!-- END target_diff_select -->
    </select>	</td>
    <td align="right" title="amount of SF striction">amount (SF)&nbsp;</td>
    <td><input type="text" class="inputField" style="width:50px " name="amount_of_SF_striction" /></td>
  </tr>
</table></td>
              </tr>
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Set Command</div></td>
              </tr>
              <tr>
                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr class="gSGRowOdd_input">
				    <td align="right">&nbsp;time&nbsp;</td>
                    <td>
					<select style="width:50px " name="time_select">
                      <!-- BEGIN time_select -->
                      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
                      <!-- END time_select -->
                    </select>
					</td>
					
				    <td align="right">&nbsp;cond&nbsp;</td>
                    <td>
					<select style="width:80px " name="cond_select">
                      <!-- BEGIN cond_select -->
                      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
                      <!-- END cond_select -->
                    </select>
					</td>
					
				    <td align="right">&nbsp;type&nbsp;</td>
                    <td>
					<select style="width:110px " name="type_select" onChange="command_type_change(this)">
                      <!-- BEGIN type_select -->
                      <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
                      <!-- END type_select -->
                    </select>
					</td>
                  </tr>
                  <tr>
                    <td height="250" colspan="6" valign="top">
					  <!-- BEGIN set_command_value_div -->
					  <div id="{DIV_ID}" style="display:{DIV_DISPLAY} " >
					  {DIV_CONTENT}
					  </div>
					  <!-- END set_command_value_div -->
					</td>
                    </tr>
                </table></td>
              </tr>
            </table></td>
			
            <td width="1" class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
            <td width="10" valign="top" bgcolor="#cccccc">&nbsp;</td>
            <td width="1" class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
			
			<td width="49%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Command List</div></td>
              </tr>
              <tr>
                <td class="gSGRowOdd_input">
				<table id="command_list_table" width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
				    <td class="gSGSectionColumnHeadings">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="25" align="center">
						  <input type="checkbox" name="all_checkbox_0" onclick="set_all_checkbox_check_flag(this.form, 'zero_min_table', this)" />
						</td>
						<td>
						  &nbsp;After 0 min. 
						</td>
						<td align="right">&nbsp;						 
						</td>
					  </tr>
					</table>
					</td>
                  </tr>
				  <tr class="gSGRowOdd">
                    <td>
                      <table id="zero_min_table" width="100%"  border="1" cellspacing="0" cellpadding="0">
                        <!-- BEGIN zero_min_table_tr -->
						  <tr id="{ROW_ID}">
						    <td width="25" align="center">
							  <input type="checkbox" value="{ROW_ID}" />
							  <input type="hidden" name="full_command_value[]" value="{FULL_COMMAND_VALUE}" />
						    </td>
						    <td>
							  &nbsp;{COMMAND_DISPLAY_STR}
						    </td>
						  </tr>
						<!-- END zero_min_table_tr -->
                      </table>
					</td>
                  </tr>
				  <tr>
                    <td class="gSGSectionColumnHeadings">
                      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
						<td width="25" align="center">
						  <input type="checkbox" name="all_checkbox_15" onclick="set_all_checkbox_check_flag(this.form, 'fifteen_min_table', this)" />
						</td>
                          <td >&nbsp;After 15 min. </td>
                          <td align="right">&nbsp;
                          </td>
                        </tr>
                      </table>
</td>
                  </tr>
				  <tr class="gSGRowOdd">
                    <td>
                      <table id="fifteen_min_table" width="100%"  border="1" cellspacing="0" cellpadding="0">
                        <!-- BEGIN fifteen_min_table_tr -->
						  <tr id="{ROW_ID}">
						    <td width="25" align="center">
							  <input type="checkbox" value="{ROW_ID}" />
							  <input type="hidden" name="full_command_value[]" value="{FULL_COMMAND_VALUE}" />
						    </td>
						    <td>
							  &nbsp;{COMMAND_DISPLAY_STR}
						    </td>
						  </tr>
						<!-- END fifteen_min_table_tr -->
                      </table>
					</td>
                  </tr>
				  <tr>
                    <td class="gSGSectionColumnHeadings">
                      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
						<td width="25" align="center">
						  <input type="checkbox"  name="all_checkbox_30"  onclick="set_all_checkbox_check_flag(this.form, 'thirty_min_table', this)" />
						</td>
                          <td >&nbsp;After 30 min. </td>
                          <td align="right">&nbsp;
                          </td>
                        </tr>
                      </table>
</td>
                  </tr>
				  <tr class="gSGRowOdd">
                    <td>
                      <table id="thirty_min_table" width="100%"  border="1" cellspacing="0" cellpadding="0">
                        <!-- BEGIN thirty_min_table_tr -->
						  <tr id="{ROW_ID}">
						    <td width="25" align="center">
							  <input type="checkbox" value="{ROW_ID}" />
							  <input type="hidden" name="full_command_value[]" value="{FULL_COMMAND_VALUE}" />
						    </td>
						    <td>
							  &nbsp;{COMMAND_DISPLAY_STR}
						    </td>
						  </tr>
						<!-- END thirty_min_table_tr -->
                      </table>
					</td>
                  </tr>
				  <tr>
                    <td class="gSGSectionColumnHeadings">
                      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
						<td width="25" align="center">
						  <input type="checkbox" name="all_checkbox_45" onclick="set_all_checkbox_check_flag(this.form, 'fourty_five_min_table', this)" />
						</td>
                          <td>&nbsp;After 45 min. </td>
                          <td align="right">&nbsp;
                          </td>
                        </tr>
                      </table>
</td>
                  </tr>
				  <tr class="gSGRowOdd">
                    <td>
                      <table id="fourty_five_min_table" width="100%"  border="1" cellspacing="0" cellpadding="0">
                        <!-- BEGIN fourty_five_min_table_tr -->
						  <tr id="{ROW_ID}">
						    <td width="25" align="center">
							  <input type="checkbox" value="{ROW_ID}" />
							  <input type="hidden" name="full_command_value[]" value="{FULL_COMMAND_VALUE}" />
						    </td>
						    <td>
							  &nbsp;{COMMAND_DISPLAY_STR}
						    </td>
						  </tr>
						<!-- END fourty_five_min_table_tr -->
                      </table>
					  </td>
                  </tr>
				  <tr>
                    <td class="gSGSectionColumnHeadings">
                      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
						<td width="25" align="center">
						  <input type="checkbox" name="all_checkbox_60" onclick="set_all_checkbox_check_flag(this.form, 'sisty_min_table', this)" />
						</td>
                          <td>&nbsp;After 60 min. </td>
                          <td align="right">&nbsp;
                          </td>
                        </tr>
                      </table>
</td>
                  </tr>
				  <tr class="gSGRowOdd">
                    <td>
                      <table id="sisty_min_table" width="100%"  border="1" cellspacing="0" cellpadding="0">
                        <!-- BEGIN sisty_min_table_tr -->
						  <tr id="{ROW_ID}">
						    <td width="25" align="center">
							  <input type="checkbox" value="{ROW_ID}" />
							  <input type="hidden" name="full_command_value[]" value="{FULL_COMMAND_VALUE}" />
						    </td>
						    <td>
							  &nbsp;{COMMAND_DISPLAY_STR}
						    </td>
						  </tr>
						<!-- END sisty_min_table_tr -->
                      </table>
					</td>
                  </tr>
				  <tr>
                    <td class="gSGSectionColumnHeadings">
                      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
						<td width="25" align="center">
						  <input type="checkbox" name="all_checkbox_75" onclick="set_all_checkbox_check_flag(this.form, 'seventy_five_min_table', this)" />
						</td>
                          <td >&nbsp;After 75 min. </td>
                          <td align="right">&nbsp;
                          </td>
                        </tr>
                      </table>
</td>
                  </tr>
				  <tr class="gSGRowOdd">
                    <td>
                      <table id="seventy_five_min_table" width="100%"  border="1" cellspacing="0" cellpadding="0">
                        <!-- BEGIN seventy_five_min_table_tr -->
						  <tr id="{ROW_ID}">
						    <td width="25" align="center">
							  <input type="checkbox" value="{ROW_ID}" />
							  <input type="hidden" name="full_command_value[]" value="{FULL_COMMAND_VALUE}" />
						    </td>
						    <td>
							  &nbsp;{COMMAND_DISPLAY_STR}
						    </td>
						  </tr>
						<!-- END seventy_five_min_table_tr -->
                      </table>
					</td>
                  </tr>
				  <tr>
                    <td class="gSGSectionColumnHeadings">
                      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
						<td width="25" align="center">
						  <input type="checkbox" name="all_checkbox_90" onclick="set_all_checkbox_check_flag(this.form, 'ninety_min_table', this)" />
						</td>
                          <td>&nbsp;After 90 min. </td>
                          <td align="right">&nbsp;
                          </td>
                        </tr>
                      </table>
</td>
                  </tr>
				  <tr class="gSGRowOdd">
                    <td>
                      <table id="ninety_min_table" width="100%"  border="1" cellspacing="0" cellpadding="0">
                        <!-- BEGIN ninety_min_table_tr -->
						  <tr id="{ROW_ID}">
						    <td width="25" align="center">
							  <input type="checkbox" value="{ROW_ID}" />
							  <input type="hidden" name="full_command_value[]" value="{FULL_COMMAND_VALUE}" />
						    </td>
						    <td>
							  &nbsp;{COMMAND_DISPLAY_STR}
						    </td>
						  </tr>
						<!-- END ninety_min_table_tr -->
                      </table>
					</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
			
            
          </tr>
          <tr>
           <td valign="top">&nbsp;</td>
			
            <td class="cBBottom"></td>
            <td bgcolor="#cccccc">&nbsp;</td>
            <td class="cBBottom"></td>
			
            
			
			 <td align="center" valign="top" class="gSGRowOdd_input">
              <input type="button" class="button" style="width:90px " value="del selected" onClick="delete_selected_rows(this.form)" />
&nbsp;
              <input type="button" class="button" style="width:90px " name="reduce_command_list" value="clear" onClick="delete_all_rows(this.form)" />
&nbsp;
<input type="submit" class="button" style="width:90px " name="save_command_list" value="save" />
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  
  </form>
  
</table>

<script type="text/javascript" language="javascript">
// when the command type changed, 
// display the correspont window to set the command value
function command_type_change(type_obj)
{
	var selectedIndex = type_obj.selectedIndex;
	
	var div_arr = ["POPSwitch_div", "POPChangeD_div", "POPChangeN_div",
					"Substitution_div", "SubstitutionN_div", "SetTactics_div"];
	var len = div_arr.length;
	for (var i=0; i<len; ++i) {
		var div_name = div_arr[i];
		var div_obj = document.getElementById(div_name);
		if (i == selectedIndex) {
			div_obj.style.display = "";
		}
		else {
			div_obj.style.display = "none";
		}
	}
	
}


// delete selected rows
function delete_selected_rows(form_obj)
{
	var len = form_obj.length;
	var row_id, row_index, pos, table_name, table_obj;
	for (var i=len-1; i>=0; --i) {  
		if((form_obj.elements[i].type).toUpperCase()=="CHECKBOX" && form_obj.elements[i].checked ){		
			if(form_obj.elements[i].value.indexOf("min_table")!=-1) {  
				row_id = form_obj.elements[i].value;
				pos = row_id.indexOf("_row");
				table_name = row_id.substr(0, pos); 
				
				table_obj = document.getElementById(table_name); 
				row_index = getRowIndex(table_obj, row_id);  
				if (row_index == -1) continue;
				
				// delete the selected row
				deleteRow(table_obj, row_index); 
			}
			else if (form_obj.elements[i].name.indexOf("all_checkbox_")!=-1) {
				form_obj.elements[i].checked = false;
			}
		}
	}
}

// get the row_index in the table
function getRowIndex(table_obj, row_id)
{
	var row_index;
	var row_count = table_obj.rows.length;
	for (var i=0; i<row_count; ++i) { 
		if (table_obj.rows[i].id == row_id) {
			return i;
		}
	}
	
	return -1;
}

// delete all rows
function delete_all_rows(form_obj)
{
	var len = cmd_list_table_arr.length;
	var row_id, table_name, table_obj;
	for (var i=len-1; i>=0; --i) {  
		table_name = cmd_list_table_arr[i];
		table_obj = document.getElementById(table_name); 
		
		var row_count = table_obj.rows.length;  
		for (var r=row_count-1; r>=0; --r) {
			row_index = r;
			
			// delete the very row
			deleteRow(table_obj, row_index); 
		}
	}
}

// set the check_flag of all check_box in the min_table
function set_all_checkbox_check_flag(form_obj, table_name, select_obj)
{
	var len = form_obj.length;
	var check_flag = select_obj.checked;
	for (var i=len-1; i>=0; --i) {  
		if((form_obj.elements[i].type).toUpperCase()=="CHECKBOX" && 
		    form_obj.elements[i].value.indexOf(table_name)!=-1) {
			
			form_obj.elements[i].checked = check_flag;
		}
	}
}

// -----------------------------------------------
// get player_on_field_name
function get_player_on_field_name(player_id)
{
	var common_player_on_field_select = document.getElementById("common_player_on_field_select");
	var options_arr = common_player_on_field_select.options;
	var len = options_arr.length;
	for (var i=0; i<len; ++i) {
		if (options_arr[i].value == player_id) {
			return options_arr[i].text;
		}
	}
	
	return "";
}

// get player_sub_name
function get_player_sub_name(player_id)
{
	var common_player_sub_select = document.getElementById("common_player_sub_select");
	var options_arr = common_player_sub_select.options;
	var len = options_arr.length;
	for (var i=0; i<len; ++i) {
		if (options_arr[i].value == player_id) {
			return options_arr[i].text;
		}
	}
	
	return "";
}

// get move_direction_str
function get_move_direction_str(value)
{
	var common_move_direction_arr_select = document.getElementById("common_move_direction_arr_select");
	var options_arr = common_move_direction_arr_select.options;
	var len = options_arr.length;
	for (var i=0; i<len; ++i) {
		if (options_arr[i].value == value) {
			return options_arr[i].text;
		}
	}
	
	return "";
}

// get row_str
function get_row_str(value)
{
	var common_row_arr_select = document.getElementById("common_row_arr_select");
	var options_arr = common_row_arr_select.options;
	var len = options_arr.length;
	for (var i=0; i<len; ++i) {
		if (options_arr[i].value == value) {
			return options_arr[i].text;
		}
	}
	
	return "";
}

// get col_str
function get_col_str(value)
{
	var common_col_arr_select = document.getElementById("common_col_arr_select");
	var options_arr = common_col_arr_select.options;
	var len = options_arr.length;
	for (var i=0; i<len; ++i) {
		if (options_arr[i].value == value) {
			return options_arr[i].text;
		}
	}
	
	return "";
}


// get can_tactics_str
function get_can_tactics_str(value)
{
	var common_can_tactics_arr_select = document.getElementById("common_can_tactics_arr_select");
	var options_arr = common_can_tactics_arr_select.options;
	var len = options_arr.length;
	for (var i=0; i<len; ++i) {
		if (options_arr[i].value == value) {
			return options_arr[i].text;
		}
	}
	
	return "";
}

</script>