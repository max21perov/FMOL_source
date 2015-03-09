/**
 * code type: javascript code
 * function: to drag the layer
 */

var mouse_x = 0;
var mouse_y = 0;
var active_layer = "";
var active_drop_area = "";
var diff_x;
var diff_y;
var last_x;
var last_y;
var mouse_down_x;
var mouse_down_y;
var mouse_up_x;
var mouse_up_y;
var active_layer_w;
var active_layer_h;
var mouse_down_r;
var mouse_down_c;
var mouse_up_r;
var mouse_up_c;

var left_mouse_down_r, left_mouse_down_c;

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

five_x_snap_lines = new Array();
four_x_snap_lines = new Array();
one_x_snap_lines = new Array();
x_snap_lines = new Array();
for (var i=0; i<5; ++i) {
    x_snap_lines[i] = new Array();
}
y_snap_lines = new Array();

five_x_snap_places = new Array();
four_x_snap_places = new Array();
one_x_snap_places = new Array();
y_snap_places = new Array();
snap_places = new Array();
for (var i=0; i<6; ++i) {
    snap_places[i] = new Array();
}

player_formation = new Array();
for (var i=0; i<6; ++i) {
    player_formation[i] = new Array();
}
formation_subs = new Array();
formation_others = new Array();

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
	// 根据 choose_left_key_function 的值来判断是移动球员还是画球员跑动路线  
	var b_draw_run_direction = document.save_form.elements["choose_left_key_function"][1].checked;	

    if ( b_draw_run_direction ) {     
     	right_mouse_down(ev); 
     	return;	
    }
    
  if ((document.all && event.button != 1) || 
      (document.layers && ev.which != 1)) {
    
    return; 
    
  }  
  // judge which layer has been pitched on
  for (var i = 0; i < drag_players.length; i++) {
    if (drag_players[i].indexOf("_p_") != -1) { // 判定是prompt 层  
      if (mouse_x >= layer_get_x(drag_players[i]) && 
          mouse_x <= (layer_get_x(drag_players[i])+layer_get_w(drag_players[i])) &&
          mouse_y >= layer_get_y(drag_players[i]) && 
          mouse_y <= (layer_get_y(drag_players[i])+layer_get_h(drag_players[i]))) {
        
        var row_col_arr = get_row_col(drag_players[i]); // 取得这个prompt层的行号、列号  
        var r_index = row_col_arr[0];
        var c_index = row_col_arr[1];
        if (player_formation[r_index][c_index] == 0) // 如果这个prompt层没有显示的话，就返回  
          return;
        
        
        active_layer = drag_players[i];
        last_x = layer_get_x(active_layer);  // last_x为被选中的球员层的x坐标，mouse_x 为鼠标的x坐标  
        last_y = layer_get_y(active_layer);  // last_y为被选中的球员层的y坐标，mouse_y 为鼠标的y坐标  
        diff_x = mouse_x - last_x;          // diff_x为鼠标点与层左边界之间的距离  
        diff_y = mouse_y - last_y;
        active_layer_x = last_x;          // 记录层的x坐标  
        active_layer_y = last_y;          // 记录层的y坐标  
        
        left_mouse_down_r = r_index;
        left_mouse_down_c = c_index; 
        
        on_pick();
        return;
      }
    } 
    else {
      if (mouse_x >= layer_get_x(drag_players[i]) && 
          mouse_x <= (layer_get_x(drag_players[i])+layer_get_w(drag_players[i])+131) &&
          mouse_y >= layer_get_y(drag_players[i]) && 
          mouse_y <= (layer_get_y(drag_players[i])+layer_get_h(drag_players[i]))) {
          	        
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
	   
	// // 根据 choose_left_key_function 的值来判断是移动球员还是画球员跑动路线  
	var b_draw_run_direction = document.save_form.elements["choose_left_key_function"][1].checked;	

    if ( b_draw_run_direction ) {  
     	right_mouse_up(ev); 	
     	return;
    }
    
   var i, j;
   if (active_layer == "")
      return;
   var allowed_move;
   allowed_move = false;
   snap = true;
   
   
	// 鼠标左键移动球员时，清除球员的跑动路线  
	cancel_player_run_direction(left_mouse_down_r, left_mouse_down_c);
	
	
   snap_player(active_layer);
   on_drop();
   
         
   last_x = layer_get_x(active_layer);
   last_y = layer_get_y(active_layer);
   active_layer = "";

   return true;
}


/**
 * function: 鼠标右键被按下发生的事件     
 */
function right_mouse_down(ev)
{
	// judge which layer has been pitched on
  for (var i = 0; i < drag_players.length; i++) {  
    if (drag_players[i].indexOf("_p_") != -1) { // 判定是prompt 层  
  
      if (mouse_x >= layer_get_x(drag_players[i]) && 
          mouse_x <= (layer_get_x(drag_players[i])+layer_get_w(drag_players[i])) &&
          mouse_y >= layer_get_y(drag_players[i]) && 
          mouse_y <= (layer_get_y(drag_players[i])+layer_get_h(drag_players[i]))) {
        
        var row_col_arr = get_row_col(drag_players[i]); // 取得这个prompt层的行号、列号  
        var r_index = row_col_arr[0];
        var c_index = row_col_arr[1];
        if (player_formation[r_index][c_index] == 0) // 如果这个prompt层没有显示的话，就返回  
          return;
        
        
        active_layer = drag_players[i];
        last_x = layer_get_x(active_layer);  // last_x为被选中的球员层的x坐标，mouse_x 为鼠标的x坐标  
        last_y = layer_get_y(active_layer);  // last_y为被选中的球员层的y坐标，mouse_y 为鼠标的y坐标
        
        mouse_down_x = layer_get_x(active_layer);
        mouse_down_y = layer_get_y(active_layer);
        active_layer_w = layer_get_w(active_layer);
        active_layer_h = layer_get_h(active_layer);
        mouse_down_r = r_index;
        mouse_down_c = c_index;
          
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
          	      
        active_layer = drag_players[i];
        
        mouse_down_x = layer_get_x(active_layer);
        mouse_down_y = layer_get_y(active_layer);
        active_layer_w = layer_get_w(active_layer);
        active_layer_h = layer_get_h(active_layer);
        
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
}


/**
 * function: 鼠标右键弹起发生的事件   
 */
function right_mouse_up(ev)
{
	var i, j;
	if (active_layer == "")
		return;
		
	var allowed_move;
	allowed_move = false;
	snap = true;
	//snap_player(active_layer);  // 关键是修改这个函数
	on_drop();

	last_x = layer_get_x(active_layer);
	last_y = layer_get_y(active_layer);
	
	
	// 为了 mozilla 浏览器的特殊处理，因为在 mozilla 浏览器中，
	// 拖动球员层的形式是：先按下鼠标左键，捡起球员层，松开鼠标左键，在目标位置，按下鼠标左键，松开球员层	
	// 所以加入以下三句程序来重新获取 mouse_down_x，mouse_down_y     
	var no_move_layer = active_layer.replace("_p_", "_p_n_"); 
	mouse_down_x = layer_get_x(no_move_layer);
	mouse_down_y = layer_get_y(no_move_layer);
	
	
	// 画箭头线，方向是由起始位置指向终止位置
	draw_final_run_direction(); 
	
    // 将promote层放回它原来的位置 - 将promote的层隐藏
    layer_move(active_layer, mouse_down_x, mouse_down_y, false);
    // 将 active_layer 清空  
	active_layer = "";	
	
	// 现在要实现的功能是：
	// 1. 将promote的层隐藏
	// 2. 画箭头线，方向是由起始位置指向终止位置

	
	// 另外，应该在mouse_move()函数中实现即时显示箭头
	
	

   	return true;
}


/**
 * function: 鼠标左键移动球员时，清除球员的跑动路线   
 */
function cancel_player_run_direction(r_index, c_index)
{   
	// 判断r_index 和 c_index 的有效性   
	if (!r_index || !c_index ) {
		return;
	}
	
	if(r_index<0 || r_index>5 || c_index<0 || c_index>5) {
		return;	
	}
	
	
	var player_id = player_formation[r_index][c_index];  
	var div_id = "run_direction_" + player_id;  
	   	   			   		
   	var div_obj = document.getElementById(div_id); 
   	if (div_obj) {    // 如果原来就存在该div的情况
   		 
   		var td_obj = document.getElementById("graphic_td");   
		td_obj.removeChild(div_obj);
	}
	
}

/**
 * function: 应该在mouse_move()函数中实现即时显示箭头  
 */
function draw_runtime_run_direction()
{
	// 应该在mouse_move()函数中实现即时显示箭头  
    var div_obj = document.getElementById("draw_line");   
    
    // 
    target_coordinate = get_target_coordinate(active_layer);
    
    // 得到球员跑动方向，然后确定相应的图片文件的名称
    var return_result = get_run_direction_img_name(mouse_down_r, mouse_down_c, mouse_up_r, mouse_up_c, mouse_down_x, mouse_down_y, active_layer_w, active_layer_h);
    var img_name = "";
    var left_coordinate = 0, top_coordinate = 0;
	img_name = return_result[0];
	left_coordinate = return_result[1] ;
	top_coordinate = return_result[2];
    
//    div_obj.style.left = left_coordinate + "px";
//    div_obj.style.top = top_coordinate + "px";
	if (document.all) {
	  	div_obj.style.pixelLeft = left_coordinate;
	  	div_obj.style.pixelTop = top_coordinate;
	}
	else if (document.layers) {
	  	div_obj.left = left_coordinate;
	  	div_obj.top = top_coordinate;
	}
	else if (document.getElementById) {
	  	div_obj.style.left = left_coordinate + "px";
	  	div_obj.style.top = top_coordinate + "px";
	}
	
	
	if (img_name != "") { 
		div_obj.innerHTML = '<IMG src="/fmol/images/' + img_name + '.gif"/>';     
	}	
}


/**
 * function: 画箭头线，方向是由起始位置指向终止位置   
 */
function draw_final_run_direction()
{
	// target_coordinate 为终止位置的坐标  
	var target_coordinate = get_target_coordinate(active_layer);
    mouse_up_x = target_coordinate.px;
    mouse_up_y = target_coordinate.py;    
    
    // 鼠标右键弹起的时候，球员的位置必须有效
    if (mouse_up_x == -1 && mouse_up_y == -1) {
    	// 球员的位置无效
    	
    	return;	    	
    }	
	   	
   	var player_id = player_formation[mouse_down_r][mouse_down_c];
	var div_id = "run_direction_" + player_id;  
	var run_from = "from_" + mouse_down_r + "_" + mouse_down_c;
	var run_to = "to_" + mouse_up_r + "_" + mouse_up_c;
	   
	if (mouse_down_r == mouse_up_r && mouse_down_c == mouse_up_c) {  // 球员位置没有改变的情况
   			   		
   		var div_obj = document.getElementById(div_id); 
   		if (div_obj) {    // 如果原来就存在该div的情况
   			 
   			var td_obj = document.getElementById("graphic_td");   
			td_obj.removeChild(div_obj);
			
   		}			
		
   	}
	else if ( player_formation[mouse_up_r][mouse_up_c] > 0 ) {		
		// 只能拉线到没有球员的位置  
		alert("On the run direction, there must be no player!");
		
	}
	else if ( Math.abs(mouse_up_r - mouse_down_r) > 1 || Math.abs(mouse_up_c - mouse_down_c) > 1 ) {
		// 需要球员跑动的距离不能大于 1  
		alert("The run distence is too long!");
		
	}
	else if ( mouse_down_r==4 && mouse_down_c>=1 && mouse_down_c<=3 ) {
		// 中卫不能拉线  
		alert("The Middle Defender can not be set the run direction!");
		
	}
	else if ( mouse_up_r==4 && mouse_up_c>=1 && mouse_up_c<=3 ) {
	    // 不能拉线到中卫   	
		alert("The run direction can not be the place of the Middle Defender!");
		
	}
	else if ( mouse_down_r == (player_formation.length-1) ) {
		// 门将不能拉线  
		alert("The Goal Keeper can not be set the run direction!");
		
	}
	else if ( mouse_up_r == (player_formation.length-1) ) {
		// 不能拉线门将  
		alert("The run direction can not be the place of the Goal Keeper!");
		
	}
	else if ( 1 == other_player_have_same_target("run_direction_", run_to) ) {
		// 已经有球员被要求跑到该目的地了    
		alert("Other player has the same target place!");
		
	}	   	
	else {  // 球员位置发生改变的情况
   		 			
		var div_obj;		
			         
		if (document.getElementById(div_id)) {   // 如果原来就存在该div的情况
			
			div_obj = document.getElementById(div_id); 
			div_obj.run_from = run_from;  // run_from 为自定义属性 
			div_obj.run_to = run_to;  // run_to 为自定义属性  			
			
		}
		else {  // 如果原来没有存在该div的情况
			
			// 动态创建 div
			div_obj = document.createElement("DIV"); 	
			div_obj.id = div_id;  
			div_obj.run_from = run_from;  // run_from 为自定义属性 
			div_obj.run_to = run_to;  // run_to 为自定义属性   
			var td_obj = document.getElementById("graphic_td");   
			td_obj.appendChild(div_obj);
			
		}
		
		// 方法1：动态画线
//	    var line_start_x = mouse_down_x+active_layer_w/2; 
//	    var line_start_y = mouse_down_y+active_layer_h/2;
//	    var line_end_x = mouse_up_x+active_layer_w/2;
//	    var line_end_y = mouse_up_y+active_layer_h/2;    
//	    var lineHtml = drawLine(line_start_x, line_start_y, 
//	    				line_end_x, line_end_y, "#000000");
//		div_obj.innerHTML = lineHtml;  // 这个层以后可以作为画箭头的层，它应该在“位置圆圈层”下面
		
		
		// 方法2：动态贴图
		div_obj.style.position = "absolute";
		
		// 得到球员跑动方向，然后确定相应的图片文件的名称
		var return_result = get_run_direction_img_name(mouse_down_r, mouse_down_c, mouse_up_r, mouse_up_c, mouse_down_x, mouse_down_y, active_layer_w, active_layer_h);
	    var img_name = "";
	    var left_coordinate = 0, top_coordinate = 0;
		img_name = return_result[0];
		left_coordinate = return_result[1] ;
		top_coordinate = return_result[2];
	    
//	    div_obj.style.left = left_coordinate + "px";
//	    div_obj.style.top = top_coordinate + "px";
	    if (document.all) {
	      	div_obj.style.pixelLeft = left_coordinate;
	      	div_obj.style.pixelTop = top_coordinate;
	    }
	    else if (document.layers) {
	      	div_obj.left = left_coordinate;
	      	div_obj.top = top_coordinate;
	    }
	    else if (document.getElementById) {
	      	div_obj.style.left = left_coordinate + "px";
	      	div_obj.style.top = top_coordinate + "px";
		}

		if (img_name != "") { 
			div_obj.innerHTML = '<IMG src="/fmol/images/' + img_name + '.gif"/>';    
		}
		
		
	}  // end of " 球员位置发生改变的情况 "
	
	
	// clear the draw_line div's innerHTML
	var draw_line_div_obj = document.getElementById("draw_line"); 
	draw_line_div_obj.innerHTML = "";
	

	  	
}


/**
 * function: 画一条箭头线，方向是由起始位置指向终止位置   
 */
function draw_one_run_direction(player_id, from_r, from_c, to_r, to_c)
{
    
	   	
	var div_id = "run_direction_" + player_id;  
	var run_from = "from_" + from_r + "_" + from_c; 
	var run_to = "to_" + to_r + "_" + to_c;
			          
    // 球员的位置必须有效
    if (from_r < 0 || from_r > 5 || 
        from_c < 0 || from_c > 4 || 
        to_r < 0 || to_r > 5 ||
        to_c < 0 || to_c > 4 ) {
        	
    	// 球员的位置无效
    	
    	return;	    	
    }	
	   
	if (from_r == to_r && from_c == to_c) {  // 球员位置没有改变的情况
   			   		
   		return;		
		
   	}
	else if ( player_formation[to_r][to_c] > 0 ) {		
		// 只能拉线到没有球员的位置  
		return;
		
	}
	else if ( Math.abs(to_r - from_r) > 1 || Math.abs(to_c - from_c) > 1 ) {
		// 需要球员跑动的距离不能大于 1  
		return;
		
	}
	else if ( from_r==4 && from_c>=1 && from_c<=3 ) {
		// 中卫不能拉线  
		return;
		
	}
	else if ( to_r==4 && to_c>=1 && to_c<=3 ) {
	    // 不能拉线到中卫   	
		return;
		
	}
	else if ( from_r == (player_formation.length-1) ) {
		// 门将不能拉线  
		return;
		
	}
	else if ( to_r == (player_formation.length-1) ) {
		// 不能拉线门将  
		return;
		
	}
	else if ( 1 == other_player_have_same_target("run_direction_", run_to) ) {
		// 已经有球员被要求跑到该目的地了    
		return;
		
	}	   	
	else {  // 球员位置发生改变的情况
   		 			
		var div_obj;		
			         
		if (document.getElementById(div_id)) {   // 如果原来就存在该div的情况
			
			div_obj = document.getElementById(div_id); 
			div_obj.run_from = run_from;  // run_from 为自定义属性 
			div_obj.run_to = run_to;  // run_to 为自定义属性   			
			
		}
		else {  // 如果原来没有存在该div的情况
			
			// 动态创建 div
			div_obj = document.createElement("DIV"); 	
			div_obj.id = div_id;  
			div_obj.run_from = run_from;  // run_from 为自定义属性 
			div_obj.run_to = run_to;  // run_to 为自定义属性  
			var td_obj = document.getElementById("graphic_td");     
			td_obj.appendChild(div_obj);
			
		}
		
		// 方法2：动态贴图
		div_obj.style.position = "absolute";
		
		// 得到球员跑动方向，然后确定相应的图片文件的名称
		var layer_name = "prompt_p_" + from_r + "_" + from_c;  
		var from_x = layer_get_x(layer_name);
		var from_y = layer_get_y(layer_name);
		var layer_w = layer_get_w(layer_name);
		var layer_h = layer_get_h(layer_name);
		
		var img_name = "";
	    var left_coordinate = 0, top_coordinate = 0;
		var return_result = get_run_direction_img_name(from_r, from_c, to_r, to_c, from_x, from_y, layer_w, layer_h);
	    
		img_name = return_result[0];
		left_coordinate = return_result[1] ;
		top_coordinate = return_result[2];
	    
//	    div_obj.style.left = left_coordinate;
//	    div_obj.style.top = top_coordinate;
		if (document.all) {
	      	div_obj.style.pixelLeft = left_coordinate;
	      	div_obj.style.pixelTop = top_coordinate;
	    }
	    else if (document.layers) {
	      	div_obj.left = left_coordinate;
	      	div_obj.top = top_coordinate;
	    }
	    else if (document.getElementById) {
	      	div_obj.style.left = left_coordinate + "px";
	      	div_obj.style.top = top_coordinate + "px";
		}
		
		
		if (img_name != "") { 
			div_obj.innerHTML = '<IMG src="/fmol/images/' + img_name + '.gif"/>';    
		}
	}  // end of " 球员位置发生改变的情况 "
	
	
	// clear the draw_line div's innerHTML
	var draw_line_div_obj = document.getElementById("draw_line"); 
	draw_line_div_obj.innerHTML = "";	

	  	
}


/**
 * function: 取得球员的最终目的地  
 */
function get_target_coordinate(layer)
{
	var target_coordinate = new snap_point();
	target_coordinate.px = -1;
	target_coordinate.py = -1;
	var i=0, j=0;
	
	var layer_center_x = layer_get_x(layer) + half_shirt_width;
	var layer_center_y = layer_get_y(layer) + half_shirt_height;
	// x轴方向，如果球员在球场内  
	if (layer_center_x < x_snap_lines[0][x_snap_lines[0].length-1]) {
		for (i=0; i<y_snap_lines.length-1; ++i) {
			if (i == y_snap_lines.length-2) {
				if (layer_center_y < y_snap_lines[y_snap_lines.length-1]) {
					// goal keeper
					//layer_move(layer, field_left+field_xcorner+goalie_x-half_shirt_width, field_top+field_ycorner+goalie_y-half_shirt_height);
					j = 0;
					mouse_up_r = i;
					mouse_up_c = j;
					
					target_coordinate = snap_places[i][j];
					//reset_player(layer);  
					break;
				}
				
			} // if (i == y_snap_lines.length-2)
			else {
				if (layer_center_y >= y_snap_lines[i] && layer_center_y < y_snap_lines[i+1]) {
					for (j=0; j<x_snap_lines[i].length-1; ++j) { 
						if (layer_center_x >= x_snap_lines[i][j] && layer_center_x < x_snap_lines[i][j+1]) {
							mouse_up_r = i;
							mouse_up_c = j;
					
							target_coordinate = snap_places[i][j];
							break;
						}
					}
					
					break;
				}
			} // else
		} // for
	} // if (layer_center_x < x_snap_lines[0][x_snap_lines[0].length-1])

	return target_coordinate;	
}

/**
 * function: 判断是否已经有球员被要求跑到该目的地了  
 */	
function other_player_have_same_target(pre_str, run_to)
{
	var is_have = 0;
	
	// 得到页面上所有控件的方法 (兼容了浏览器的版本)  
	var agt = navigator.userAgent.toLowerCase();
	var is_ie = (agt.indexOf("msie") != -1);
	var is_ie5 = (agt.indexOf("msie 5") != -1);
	var all_elements = (is_ie || is_ie5) ? document.all : document.getElementsByTagName( "*" );
	
	var len = all_elements.length;    
	for (var i=0; i<len; ++i) {   
		// 只对 div 成员进行处理  
		if (all_elements[i].tagName != "DIV") continue;  
		
		var element_id = all_elements[i].id;
		var element_run_to = all_elements[i].run_to;  
	
		if ( (element_id.substr(0, pre_str.length) == pre_str) &&
		     (element_run_to == run_to) ) 
		{  
			is_have = 1;	
			
			break;
		}
	}
		
	return is_have;
		
}


/**
 * function: 画线
 */
function drawLine(x0,y0,x1,y1,color)
{ 
  var rs = ""; 
  if (y0 == y1) //画横线 
  { 
    if (x0>x1){var t=x0;x0=x1;x1=t} 
    
    rs = "<p class='emuH' style='top:"+y0+"px;left:"+x0+"px;background-color:"+color+"; width:"+Math.abs(x1-x0)+"px'/>"; 
  } 
  else if (x0 == x1) //画竖线 
  { 
    if (y0>y1){var t=y0;y0=y1;y1=t} 
    
    rs = "<p class='emuW' style='top:"+y0+"px;left:"+x0+"px;background-color:"+color+";height:"+Math.abs(y1-y0)+"px'/>"; 
  } 
  else // 画斜线
  { 
    var lx = x1-x0; 
    var ly = y1-y0; 
    var l = Math.sqrt(lx*lx+ly*ly);  
    rs_arr = new Array(); 

    for (var i=0;i<l;i+=1) // i+=3 改成虚线
    { 
      var p = i/l; 
      var px = parseInt(x0 + lx*p); 
      var py = parseInt(y0 + ly*p);   
      
      // 画点  
      rs_arr[rs_arr.length] = "<p class='emuWH' style='top:"+py+"px;left:"+px+"px;background-color:"+color+"'/>";  
      
    } 
    
    rs = rs_arr.join("");   
  } 
  return rs;
} 


/**
 * function: 得到球员跑动方向，然后确定相应的图片文件的名称
 */
function get_run_direction_img_name(from_r, from_c, to_r, to_c, from_x, from_y, layer_w, layer_h)
{
	
	var img_name = "";
	var left_coordinate = 0, top_coordinate = 0;
	if (to_c == from_c) {
		if (to_r > from_r) {
			img_name = "down";	
			
			left_coordinate = from_x + layer_w/2 - 30/2;  
			top_coordinate = from_y + layer_h;
		}	
		else if (to_r < from_r){
			img_name = "up";	
			
			left_coordinate = from_x + layer_w/2 - 30/2; 
			top_coordinate = from_y - 66;
		}
	}
	else if (to_r == from_r) {
		if (to_c > from_c) {
			img_name = "right";	
			
			left_coordinate = from_x + layer_w; 
			top_coordinate = from_y + layer_h/2 - 30/2;
		}	
		else if (to_c < from_c){
			img_name = "left";	
			
			left_coordinate = from_x - 40;
			top_coordinate = from_y + layer_h/2 - 30/2; 
		}
	}
	else if (to_c > from_c) {
		if (to_r > from_r) {
			img_name = "down_right";	
			
			left_coordinate = from_x + layer_w;
			top_coordinate = from_y + layer_h; 
		}	
		else if (to_r < from_r){
			img_name = "up_right";	
			
			left_coordinate = from_x + layer_w; 
			top_coordinate = from_y - 66;
		}
	}
	else if (to_c < from_c) {
		if (to_r > from_r) {
			img_name = "down_left";	
			
			left_coordinate = from_x - 44;
			top_coordinate = from_y + layer_h;
		}	
		else if (to_r < from_r){
			img_name = "up_left";	
			
			left_coordinate = from_x - 44;
			top_coordinate = from_y - 66;
		}
	}
	
	var return_result = new Array();
	return_result[0] = img_name;
	return_result[1] = left_coordinate;
	return_result[2] = top_coordinate;
	
	return return_result;
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
	if (layer_center_x < x_snap_lines[0][x_snap_lines[0].length-1]) {
		for (i=0; i<y_snap_lines.length-1; ++i) {
			if (i == y_snap_lines.length-2) {
				if (layer_center_y < y_snap_lines[y_snap_lines.length-1]) {
					// goal keeper
					//layer_move(layer, field_left+field_xcorner+goalie_x-half_shirt_width, field_top+field_ycorner+goalie_y-half_shirt_height);
					move_to_col_of_row(i, layer);  // 因为现在没有门将，所以暂时的处理办法是用 reset_player 函数代替原来的 move_to_col_of_row
					//reset_player(layer);  
					break;
				}
				else {
					// out of the field (y direction)
					reset_player(layer);  
					break;      
				}
			} // if (i == y_snap_lines.length-2)
			else {
				if (layer_center_y >= y_snap_lines[i] && layer_center_y < y_snap_lines[i+1]) {
					move_to_col_of_row(i, layer);
					break;
				}
			} // else
		} // for
	} // if (layer_center_x < x_snap_lines[0][x_snap_lines[0].length-1])
	else { // move the layer to the player list
		// 如果是球场内的球员放到 player list 中
		if (layer.indexOf("_p_") != -1) {  // move the player from the inside of the field to the player list
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
function move_to_col_of_row(row, layer)
{
	var j;
	var i = row;
	var layer_center_x = layer_get_x(layer) + half_shirt_width;
	
	var in_the_field_flag = 0;
	// 判断在x轴方向，球员应该在哪个位置  
	for (j=0; j<x_snap_lines[i].length-1; ++j) { 
		if (layer_center_x >= x_snap_lines[i][j] && layer_center_x < x_snap_lines[i][j+1]) {
			
			var player_id_at_pos = player_formation[i][j];
			
			var old_r_index = -1;
			var old_c_index = -1;
			var player_id_moved = 0;
			if (layer.indexOf("_p_") != -1) {  // layer 是球场上的层  
				in_the_field_flag = 1;
				var row_col_arr = get_row_col(layer);
				old_r_index = row_col_arr[0];
				old_c_index = row_col_arr[1];
				player_id_moved = player_formation[old_r_index][old_c_index]; // get the "player_id_moved"
			}
			else if (player_formation[i][j] != 0){ // 从球场外把球员拖到球场上，并且新的位置是可以显示的提示  
				player_id_moved = layer.replace("p", "");
				var continue_flag = 1;
				for (var m=0; m<player_formation.length; ++m) {
					if (continue_flag == 0) 
						break;
					  
					for (var n=0; n<player_formation[m].length; ++n) {
						if (player_formation[m][n] == player_id_moved) {
							old_r_index = m;
							old_c_index = n;
							continue_flag = 0;
							break;
						}
					}
				}
			}
			//else {continue;}
			
			if (old_r_index != -1 && old_c_index != -1) { 
				// the player's original place is on the field
				player_formation[old_r_index][old_c_index] = player_id_at_pos;
				player_formation[i][j] = player_id_moved;
				
				// 将两个需要交换的球员层的位置互换  
				// player_id_at_pos 换到 [old_r_index, old_c_index] 位置上  
				showPlayerLayer(player_id_at_pos, old_r_index, old_c_index);
				// player_id_moved 换到 [i, j] 位置上  
				showPlayerLayer(player_id_moved, i, j);
			}
			else {  // if (player_formation[i][j] != 0) { 
				// the player's original place is not no the field, is from other place
				// 从 player_list 将球员移动到 球场上  
				//if (player_id_at_pos != 0) {
				  //var player_at_pos = "p" + player_id_at_pos;
				  //layer_move(player_at_pos, last_x, last_y);
				//}
				// 从球场外面的替补（或其他球员）移动到球场里面的情况  
				player_formation[i][j] = player_id_moved;
				
				// 处理 subs 和 others 				
				var in_sub = 0;
				for (var sub_index=0; sub_index<formation_subs.length; ++sub_index) {
					if (formation_subs[sub_index] == player_id_moved) {
						
						formation_subs[sub_index] = player_id_at_pos;
						
						in_sub = 1;
						break;
					}
				}
				if (in_sub == 0) {
					for (var other_index=0; other_index<formation_others.length; ++other_index) {
						if (formation_others[other_index] == player_id_moved) {
							formation_others[other_index] = player_id_at_pos;
													
							break;
						}
					}
				}
				
				
				// player_id_moved 换到 [i, j] 位置上  
				showPlayerLayer(player_id_moved, i, j);
			}
			
			break;
		}
	} // end of for
	
	if (in_the_field_flag == 0) { 
	  	// out of the field (x direction)
	  	reset_player(layer);
	} // end of if(in_the_flag)
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
			var c_index_moved = -1;
			var r_index_at_list = -1;
			var c_index_at_list = -1;
			for (var m=0; m<player_formation.length; ++m) {
				if (continue_flag_moved == 0 && continue_flag_at_list == 0) break;
				  
				for (var n=0; n<player_formation[m].length; ++n) {
					if (player_formation[m][n] == player_id_moved) {
						r_index_moved = m;
						c_index_moved = n;
						continue_flag_moved = 0;
					}
					if (player_formation[m][n] == player_id_at_list) {
						r_index_at_list = m;
						c_index_at_list = n;
						continue_flag_at_list = 0;
					}
					
					if (continue_flag_moved == 0 && continue_flag_at_list == 0) break;
				} // for (var n=0; n<player_formation[m].length; ++n)
			}// for (var m=0; m<player_formation.length; ++m)
			
			if (r_index_moved != -1 && c_index_moved != -1 && 
			  r_index_at_list != -1 && c_index_at_list != -1) {
				player_formation[r_index_moved][c_index_moved] = player_id_at_list;
				player_formation[r_index_at_list][c_index_at_list] = player_id_moved;
				
				// player_id_at_list 换到 [r_index_moved, c_index_moved] 位置上  
				showPlayerLayer(player_id_at_list, r_index_moved, c_index_moved);
				// player_id_moved 换到 [r_index_at_list, c_index_at_list] 位置上  
				showPlayerLayer(player_id_moved, r_index_at_list, c_index_at_list);
			}
			else if (r_index_moved != -1 && c_index_moved != -1){  
				// 移动的球员是球场上的球员，而将要换到的位置上的球员是替补（或者其他）  
				player_formation[r_index_moved][c_index_moved] = player_id_at_list;
				
				var in_sub = 0;
				for (var sub_index=0; sub_index<formation_subs.length; ++sub_index) {
					if (formation_subs[sub_index] == player_id_at_list) {
						formation_subs[sub_index] = player_id_moved;
						in_sub = 1;
						break;
					}
				}
				if (in_sub == 0) {
					for (var other_index=0; other_index<formation_others.length; ++other_index) {
						if (formation_others[other_index] == player_id_at_list) {
							formation_others[other_index] = player_id_moved;
							break;
						}
					}
				}
				
				// player_id_at_list 换到 [r_index_moved, c_index_moved] 位置上  
				showPlayerLayer(player_id_at_list, r_index_moved, c_index_moved);
			}
			else if (r_index_at_list != -1 && c_index_at_list != -1){    
				// 移动的球员是替补（或者其他），而将要换到的位置上的球员是球场上的球员  
				player_formation[r_index_at_list][c_index_at_list] = player_id_moved;
				
				var in_sub = 0;
				for (var sub_index=0; sub_index<formation_subs.length; ++sub_index) {
					if (formation_subs[sub_index] == player_id_moved) {
						formation_subs[sub_index] = player_id_at_list;
						in_sub = 1;
						break;
					}
				}
				if (in_sub == 0) {
					for (var other_index=0; other_index<formation_others.length; ++other_index) {
						if (formation_others[other_index] == player_id_moved) {
							formation_others[other_index] = player_id_at_list;
							break;
						}
					}
				}
				
				// player_id_moved 换到 [r_index_at_list, c_index_at_list] 位置上  
				showPlayerLayer(player_id_moved, r_index_at_list, c_index_at_list);
			}
			
			else { 
				// 移动的球员是替补（或者其他），而将要换到的位置上的球员是替补（或者其他）  
				var in_sub_at_list = 0;
				var index_at_list = 0;
				for (var sub_index=0; sub_index<formation_subs.length; ++sub_index) {
					if (formation_subs[sub_index] == player_id_at_list) {
						index_at_list = sub_index;
						in_sub_at_list = 1;
						break;
					}
				}
				if (in_sub_at_list == 0) {
					for (var other_index=0; other_index<formation_others.length; ++other_index) {
						if (formation_others[other_index] == player_id_at_list) {
							index_at_list = other_index;
							break;
						}
					}
				}
				// 
				var in_sub_moved = 0;
				var index_moved = 0;
				for (var sub_index=0; sub_index<formation_subs.length; ++sub_index) {
					if (formation_subs[sub_index] == player_id_moved) {
						index_moved = sub_index;
						in_sub_moved = 1;
						break;
					}
				}
				if (in_sub_moved == 0) {
					for (var other_index=0; other_index<formation_others.length; ++other_index) {
						if (formation_others[other_index] == player_id_moved) {
							index_moved = other_index;
							break;
						}
					}
				}
				
				// 
				if (in_sub_at_list != 0) {
					formation_subs[index_at_list] = player_id_moved;
				}
				else { 
					formation_others[index_at_list] = player_id_moved;
				}
				
				if (in_sub_moved != 0) {
					formation_subs[index_moved] = player_id_at_list;
				}
				else {
					formation_others[index_moved] = player_id_at_list;
				}
			}
			
			
			// exchange player_id in "players", "players" is a value in document.save_form
			//exchange_player_id_in_data(player_id_moved, player_id_at_list);
			
			break;
		} // if
	}
}


/**
 * function: exchange player_id in "players", "players" is a value in document.save_form
 */
function exchange_player_id_in_data(player_id1, player_id2)
{
  var data = document.save_form.players.value; 
  if (data == "") return;  
  if (data.indexOf(player_id1)==-1 || data.indexOf(player_id2)==-1) return;

  data = data.replace(player_id1, "abcde");
  data = data.replace(player_id2, player_id1);
  data = data.replace("abcde", player_id2);
  document.save_form.players.value = data;
}

/**
 * function: 将球场上 [r_index, c_index] 位置上的球员层显示出来  
 */
function showPlayerLayer(player_id, r_index, c_index)
{
	var layer = "";
	// 如果球员层上的球员id不为0，则显示该球员层  
	if (player_id != 0) {
		layer = "prompt_p_n_" + r_index + "_" + c_index; 
		layer_show(layer); 
		layer = "prompt_p_" + r_index + "_" + c_index; 
		layer_show(layer); 
		if (player_id != -1) {
			// 如果球员层上的球员id不为0也不为-1，则显示该球员的名字  
			layer = "player_div_" + r_index + "_" + c_index; 
			layer_show(layer);
			layer_write(layer, get_player_name(player_id)); // write the player name
		}
		else {
			// 如果球员层上的球员id为-1，则显示该球员的名字为空  
			layer = "player_div_" + r_index + "_" + c_index; 
			layer_show(layer);
			layer_write(layer, ""); // write the player name
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
    
    
     
	// 只对鼠标右键事件进行处理(以前的版本) 
	// 现在由 name 为 choose_left_key_function 的下拉框来决定   
	var b_draw_run_direction = document.save_form.elements["choose_left_key_function"][1].checked;	

    if ( b_draw_run_direction ) {  
    	
     	// 应该在mouse_move()函数中实现即时显示箭头    
    	draw_runtime_run_direction();
    }
    
    
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
{//alert(layer);
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





