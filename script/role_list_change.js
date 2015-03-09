// ȫ�ֱ���  
var pre_str_indexs = "index_select_";
var selectValues_indexs = new Array();



// ��ҳ���ύǰ����ҳ���ϵ����ݽ���У��  
function beforeSubmit_indexs(form_obj) 
{
	
	var all_role_types = form_obj.elements["all_role_types"].value;
	var all_role_types_arr = all_role_types.split("|");
	var len = all_role_types_arr.length;
	
	for (var i=0; i<len; ++i) {
		var role_type = all_role_types_arr[i];
		
		var index_select_arr = document.getElementsByName("index_select_" + role_type + "[]");
		var index_select_arr_len = index_select_arr.length;
		var selectedIndexArr = [];
		
		for (var j=0; j<index_select_arr_len; ++j) {
			var selectedIndex = index_select_arr[j].selectedIndex;
			
			if (isInTheArr(selectedIndexArr, selectedIndex)) {
				alert("Deferent item must have deferent coach.");
				return false;
			}
			else {
				selectedIndexArr[selectedIndexArr.length] = selectedIndex;
			}
		}
	}
	
	return true;
}

// �ж� value �Ƿ������� arr ��  
// ����ڣ����� true, ���� false  
function isInTheArr(arr, value) 
{
	for (var i=0; i<arr.length; ++i) {
		if (value == arr[i]) {
			return true;
		}
	}
	
	return false;
}

// ----------------------------------------------------------------

// when the role type changed, 
// display the correspont window to set the command value
function role_type_select_change(select_obj)
{
	var selectedIndex = select_obj.selectedIndex;
	
	var len = select_obj.options.length;
	for (var i=0; i<len; ++i) {
		var role_type = select_obj.options[i].value;
		var table_obj = document.getElementById("table_" + role_type);
		
		if (i == selectedIndex) {  
			table_obj.style.display = "";
		}
		else {
			table_obj.style.display = "none";
		}
	}
	
}



// ----------------------------------------------------------------
// ������ selectValues_indexs ��ֵ  
function formTheSelectValues_indexs()
{
	selectValues_indexs = [];
	
	
	var form_obj = document.forms["save_form"];    
	var role_type = document.getElementById("role_type_select").value;
	
	var index_select_arr = document.getElementsByName(pre_str_indexs + role_type + "[]");
	var index_select_arr_len = index_select_arr.length;
	
	for (i = 0; i < index_select_arr_len; ++i) {		
		obj = index_select_arr[i];
		selectValues_indexs[selectValues_indexs.length] = obj.selectedIndex;
	}
	
}


// the event when the coach changed  
function index_select_change(role_type, form_obj, select_obj)
{
	
	my_index  = select_obj.selectedIndex;
	my_obj_id = select_obj.id;
    	
	var index_select_arr = document.getElementsByName(pre_str_indexs + role_type + "[]");
	var index_select_arr_len = index_select_arr.length;  
	
	// �������е�place_select��ȡ�����ǵ�selectedIndex�� ��selectValues���޳���ֵ  
	var target_select_obj_id = "";
	
	for (var i = 0; i < index_select_arr_len; ++i) {		
		index = index_select_arr[i].selectedIndex; 
		obj_id = index_select_arr[i].id;  
		for (var j=0; j<selectValues_indexs.length; ++j) { 
			if (index == selectValues_indexs[j]) {
				if (my_index != selectValues_indexs[j]) {
		    		selectValues_indexs[j] = -1;
		    		break;
		    	}
		    	else if (obj_id == my_obj_id) {
		    		selectValues_indexs[j] = -1;
		    		break;
		    	}
			}
		}
		
		if (index == my_index && obj_id != my_obj_id) {  
			target_select_obj_id = obj_id;
		}
		
	}
		
	// ����selectValues ��ֻʣ��һ����ֵ�ˣ�����ֵ��������һ����Ҫ����Ӧ�޸ĵ�place_select ��selectedIndex  
	target_select_index = 0;
	for (var j=0; j<selectValues_indexs.length; ++j) {
		if (selectValues_indexs[j] != -1) { 
			target_select_index = selectValues_indexs[j];
			break;
		}	
	}
   
	if (target_select_obj_id != "") {
		document.getElementById(target_select_obj_id).selectedIndex = target_select_index;
	}
	
	// �ں�����ĩβ�������� selectValues_indexs ��ֵ  
	formTheSelectValues_indexs();  
	
	return true;
}

// delete the selected role row
function delete_selected_role(role_type, row_id)
{
	
	var table_obj = document.getElementById("table_" + role_type);  
	var len = table_obj.rows.length;  
	for (var i=1; i<len; ++i) {  // because the first line is blank line  
		
		if (table_obj.rows[i].id == row_id) {  
			table_obj.deleteRow(i); 
			break;	
		}	
	}	
	
	// if only blank_line left, show the blank_line
	if (table_obj.rows.length == 1) {
		table_obj.rows[0].style.display = "block";	
	}
		
	
	// because the row have reduce, then arrange all index_select    
	arrange_all_index_select(table_obj, role_type);
}


// delete selected rows
function delete_selected_rows(form_obj)
{
	var len = form_obj.length;
	var row_id;
	var role_type = document.getElementById("role_type_select").value;
	for (var i=len-1; i>=0; --i) {  
		if((form_obj.elements[i].type).toUpperCase()=="CHECKBOX" && form_obj.elements[i].checked ){		
			
				row_id = form_obj.elements[i].value;
				delete_selected_role(role_type, row_id);
			
		}
	}
}



// delete all rows of one role_type
function delete_all_rows(form_obj)
{
	
	var role_type = document.getElementById("role_type_select").value;
	var table_obj = document.getElementById("table_" + role_type);  

	var row_count = table_obj.rows.length;    
	for (var r=row_count-1; r>=1; --r) {  // because the first row is blank_row
		// delete the very row
		table_obj.deleteRow(r); 
		
	}
	
	// display the blank_row
	table_obj.rows[0].style.display = "block";
	
}



