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
var half_shirt_width;
var half_shirt_height;
var half_prompt_width;
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
function init_role_list(ft, fl, fx, fy, gw, gh, sw, sh, pw, ph, iw, ih, md, mm, mf, with_count, tc, lc, plc, iclc, pnlc)
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
	

	// ҳ�� onload ʱִ�еĺ���
	formTheSelectValues_indexs();
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
	// ����y�᷽��ķָ��ߵ�����    
    
}






/**
 * function: when the player layer is not layed on the field, 
 *           then remove it back to its original place
 */
function reset_player(layer)
{

	// layer �����ϵ���Ա�㣬���� player_list �ϵĲ�    
	var layer2 = "n" + layer; // the "blank shirt" layer
	var x = layer_get_x(layer2); // for the "blank shirt" layer has not been moved
	var y = layer_get_y(layer2);
	layer_move(layer, x, y, true);

} 

/**
 * function: remove all the player layers back to its original place
 */
function reset_all()
{
	// ��1-4ѵ����Ŀ�е���Ա���  
//	for (var item_index=1; item_index<=4; ++item_index) {  // 5 -> 4 ��Ϊ�Ž�ѵ���䶯����    
//		var table_obj = eval("ppTable_" + item_index);
//		var len = table_obj.length;
//		for (var m=0; m<len; ++m) {
//			if (table_obj[m] > 0) { 
//				insert_player_into_free_item(table_obj[m]);
//				table_obj[m] = -1;
//			}
//		}
//	}
	
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




// ���� player_id ������ ppTable�������Ա������  
// ��ʾ��ʽΪ�� A, Smith  
// ���У�AΪ given_name �ĵ�һ����ĸ�Ĵ�д��Smith Ϊ family_name  
function get_player_name(player_id) 
{
	var given_name = eval("ppTable" + player_id + "[0]");
	var family_name = eval("ppTable" + player_id + "[1]");
	if (given_name == "")
		return family_name;
	else 
		return (given_name.substr(0, 1) + "."+ family_name);
}




