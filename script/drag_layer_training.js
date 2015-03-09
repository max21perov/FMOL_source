
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
{ //alert(mouse_y)
	if ((document.all && event.button != 1) || 
	    (document.layers && ev.which != 1))
		return; 
	// judge which layer has been pitched on
	for (var i = 0; i < drag_players.length; i++) {
		if (drag_players[i].indexOf("_div_") != -1) { // 判定是prompt 层  
			if (mouse_x >= layer_get_x(drag_players[i]) && 
				mouse_x <= (layer_get_x(drag_players[i])+layer_get_w(drag_players[i])) &&
				mouse_y >= layer_get_y(drag_players[i]) && 
				mouse_y <= (layer_get_y(drag_players[i])+layer_get_h(drag_players[i]))) {
				//
				active_layer = drag_players[i]; 
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
		else {
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
	// x轴方向，如果球员在球场内  
	if (layer_center_x < x_snap_lines[x_snap_lines.length-1]) {
		for (i=0; i<y_snap_lines.length-1; ++i) {
			if (layer_center_y >= y_snap_lines[i] && layer_center_y < y_snap_lines[i+1]) {
				move_to_row(i, layer);
				break;
			}
		} // for
	} // if (layer_center_x < x_snap_lines[0][x_snap_lines[0].length-1])
	else { // move the layer to the player list
		// 如果是球场内的球员放到 player list 中
		if (layer.indexOf("_div_") != -1) {  // move the player from the inside of the field to the player list
			// out of the field (x direction)
			reset_player(layer); 
		}
		else { // move the layer of the player list inside the player list 
			// 如果是player list 中的球员互换  
			// exchange the layers of the player list
			exchange_layer_of_player_list(layer);
		}    
	} // else
	
	// 更新球场上球员的显示，以及更新球员列表  
	check_update_snaps(); 
}

/**
 * function: given the row of the layer, now decide the col place of the layer
 * input parameter: row - row of the layer; layer - the layer id;
 */
function move_to_row(row, layer)
{
	var j;
	var i = row;
	var layer_center_x = layer_get_x(layer) + half_shirt_width;
	
	var in_the_field_flag = 0;
	
	var item_index = i + 1; 
	if (layer.indexOf("_div_") != -1) { // 判定是prompt 层  
		player_id = layer.replace("player_div_", "");
	}
	else {
		player_id = layer.replace("p", "");
	}
	// 判断该训练项目时候已经够人了（10人为上限）  
	var len = eval("ppTable_" + item_index + ".length");
	var table_obj = eval("ppTable_" + item_index);
	var player_num = get_player_num_in_item(table_obj);
	if (player_num >= 10) {
		// 判断移动的球员是否已经在训练项目中  
		var is_in_table = false;
		for (var m=0; m<len; ++m) {
			if (player_id == table_obj[m]) {
				is_in_table = true;
				break;
			}
		}
		
		if (!is_in_table) {
			alert("Item (" + item_index + ") now has 10 players, \nit can not have more than 10 players. ");
			reset_player(layer);
		}
		
		return;
	}
	
	// 将该球员退出其他训练项目中  
	var have_removed = remove_player_from_other_item(player_id);
	if (!have_removed)
		remove_player_from_free_item(player_id);
	// 将该球员放入相应的训练项目中  
	for (var m=0; m<len; ++m) {
		if (table_obj[m] <= 0) { 
			table_obj[m] = player_id; 
			break;
		}
	}
	
	
}

/**
 * function: 取得训练项目中球员的数目  
 */
function get_player_num_in_item(table_obj)
{
	var player_num = 0;
	for (var m=0; m<table_obj.length; ++m) {
		if (table_obj[m] > 0) 
			++ player_num;
	}	
	return player_num;
}

/**
 * function: 将该球员退出其他训练项目中  
 */
function remove_player_from_other_item(player_id)
{
	var have_removed = false;
	for (var item_index=1; item_index<=5; ++item_index) {
		if (have_removed) break;
		
		var table_obj = eval("ppTable_" + item_index);
		for (var m=0; m<table_obj.length; ++m) {
			if (table_obj[m] == player_id) {
				table_obj[m] = -1;
				have_removed = true;
				return have_removed;
			}
		}	
	}		
	
	return have_removed;
}

/**
 * function: 将该球员从 ppTable_free 中清除  
 */
function remove_player_from_free_item(player_id)
{
	var table_obj = eval("ppTable_" + "free");
	for (var m=0; m<table_obj.length; ++m) {
		if (table_obj[m] == player_id) {
			table_obj[m] = -1;
			break;
		}
	}
}

/**
 * function: 将该球员插入到 ppTable_free 中  
 */
function insert_player_into_free_item(player_id)
{
	var table_obj = eval("ppTable_" + "free");
	var can_insert = false;
	for (var m=0; m<table_obj.length; ++m) {
		if (table_obj[m] == -1) {
			table_obj[m] = player_id;
			can_insert = true;
			break;
		}
	}
	
	if (!can_insert) {
		table_obj[table_obj.length] = player_id;
	}
}

/**
 * function: exchange the layers of the player list
 */
function exchange_layer_of_player_list(layer)
{
	var layer_moved = layer;
	
	var layer_center_y = layer_get_y(layer) + half_shirt_height;
		
	var data = document.save_form.players.value;
	if (data == "") return;
	var players = data.split(",");
	for (var i=0; i<players.length; ++i) { // all the layer in the player list
		var layer_at_list = "p" + players[i];
		if (layer_moved == layer_at_list) {continue;} // the same layer
		
		if (layer_center_y > layer_get_y(layer_at_list) && 
			layer_center_y <= (layer_get_y(layer_at_list)+layer_get_h(layer_at_list))) {
			
			var player_id_moved = layer_moved.replace("p", "");
			var player_id_at_list = layer_at_list.replace("p", "");
			var continue_flag_moved = 1;
			var continue_flag_at_list = 1;
			var r_index_moved = -1;
			var r_index_at_list = -1;
			
			// 在这里判断球员是否在训练项目中  
			var table_obj_moved, table_obj_at_list;
			for (var item_index=1; item_index<=5; ++item_index) {
				if (continue_flag_moved == 0 && continue_flag_at_list == 0) break;
				var table_obj = eval("ppTable_" + item_index);
				for (var m=0; m<table_obj.length; ++m) {
					if (table_obj[m] == player_id_moved) {
						r_index_moved = m;
						table_obj_moved = table_obj;
						continue_flag_moved = 0;
					}
					if (table_obj[m] == player_id_at_list) {
						r_index_at_list = m;
						table_obj_at_list = table_obj;
						continue_flag_at_list = 0;
					}
					if (continue_flag_moved == 0 && continue_flag_at_list == 0) break;
				}
			}
			if (continue_flag_moved != 0 || continue_flag_at_list != 0) {
				var table_obj = eval("ppTable_" + "free");
				for (var m=0; m<table_obj.length; ++m) {
					if (table_obj[m] == player_id_moved) {
						r_index_moved = m;
						table_obj_moved = table_obj;
						continue_flag_moved = 0;
					}
					if (table_obj[m] == player_id_at_list) {
						r_index_at_list = m;
						table_obj_at_list = table_obj;
						continue_flag_at_list = 0;
					}
					if (continue_flag_moved == 0 && continue_flag_at_list == 0) break;
				}
			}
			
			

			
			if (r_index_moved != -1 && r_index_at_list != -1) {
				table_obj_moved[r_index_moved] = player_id_at_list;
				table_obj_at_list[r_index_at_list] = player_id_moved;
				
			}
			
			break;
		} // if
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

/**
 * function: hide the layer
 */
function layer_change_color(layer, color)
{
    if (document.layers) { 
    	document.layers[layer].color = color;
    }
    else if(document.all) { 
    	document.all(layer).style.color = color;
    }
   	else if(document.getElementById) { 
    	document.getElementById(layer).style.color = color;
    }
}

