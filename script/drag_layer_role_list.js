
var mouse_x = 0;
var mouse_y = 0;
var active_layer = "";
var active_drop_area = "";
var diff_x;
var diff_y;
var last_x;
var last_y;
var limit_max_x;
var limit_min_x;
var limit_max_y;
var limit_min_y;
var active_layer_x = 0;
var active_layer_y = 0;

// 记录setTimeout的返回对象  
var timers = [];

drag_players = new Array();
drop_areas = new Array();
snaps = new Array();

x_snap_lines = new Array();
y_snap_lines = new Array();


var on_pick, on_drag, on_drop;

on_drop = nothing;
on_pick = nothing;
on_drag = nothing;

/**
 * function: to do nothing
 */
function nothing()
{
   //do nothing
}

//default values
limit_min_x = 0;
limit_max_x = 0;
limit_min_y = 300;
limit_max_y = 300;

/**
 * function: to add drop area
 * input params: x1, y1, x2, y2, id
 */
function add_droparea(x1, y1, x2, y2, id)
{
   temp = new Array(5);
   temp[0] = x1;
   temp[1] = y1;
   temp[2] = x2;
   temp[3] = y2;
   temp[4] = id;
   drop_areas[drop_areas.length] = temp;
}

/**
 * function: to add snap area on the field
 */
function add_snap(x, y)
{
   temp = new Array(2)
      temp[0] = x;
   temp[1] = y;
   snaps[snaps.length] = temp;
}

/**
 * function: to add player layer to the "drag_players"
 */
function add_drag(layer)
{
   drag_players[drag_players.length] = layer;
}


/**
 * function: mouse down event handler
 * the active_layer is the layer which the mouse on
 */
function mouse_down(ev)
{ 
	if ((document.all && event.button != 1) || 
	    (document.layers && ev.which != 1))
		return; 
	// judge which layer has been pitched on
	for (var i = 0; i < drag_players.length; i++) {

		if (mouse_x >= layer_get_x(drag_players[i]) && 
		    mouse_x <= (layer_get_x(drag_players[i])+layer_get_w(drag_players[i])+131) &&
		    mouse_y >= layer_get_y(drag_players[i]) && 
		    mouse_y <= (layer_get_y(drag_players[i])+layer_get_h(drag_players[i]))) {
		    // 	        
			active_layer = drag_players[i];
			
			layer_move(active_layer, mouse_x-Math.floor(layer_get_w(active_layer)/2), 
			                         mouse_y-Math.floor(layer_get_h(active_layer)/2));
			
			last_x = layer_get_x(active_layer);  // last_x为被选中的球员层的x坐标，mouse_x 为鼠标的x坐标  
			last_y = layer_get_y(active_layer);  // last_y为被选中的球员层的y坐标，mouse_y 为鼠标的y坐标  
			diff_x = mouse_x - last_x;          // diff_x为鼠标点与层左边界之间的距离  
			diff_y = mouse_y - last_y;
			active_layer_x = last_x;          // 记录层的x坐标  
			active_layer_y = last_y;          // 记录层的y坐标  
			on_pick();
			return;
		}

	}  
	return true;
}

/**
 * function: mouse up event handler
 */
function mouse_up(ev)
{
   var i, j;
   if (active_layer == "")
      return;
   var allowed_move;
   allowed_move = false;
   snap = true;
   snap_player(active_layer);
   on_drop();
   last_x = layer_get_x(active_layer);
   last_y = layer_get_y(active_layer);
   active_layer = "";

   return true;
}

/**
 * function: if the player layer is moved to the snap area,
 *           then the layer is snapped to the area
 */
function snap_player(layer)
{
	var i=0, j=0;
	
	var layer_center_x = layer_get_x(layer) + half_shirt_width;
	var layer_center_y = layer_get_y(layer) + half_shirt_height;
	
	var player_id = layer.replace("p", "");
	var role_type = document.getElementById("role_type_select").value;  
	var table_obj = document.getElementById("table_" + role_type);  
	var table_rows_num = table_obj.rows.length;
	var row_height = 25;
	
	 
	// x轴方向，如果球员在球场内  
	if (layer_center_x > x_snap_lines[0] &&
	    layer_center_x < x_snap_lines[x_snap_lines.length-1]) {  
	    
	    // (1) 判断 table_rows_num == 1，即只有空白行 
	    if (table_rows_num == 1) {
	    	row_index = -1; // at the end
			
			insert_a_role_row(table_obj, row_index, row_height, role_type, player_id);
	    }
		else {
		    
		    // (2) 要判断该球员是否已经在table中了，如果已经在了，则不处理     
		    //     如果不在，则按以下两步来处理 a), b)   
		    var element_name = "player_ids_" + role_type + "[]";
		    var player_ids = document.getElementsByName(element_name); 
		    var have_exist = false;   
		    for (var i=0; i<player_ids.length; ++i) { 
		    	if (player_ids[i].value == player_id) {  
		    		have_exist = true;
		    		break;	
		    	}	
		    }
		    
		    if (!have_exist) {
			    // a) layer_center_y 在table_obj中的某一行上，则在该行前插入一行，放置球员   
			    var row_index = 0;
			    row_index = layer_center_y / row_height;
			    if (row_index < table_rows_num) {
			    	    			
	    			row_index = row_index - 1; 
			
					insert_a_role_row(table_obj, row_index, row_height, role_type, player_id);
	    	
			    }
			    else { 
			    	// b) layer_center_y 没有在表格上，就在表格的最后位置插入一行，放置球员   
			    	
	    			row_index = - 1; // at the end
			
					insert_a_role_row(table_obj, row_index, row_height, role_type, player_id);
			    	
			    }
		    }
	    
	    }
	    
	    // 将空白行隐藏    
	    var blank_tr_obj = document.getElementById("blank_tr_" + role_type);  
	    blank_tr_obj.style.display = "none";
	    
	}
	
	// 重组所有的index_select控件     
	arrange_all_index_select(table_obj, role_type);

	// 最后，降所有的拖动过的层回归到它原来的位置，以便下一次拖动   
	reset_player(layer);  
	
	// 在函数的末尾，给变量 selectValues_indexs 赋值  
	formTheSelectValues_indexs();  
}

/**
 * function: insert a role row into table_obj
 */
function insert_a_role_row(table_obj, row_index, row_height, role_type, player_id)
{
	var row_obj = table_obj.insertRow(row_index);
	var row_id = role_type + "_row_" + (new Date()).getTime(); 
	var index_select_id = "index_select_" + (new Date()).getTime();    
		    	
	row_obj.height = row_height;
	row_obj.id = row_id;
	
	// col 1
	var col_obj = row_obj.insertCell(-1);
	col_obj.className = "gSGRowOdd"
	col_obj.width = "25px"
	col_obj.innerHTML = '<input type="checkbox" value="' + row_id + '" />' + 
						'<input type="hidden" name="player_ids_' + role_type + '[]" value="' + player_id + '" />';
	// col 2
	col_obj = row_obj.insertCell(-1);
	col_obj.width = "65px";
	col_obj.className = "gSGRowOdd"
	col_html = "&nbsp;<select style=\"width:55px\" id=\"" + index_select_id + "\" name=\"index_select_" + role_type + "[]\" onChange=\"index_select_change('" + role_type + "', this.form, this)\" >";
	
	col_html += '</select>';
	col_obj.innerHTML = col_html;
	
	// col 3
	col_obj = row_obj.insertCell(-1);
	col_obj.className = "gSGRowOdd";
	
	col_obj.wrap = true;
	col_obj.innerHTML = "&nbsp;" + get_player_name(player_id);	
	
	
	// col 3
	col_obj = row_obj.insertCell(-1);
	col_obj.className = "gSGRowOdd";
	col_obj.width = "28px";
	col_obj.innerHTML = "<input type=\"button\" name=\"delete_button_" + role_type + "\" value=\"del\" onClick=\"delete_selected_role('" + role_type + "', '" + row_id + "')\" />";	
	
	
}


// arrange all index_select      
function arrange_all_index_select(table_obj, role_type)
{
	
	var table_rows_num = table_obj.rows.length;
	
	var select_name = "index_select_" + role_type + "[]";  
	var index_select_arr = document.getElementsByName(select_name);	  
	var index_select_arr_len = index_select_arr.length;
	for (var i=0; i<index_select_arr_len; ++i) {
		var select_obj = index_select_arr[i];  
		
		
		arrange_each_index_select(select_obj, index_select_arr_len, i);	
	}
	
}

// arrange each index_select      
function arrange_each_index_select(select_obj, index_select_arr_len, index_in_role_type)
{
	select_obj.options.length = 0;  
	
	for (var i=0; i<index_select_arr_len; ++i) { 		
		
		var option_value = i + 1;		
		
		var new_option;
		new_option = document.createElement("OPTION"); 
		select_obj.add(new_option);
		
		// 注意：以下这些要放在add函数之后	 
		new_option.text = option_value;  
		new_option.value = option_value;   
		if (i == index_in_role_type) {
			new_option.selected = true;
		}
		else {
			new_option.selected = false;
		}
	}
	
}


/**
 * function: mouse move event handler
 */
function mouse_move(ev)
{ 
	if (document.all) {
		mouse_x = parseFloat(window.event.x) + parseFloat(document.body.scrollLeft);
		mouse_y = parseFloat(window.event.y) + parseFloat(document.body.scrollTop);
	}
	else if (document.layers || document.getElementById) {
		mouse_x = ev.pageX;
		mouse_y = ev.pageY;
	}
	var i, mx, my;
	
	if (active_layer != "") {
		mx = mouse_x - diff_x;
		my = mouse_y - diff_y;
		if (mx < limit_min_x)
			mx = limit_min_x;
		if (mx > (limit_max_x-layer_get_w(active_layer)))
			mx = limit_max_x-layer_get_w(active_layer);
		if (my < limit_min_y)
			my = limit_min_y;
		if (my > (limit_max_y-layer_get_h(active_layer)))
			my = limit_max_y-layer_get_h(active_layer);
			
		layer_move(active_layer,mx,my);
		active_layer_x = mx;
		active_layer_y = my;
		on_drag(); // 如果选择了某个运动员层的话，就拖着它运动  
		return false;
	}
	else {
		return true;
	}
}

/**
 * function: the auto run of the page event 
 */
if (document.all || document.getElementById)
{
   document.onmousemove = mouse_move;
}
else if (document.layers)
{
   window.captureEvents(Event.MOUSEMOVE);
   window.onMouseMove = mouse_move;
}
if (document.all || document.getElementById)
{
   document.onmousedown = mouse_down;
}
else if (document.layers)
{
   window.captureEvents(Event.MOUSEDOWN);
   window.onMouseDown = mouse_down;
}

if (document.all || document.getElementById)
{
   document.onmouseup = mouse_up;
}
else if (document.layers)
{
   window.captureEvents(Event.MOUSEUP);
   window.onMouseUp = mouse_up;
}

/**
 * function: get the info of layer (x, y, w, h)
 */
function layer_get_x(layer)
{
   if (document.all)
      return document.all[layer].style.pixelLeft;
   else if (document.layers)
      return document.layers[layer].left;
   else if (document.getElementById)
      return parseInt(document.getElementById(layer).style.left);
}

function layer_get_y(layer)
{
   if (document.all)
      return document.all[layer].style.pixelTop;
   else if (document.layers)
      return document.layers[layer].top;
   else if (document.getElementById)
      return parseInt(document.getElementById(layer).style.top);
}
function layer_get_w(layer)
{
  if (document.all)
    return document.all[layer].style.pixelWidth;
  else if (document.layers)
    return document.layers[layer].clip.width;
  else if (document.getElementById)
    return parseInt(document.getElementById(layer).style.width);
}
function layer_get_h(layer)
{
   if (document.all)
      return document.all[layer].style.pixelHeight;
   else if (document.layers)
      return document.layers[layer].clip.height;
   else if (document.getElementById)
      return parseInt(document.getElementById(layer).style.height);
}


/**
 * function: move the layer
 * (be compatible to different explore)
 */
function layer_move(layer, x, y, hidden)
{
  if (document.all)
  {
    document.all[layer].style.pixelLeft=x;
    document.all[layer].style.pixelTop=y;
    if(hidden) {
      document.all[layer].style.visibility="hidden";
    } else {
      document.all[layer].style.visibility="visible";
    }
  }
  else if (document.layers)
  {
    document.layers[layer].left = x;
    document.layers[layer].top = y;
    if(hidden) {
      document.all[layer].visibility="hidden";
    } else {
      document.all[layer].visibility="show";
    }
  }
  else if (document.getElementById)
  {
    document.getElementById(layer).style.left = x + "px"; // add the "unit"
    document.getElementById(layer).style.top = y + "px";
    if(hidden) {
      document.getElementById(layer).style.visibility="hidden";
    } else {
      document.getElementById(layer).style.visibility="visible";
    }
  }
}

/**
 * function: hide the layer
 */
function layer_hide(layer)
{

   if (document.layers)
      document.layers[layer].visibility = "hide";
   else if(document.all)
      document.all(layer).style.visibility = "hidden";
   else if(document.getElementById)
      document.getElementById(layer).style.visibility = "hidden";

}

/**
 * function: show the layer
 */
function layer_show(layer)
{

   if (document.layers)
      document.layers[layer].visibility = "show";
   else if(document.all) 
      document.all(layer).style.visibility = "visible";
   else if(document.getElementById)
      document.getElementById(layer).style.visibility = "visible";
    
}

/**
 * function: display the content of "str" on the "layer"
 */
function layer_write(layer, str)
{
  if(document.all) {
    document.all(layer).innerHTML = str;
  }
  else if (navigator.userAgent.indexOf("Netscape6")>0 || 
           (document.getElementById)) {
    document.getElementById(layer).innerHTML = str;
  }
  else if(document.layers) {
    document[layer].document.open();
    document[layer].document.write(str);
    document[layer].document.close();
  }
}



