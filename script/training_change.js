// 全局变量  
var pre_str_items = "content_select_";
var pre_str_coaches = "coach_select_";
var selectValues_coaches = new Array();
var selectValues_items = new Array();

// 在页面提交前，对页面上的内容进行校验  
function beforeSubmit_items(formObj) 
{
	var selectedIndexArr = [];
	for (var i=0; i<formObj.elements.length; ++i) {
		if (formObj.elements[i].name.substr(0, pre_str_items.length) == pre_str_items) {
			var selectedIndex = formObj.elements[i].selectedIndex;
			if (isInTheArr(selectedIndexArr, selectedIndex)) {
				alert("Deferent item must have deferent content.");
				return false;
			}
			else {
				selectedIndexArr[selectedIndexArr.length] = selectedIndex;
			}
		}
	}
	
	return true;
}

// 在页面提交前，对页面上的内容进行校验  
function beforeSubmit_coaches(formObj) 
{
	var selectedIndexArr = [];
	for (var i=0; i<formObj.elements.length; ++i) {
		if (formObj.elements[i].name.substr(0, pre_str_coaches.length) == pre_str_coaches) {
			var selectedIndex = formObj.elements[i].selectedIndex;
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

// 判断 value 是否在数组 arr 中  
// 如果在，返回 true, 否则 false  
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
// 全局变量  


// 给变量 selectValues_items 赋值  
function formTheSelectValues_items()
{
	selectValues_items = [];
	for (i = 0; i < document.save_form.elements.length; i++) {
		select = document.save_form.elements[i].name;
		if (select.substring(0, pre_str_items.length) == pre_str_items) { 
			obj = document.save_form.elements[i];
		   	selectValues_items[selectValues_items.length] = obj.selectedIndex;
		}
	}
}
 
// the event when the content changed 
function content_change(select_obj) {
	my_index  = select_obj.selectedIndex;
	my_name   = select_obj.name;
	
	// 遍历所有的place_select，取出它们的selectedIndex， 从selectValues中剔除该值  
	target_select_name = "";
	for (i = 0; i < document.save_form.elements.length; i++) {
		select = document.save_form.elements[i].name;
		if (select.substring(0,pre_str_items.length) == pre_str_items) {
		   	index = document.save_form.elements[i].selectedIndex;
		   	name = document.save_form.elements[i].name;
		    for (var j=0; j<selectValues_items.length; ++j) {
		    	if (index == selectValues_items[j]) {
		    		if (my_index != selectValues_items[j]) {
			    		selectValues_items[j] = -1;
			    		break;
			    	}
			    	else if (name == my_name) {
			    		selectValues_items[j] = -1;
			    		break;
			    	}
		    	}
		    }
		    
		    if (index == my_index && name != my_name) {
		    	target_select_name = name;
		    }
		}
	}
	
	// 现在selectValues 中只剩下一个数值了，该数值就是另外一个需要做相应修改的place_select 的selectedIndex  
	target_select_index = 0;
	for (var j=0; j<selectValues_items.length; ++j) {
		if (selectValues_items[j] != -1) {
			target_select_index = selectValues_items[j];
		}	
	}
	if (target_select_name != "") 
	  	eval("document.save_form." + target_select_name + ".selectedIndex=" + target_select_index);
	
	// 在函数的末尾，给变量 selectValues_items 赋值  
	formTheSelectValues_items();
	
	return true;
} 


// ----------------------------------------------------------------
// 给变量 selectValues_coaches 赋值  
function formTheSelectValues_coaches()
{
	selectValues_coaches = [];
	for (i = 0; i < document.save_form.elements.length; i++) {
		select = document.save_form.elements[i].name;
		if (select.substring(0, pre_str_coaches.length) == pre_str_coaches && 
			document.save_form.elements[i].selectedIndex != 0 ) { 
			// document.save_form.elements[i].selectedIndex == 0 表示该训练项目没有选择教练  
			obj = document.save_form.elements[i];
		   	selectValues_coaches[selectValues_coaches.length] = obj.selectedIndex;
		}
	}
}

// the event when the coach changed  
function coach_change(select_obj)
{
	if (select_obj.selectedIndex == 0) {
		// selectedIndex == 0 表示该训练项目没有选择教练  
		
		// 在函数的末尾，给变量 selectValues_coaches 赋值  
		formTheSelectValues_coaches();  
		return;
	}
	
	my_index  = select_obj.selectedIndex;
	my_name   = select_obj.name;
	
	// 遍历所有的place_select，取出它们的selectedIndex， 从selectValues中剔除该值  
	target_select_name = "";
	for (i = 0; i < document.save_form.elements.length; i++) {
		select = document.save_form.elements[i].name;
		if (select.substring(0,pre_str_coaches.length) == pre_str_coaches) {
		   	index = document.save_form.elements[i].selectedIndex;
		   	name = document.save_form.elements[i].name;
		    for (var j=0; j<selectValues_coaches.length; ++j) {
		    	if (index == selectValues_coaches[j]) {
		    		if (my_index != selectValues_coaches[j]) {
			    		selectValues_coaches[j] = -1;
			    		break;
			    	}
			    	else if (name == my_name) {
			    		selectValues_coaches[j] = -1;
			    		break;
			    	}
		    	}
		    }
		    
		    if (index == my_index && name != my_name) {
		    	target_select_name = name;
		    }
		}
	}
	
	// 现在selectValues 中只剩下一个数值了，该数值就是另外一个需要做相应修改的place_select 的selectedIndex  
	target_select_index = 0;
	for (var j=0; j<selectValues_coaches.length; ++j) {
		if (selectValues_coaches[j] != -1) {
			target_select_index = selectValues_coaches[j];
		}	
	}
	if (target_select_name != "") 
	  	eval("document.save_form." + target_select_name + ".selectedIndex=" + target_select_index);
	
	// 在函数的末尾，给变量 selectValues_coaches 赋值  
	formTheSelectValues_coaches();  
	
	return true;
}



