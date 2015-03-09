/**
 * code type: javascript code
 * function: to lay the tactics
 */

var field_top;
var field_left;
var field_xcorner;
var field_ycorner;
var grass_width;
var grass_height;
var shirt_width;
var shirt_height;
var prompt_width;
var prompt_height;
var info_width;
var info_height;

var goalie_x;
var goalie_y;

var min_defs;
var min_mids;
var min_forw;

var top_coordinate;
var left_coordinate;
var place_left_coordinate;
var item_color_left_coordinate;
var player_name_left_coordinate;

var last_type = 0;
var goalies = 0;
var forw = 0;
var mids = 0;
var defs = 0;
var subs = 0;
var last_x = 0;
var last_y = 0;

var old_x;
var old_y;
var half_shirt_weight;
var half_shirt_height;
var half_prompt_weight;
var half_prompt_height;

var place_name = new Array("F", "AM", "M", "DM", "D");
var direction_name = new Array("L", "C", "C", "C", "R");
var item_color = new Array("#000000", "#0069b9", "#FF9900", "#CC3366", "#009900", "#FF3300");

function snap_point(px, py) 
{
  this.px = px;
  this.py = py;
}

/**
 * function: to init the parameter of tactics
 */
function init_training(ft, fl, fx, fy, gw, gh, sw, sh, pw, ph, iw, ih, md, mm, mf, with_count, tc, lc, plc, iclc, pnlc)
{
	// set the variables
	field_top = ft;
	field_left = fl;
	field_xcorner = fx;
	field_ycorner = fy;
	grass_width = gw;
	grass_height = gh;
	shirt_width = sw;
	shirt_height = sh;
	half_shirt_width = Math.floor(sw/2);
	half_shirt_height = Math.floor(sh/2);
	prompt_width = pw;
	prompt_height = ph;
	half_prompt_width = Math.floor(pw/2);
	half_prompt_height = Math.floor(ph/2);
	info_width = iw;
	info_height = ih;
	
	goalie_x = Math.floor(grass_width/2);
	goalie_y = grass_height - 20;
	
	min_defs = md;
	min_mids = mm;
	min_forw = mf;
	
	top_coordinate = tc;
	left_coordinate = lc;
	place_left_coordinate = plc;
	item_color_left_coordinate = iclc;
	player_name_left_coordinate = pnlc;
	
	// drop, drag, pick
	if (with_count)
	  	on_drop = drop_with_count;
	else
	  	on_drop = drop;
	  
	on_drag = move;
	on_pick = pick;
	
	// ��ʼ�����ϵķָ��ߵ�����    
	init_snap_lines();
	
	// resize the ppTable, ʹ�����ĳ���Ϊ10    
	resize_ppTable();
	
	init_ppTable_free();
	
	init_item_color();
	
	check_update_snaps();
	
	
	// ҳ�� onload ʱִ�еĺ���    
	formTheSelectValues_items();
	formTheSelectValues_coaches();
} 

/**
 * function: check whether need to update the snaps
 */
function check_update_snaps()
{ 
	// ��������ʾ���еĲ���ѵ������Ա    
	for (var item_index=1; item_index<=5; ++item_index) {
		var table_obj = eval("ppTable_" + item_index);
		var player_id = -1;
		var item_num = 0;
		var layer = "";
		for (var i=0; i<table_obj.length; ++i) {
			if (table_obj[i] <= 0) {
				continue;	
			}
			else {
				if (item_num >= 10) {
					ppTable_1[i] = -1;
					continue;
				}
				player_id = table_obj[i];  
				if (item_num < 5) {
					layer = "player_div_nomove_" + player_id; 
					layer_move(layer, field_left+(item_num%5)*Math.floor(grass_width/5)+10, y_snap_lines[item_index]-22*2, false); 
					layer = "player_div_" + player_id;
					layer_move(layer, field_left+(item_num%5)*Math.floor(grass_width/5)+10, y_snap_lines[item_index]-22*2, false); 
				}
				else {
					layer = "player_div_nomove_" + player_id; 
					layer_move(layer, field_left+(item_num%5)*Math.floor(grass_width/5)+10, y_snap_lines[item_index]-22, false); 
					layer = "player_div_" + player_id;
					layer_move(layer, field_left+(item_num%5)*Math.floor(grass_width/5)+10, y_snap_lines[item_index]-22, false); 
				}
				
				++ item_num;
			}			
		}
		
		// ���²μ�ѵ����Ŀ������    
		if (item_index == 1) {
			layer_write("item_num_" + item_index, "players:&nbsp;&nbsp;" + item_num + "&nbsp;/&nbsp;10" + "&nbsp;&nbsp;");  
		}
		else {
			layer_write("item_num_" + item_index, item_num + "&nbsp;/&nbsp;10" + "&nbsp;&nbsp;");  
		}
	}
	
	
	var table_obj = eval("ppTable_" + "free");
	for (var m=0; m<table_obj.length; ++m) {
		if (table_obj[m] <= 0) {
			continue;
		}
		
		player_id = table_obj[m];  
		layer = "player_div_nomove_" + player_id;
		layer_move(layer, -100, -100, true);
		layer = "player_div_" + player_id;
		layer_move(layer, -100, -100, true);	
		
	}
	
	// arrange the player list
	arrange_player_list();
}

/**
 * function: clear the player place on the player list
 */
function clear_player_place()
{
	var data = document.save_form.players.value;
	if (data == "")
	  	return;
	  
	var players = data.split(",");
	for (var i=0; i<players.length; ++i) {
		var layer = "place_" + players[i];
		layer_write(layer, "");
	}
}

/**
 * function: �����������ֱߵ���Ա�б�    
 * first: show the player who has been on the field
 * then: show the five subs
 * last: show the free players
 */
function arrange_player_list()
{
	// �����������Ա����ѵ������Ŀ��������������Ա��λ��    
	var top_y = top_coordinate;
	var y_interval = 26;
	var x1 = left_coordinate;
	var x2 = place_left_coordinate;
	var x3 = item_color_left_coordinate;
	var x4 = player_name_left_coordinate;
	var cur_y = top_y;
	var layer = "";
	for (var item_index=1; item_index<=5; ++item_index) {
		var table_obj = eval("ppTable_" + item_index);
		var player_id = -1;
		for (var i=0; i<table_obj.length; ++i) {
			if (table_obj[i] <= 0) {
				continue;	
			}
			else {
				player_id = table_obj[i];  
				
				// move the player layer
				layer_move("np"+player_id, x1, cur_y, true);
				layer_move("p"+player_id, x1, cur_y, true);
				layer_move("index_"+player_id, x2, cur_y, false);
				layer_move("item_color_"+player_id, x3, cur_y, false);
				layer_move("player_name_"+player_id, x4, cur_y, false);
				layer_change_color("item_color_"+player_id, item_color[item_index]);
				
				cur_y += y_interval; 
			}			
		}
	}
	for (var m=0; m<ppTable_free.length; ++m) {
		player_id = ppTable_free[m];  
		if (player_id <=0) continue;		
		// move the player layer
		layer_move("np"+player_id, x1, cur_y, true);
		layer_move("p"+player_id, x1, cur_y, true);
		layer_move("index_"+player_id, x2, cur_y, false);
		layer_move("item_color_"+player_id, x3, cur_y, false);
		layer_move("player_name_"+player_id, x4, cur_y, false);
		layer_change_color("item_color_"+player_id, item_color[0]);
		
		cur_y += y_interval; 
	}
	
}

/**
 * function: remove the value from arr
 */
function remove_value_from_arr(arr, value)
{
	for (var m=0; m<arr.length; ++m) {
		if (arr[m] == value) {
			arr[m] = "-1";
		}
	}
	
	return arr;
}


/**
 * function: ��ʼ�����ϵķָ��ߵ�����   
 */
function init_snap_lines()
{
	var x = 0;
	var y = 0;
	// -------------------------------------------------------------------------
	// x�᷽��  
	// -------------------------------------------------------------------------
	// five x  
	// ����x�᷽��ķָ��ߵ����꣨��1����ѡλ�õ������ ����2����    
	x_snap_lines[x_snap_lines.length] = field_left; // ��1��    
	x_snap_lines[x_snap_lines.length] = field_left + grass_width; // ��2��    
	
	
	// -------------------------------------------------------------------------
	// y �᷽��    
	// -------------------------------------------------------------------------
	// ����y�᷽��ķָ��ߵ����� ����6����    
	y_snap_lines[y_snap_lines.length] = field_top; // ��1��    
	y = field_top;
	y += Math.floor((grass_height-16)/5) + 16 + 2;	
	y_snap_lines[y_snap_lines.length] = y; // �� 2 ��   
	
	y += 2; 
	for (var i=2; i<=5; ++i) {
	  	y += Math.floor((grass_height-16)/5);	
	  	y_snap_lines[y_snap_lines.length] = y; // �� 3 4 5 6 ��    
	}
	
	//y_snap_lines[y_snap_lines.length] = field_top + grass_height; // ��6��    
}


/**
 * function: resize the ppTable, ʹ�����ĳ���Ϊ10    
 */
function resize_ppTable() 
{
	for (var item_index=1; item_index<=5; ++item_index) {
		var table_obj = eval("ppTable_" + item_index);
		var len = table_obj.length;
		if (len >  10) {
			table_obj.length = 10;
		}
		else if (len < 10) { 
			while (table_obj.length != 10) {
				table_obj[table_obj.length] = -1;
			}
		}
	}
}

/**
 * function: init the ppTable_free, ����������û��ѵ����Ŀ����Ա    
 */
function init_ppTable_free()
{
	var players = document.save_form.players.value;
	var arr = players.split(",");
	
	for (var item_index=1; item_index<=5; ++item_index) {
		var table_obj = eval("ppTable_" + item_index);
		var len = table_obj.length;
		for (var m=0; m<len; ++m) {
			if (table_obj[m] > 0) {
				arr = remove_value_from_arr(arr, table_obj[m]);
			}
		}
	}
	
	ppTable_free.length = 0;
	for (var m=0; m<arr.length; ++m) {
		if (arr[m] > 0) {
			ppTable_free[ppTable_free.length] = arr[m];  
		}
	}
}

/**
 * function: init the item_color, ������ɫ��ʾ�����ѵ����Ŀ����Ա����ɫ    
 */
function init_item_color()
{
	for (var item_index=1; item_index<=5; ++item_index) {
		layer_change_color("item_"+item_index, item_color[item_index]);
	}
}


/**
 * function: when the player layer is not layed on the field, 
 *           then remove it back to its original place
 */
function reset_player(layer)
{
	if (layer.indexOf("_div_") == -1) { 
		// layer �����ϵ���Ա�㣬���� player_list �ϵĲ�    
		var layer2 = "n" + layer; // the "blank shirt" layer
		var x = layer_get_x(layer2); // for the "blank shirt" layer has not been moved
		var y = layer_get_y(layer2);
		layer_move(layer, x, y, true);
	}
	else { 
		// layer �����ϵ���Ա��    
		// ��to do...��
		var player_id = layer.replace("player_div_", ""); 
		remove_player_from_other_item(player_id);
		insert_player_into_free_item(player_id);

	}
} 

/**
 * function: remove all the player layers back to its original place
 */
function reset_all()
{
	// ��1-4ѵ����Ŀ�е���Ա���  
	for (var item_index=1; item_index<=4; ++item_index) {  // 5 -> 4 ��Ϊ�Ž�ѵ���䶯����    
		var table_obj = eval("ppTable_" + item_index);
		var len = table_obj.length;
		for (var m=0; m<len; ++m) {
			if (table_obj[m] > 0) { 
				insert_player_into_free_item(table_obj[m]);
				table_obj[m] = -1;
			}
		}
	}
	
	// restore the snaps
	check_update_snaps();
} 

/**
 * function: save the tactics
 * return: "false"-do not send the data to the server
 *         "true"-send the data to the server  
 */
function save()
{
	try {
	  	return save_int();
	} 
	catch(error) {
		alert("A javascript error occurred; " + error.description + "\n" +
		      "Make sure you have enabled Javascript in your browser, " +
		      "or contact support for help. ");
		return false;
	}
}

/**
 * function: save the tactics data to the "data"
 */
function save_int() 
{
	// �����ﻹҪ��һЩУ������    
	// to do ...
	var player_ids = "";
  	for (var item_index=1; item_index<=5; ++item_index) {  
		var table_obj = eval("ppTable_" + item_index);
		var len = table_obj.length;
		player_ids = "";
		for (var m=0; m<len; ++m) {
			if (table_obj[m] > 0) {
				player_ids += "," + table_obj[m]; 
			}
		}
		player_ids = player_ids.substr(1);
		eval("document.save_form.players_of_item_" + item_index + ".value=\"" + player_ids + "\"");
	}
	
    disable_submit_button(document.save_form);
    return true;

  	return false;
}

/**
 * function: Call from <form> tag: onSubmit='disable_submit_button(this)'
 */
function disable_submit_button(form_obj)
{ 
	/* form.elements:
	 * Retrieves a collection, in source order, of all controls in a given form.
	 * input type=image objects are excluded from the collection. 
	 */
	for(i=0; i<form_obj.elements.length; i++) {
		if(form_obj.elements[i].type=="submit") {
			form_obj.elements[i].disabled=true;
		}
	}
}

///**
// * function: get the player number
// */
//function get_player_shirt_number(pid)
//{
//  return eval("pdata" + pid + "[17]");
//}


/**
 * function: 
 */
function drop_with_count() 
{
	drop();
	//load_count();
}

/**
 * function: drop the player layer
 */
function drop() 
{
  var layer = active_layer;
//  var x = layer_get_x(layer);
//  var y = layer_get_y(layer);


  
//  if (!a) //send him back
//    reset_player(layer);
    
  //writePlayerInfo("nodata");


}

/**
 * function: pick the player layer
 */
function pick() 
{
	var layer = active_layer;
	last_x = layer_get_x(active_layer);
	last_y = layer_get_y(active_layer);
	
    
  	return;
}

/**
 * function: move the player layer
 */
function move() 
{
	// Ϊ��Nescape�����ӵģ����ƶ���Ա��ʱ�����timers�е�����    
	for(var i=0; i<timers.length; ++i) clearTimeout(timers[i]);
	timers = [];
	
	y = mouse_y-diff_y;  // yΪ�����ڵ�y����    
	x = mouse_x-diff_x;  // xΪ�����ڵ�x����    
	
	// old_x��old_yΪ֮ǰ�Ĳ��x��y����    
	if (old_x == x && old_y == y)
		return;
	
	old_x = x;
	old_y = y;
}







