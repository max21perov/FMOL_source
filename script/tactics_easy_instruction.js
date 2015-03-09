// +----------------------------------------------+
// | set the instruction of the tactics_easy page |
// +----------------------------------------------+

// show the player instrctuion
var pre_tr_obj = "";
var pre_tr_obj_className = "";
function showPlayerInstruction(tr_obj, select_obj_name, player_id)
{ 
	
	// change the class of the tr
	if (pre_tr_obj != "")
		pre_tr_obj.className = pre_tr_obj_className;
	pre_tr_obj = tr_obj;
	pre_tr_obj_className = tr_obj.className;
	tr_obj.className = "gSGRowSelected_input";
	
	
	var form_obj = document.forms["save_form"];
	var selectedIndex = form_obj.elements[select_obj_name].selectedIndex;
		
	// hide the team instruction
	var hide_flag = "none";
	hideTeamInstruction(hide_flag);	
	
	// only handle the players on the field
	// do not include GK
	if (selectedIndex <= 0 || selectedIndex > 10) {
		
		// handle all player instructions 
		hideAllPlayerInstruction();
	}
	else {
		
		// handle all player instructions 
		hideAllPlayerInstruction();
		// show the specific player instruction
		// *** be careful: if selectedIndex = 1, it corresponds to pop_id = 10
		// so the condition is : selectedIndex == (10-i+1)
		var div_id = "instruction_" + (10-selectedIndex+1);
		var div_obj = document.getElementById(div_id); 
		div_obj.style.display = "";
	}
	
	// show the player_property_div
	var div_id = "player_property_div";
	var div_obj = document.getElementById(div_id); 
	div_obj.style.display = "";
	var span_id = "player_property_span";  
    resetPlayerProperty(player_id, span_id, selectedIndex);
	
}

// show the team instruction
function showTeamInstruction(tr_obj)
{
	// hide the team instruction
	var hide_flag = "";
	hideTeamInstruction(hide_flag);
	
	// handle all player instructions 
	hideAllPlayerInstruction();
	
	// hide the player_property_div
	var div_id = "player_property_div";
	var div_obj = document.getElementById(div_id); 
	div_obj.style.display = "none";
	
	// change the class of the tr
	if (pre_tr_obj != "")
		pre_tr_obj.className = pre_tr_obj_className;
	pre_tr_obj = tr_obj;
	pre_tr_obj_className = tr_obj.className;
	tr_obj.className = "gSGRowSelected_input";
}


// hide the team instruction (or show)
function hideTeamInstruction(hide_flag)
{
	var div_id = "instruction_team";
	var div_obj = document.getElementById(div_id);
	div_obj.style.display = hide_flag;
}

// handle all player instructions 
function hideAllPlayerInstruction()
{ 
	for (var i=1; i<=10; ++i) {
		var div_id = "instruction_" + i;
		var div_obj = document.getElementById(div_id);
		div_obj.style.display = "none";
	}	
}
	
// reset the player property of this be_showed div
function resetPlayerProperty(player_id, span_id, selectedIndex)
{ 
	var span_obj = document.getElementById(span_id);  
	
	span_obj.innerHTML = "";
	if (selectedIndex <= 0 || selectedIndex > 10) {
		var html_str = "";
		html_str = getBlankPlayerInstuctionHTML();
		span_obj.innerHTML = html_str;
	}
	span_obj.appendChild(getPlayerPropertyTable(player_id));   // getPlayerPropertyTable is defined in file "tactics_functions.js"
	
}

function getBlankPlayerInstuctionHTML()
{
	var html_str = "";
	html_str = "<table width='100%'  border='0' cellpadding='0' cellspacing='0'>";
	html_str += "<tr><td class=gSGSectionColumnHeadings></td></tr>";
	for (var i=0; i<6; ++i) {
		html_str += "<tr><td class=gSGRowEven_input></td></tr>";
	}
	html_str += "<tr><td bgcolor='#e0dfe3' height='2'></td></tr>";
	html_str += "<tr><td class=gSGRowEven_input></td></tr>";
	html_str += "<tr><td bgcolor='#e0dfe3' height='6'></td></tr>";
	html_str += "</table>";
	
	return html_str;
}


// reset player instruction
function resetPlayerInstruction(pop_id)
{
	var reset_select_name = "reset_select_" + pop_id;
	var forward_run_name = "forward_run_" + pop_id;
	var run_with_ball_name = "run_with_ball_" + pop_id;
	var long_shot_name = "long_shot_" + pop_id;
	var hold_the_ball_name = "hold_the_ball_" + pop_id;
	var through_pass_name = "through_pass_" + pop_id;
	var crossing_name = "crossing_" + pop_id;
	var id_arr = [forward_run_name, run_with_ball_name, long_shot_name,
				  hold_the_ball_name, through_pass_name, crossing_name];
	
	var form_obj = document.forms["save_form"];
	var reset_select_obj = form_obj.elements[reset_select_name];  
	var reset_select_value = reset_select_obj.value;
	
	var value_arr = eval("p_i_" + reset_select_value);
	setSelectedObjValue(form_obj, id_arr, value_arr);
	
}

function setSelectedObjValue(form_obj, id_arr, value_arr)
{
	var len = id_arr.length;
	for (var i=0; i<len; ++i) {
		var obj_name = id_arr[i];
		var obj_new_value = value_arr[i];
		
		var obj = form_obj.elements[obj_name]; 
		var options = obj.options;
		var options_len = options.length;
		for (var j=0; j<options_len; ++j) {
			if (options[j].value == obj_new_value) {
				options[j].selected = true;
				
				break;	
			}
		}
	}
}


