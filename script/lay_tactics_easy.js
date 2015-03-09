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

function snap_point(px, py) 
{
  this.px = px;
  this.py = py;
}

/**
 * function: to init the parameter of tactics
 */
function init_tactics(ft, fl, fx, fy, gw, gh, sw, sh, pw, ph, iw, ih, md, mm, mf, with_count, tc, lc, plc, pnlc)
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
  player_name_left_coordinate = pnlc;
  

  
	// 初始化球员在球场上的候选位置的坐标  
  init_prompt_place();

	// 根据数据库中的阵型来初始化 player_formation  
  init_player_formation();


  check_update_snaps();
  
} 

/**
 * function: check whether need to update the snaps  
 */
function check_update_snaps()
{ 
	// 遍历 player_formation，将 player_formation 中内容不为 0 的元素显示  
  for (var r=0; r<player_formation.length-1; ++r) {
    if (player_formation[r][2] != 0) { 
			// 如果有球员在横向的第3个位置，则使用 five_x_snap_places 和 five_x_snap_lines （five）
      for (var c=0; c<five_x_snap_places.length; ++c) { 
        snap_places[r][c] = new snap_point(five_x_snap_places[c], y_snap_places[r]);
      }
    }
    else if (player_formation[r][1] != 0 || player_formation[r][3] != 0) {
			// 如果没有有球员在横向的第3个位置，   
			// 并且有球员在横向的第2或者4个位置，则使用 four_x_snap_places 和 four_x_snap_lines （four）  
      for (var c=0; c<four_x_snap_places.length; ++c) { 
        snap_places[r][c] = new snap_point(four_x_snap_places[c], y_snap_places[r]);
      }
    }
    else if (player_formation[r][0] != 0 || player_formation[r][4] != 0){
			// 如果没有有球员在横向的第3个位置，   
			// 并且没有球员在横向的第2或者4个位置，  
			// 并且有球员在横向的第1或者5个位置，则使用 five_x_snap_places 和 five_x_snap_lines （five）  
      for (var c=0; c<five_x_snap_places.length; ++c) { 
        snap_places[r][c] = new snap_point(five_x_snap_places[c], y_snap_places[r]);
      }
    }
    else {
			// 否则，如果没有球员在其中一个位置，则使用 four_x_snap_places 和 four_x_snap_lines （four）  
      for (var c=0; c<four_x_snap_places.length; ++c) { 
        snap_places[r][c] = new snap_point(four_x_snap_places[c], y_snap_places[r]);;
      }
    }
  }
  
	// 接着对门将的位置进行处理  
  for (var c=0; c<one_x_snap_places.length; ++c) { 
    snap_places[player_formation.length-1][c] = new snap_point(
                                                  one_x_snap_places[c], 
                                                  y_snap_places[player_formation.length-1]);
  }
  
  // clear the player place on the player list  
  //clear_player_place();
  // adjust the place of "prompt" and "player" on the field
	var layer;
  for (var r=0; r<snap_places.length; ++r) {  // 因为暂时没有门将，所以snap_places.length -> snap_places.length-1  
  	for (var c=0; c<snap_places[r].length; ++c) {
  		// move the player
  		var layer = "prompt_p_" + r + "_" + c; 
  		layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, true); 
  		var layer = "player_div_" + r + "_" + c; 
  		layer_move(layer, snap_places[r][c].px-10, snap_places[r][c].py+30, true); 
  		
  		// show the player
  		
  		if (player_formation[r][c] != 0) {
  			var layer = "prompt_p_" + r + "_" + c; 
  			layer_show(layer);
  			if (player_formation[r][c] != -1) {
  				var layer = "player_div_" + r + "_" + c; 
  				layer_show(layer);
  				if (r == (snap_places.length-1)) {
  					// GK
  					layer_write(layer, get_gk_name(player_formation[r][c])); // write the player id
  				}
  				else {
  					layer_write(layer, get_player_name(player_formation[r][c])); // write the player id
  				}  				
  				
  			} //if (player_formation[r][c] != -1)
  		} //if (player_formation[r][c] != 0)    
  	} //for (var c=0; c<snap_places[r].length; ++c)
 }//for (var r=0; r<snap_places.length; ++r)
 
 // arrange the player list
 //arrange_player_list();
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

// hide all players on field
function hide_all_players_on_field()
{ 
	var layer;
	for (var r=0; r<snap_places.length; ++r) {  // 因为暂时没有门将，所以snap_places.length -> snap_places.length-1  
		for (var c=0; c<snap_places[r].length; ++c) {
		
			// show the player
			  
			if (player_formation[r][c] != 0) {
				var layer = "prompt_p_" + r + "_" + c; 
				layer_hide(layer);
				if (player_formation[r][c] != -1) {
					var layer = "player_div_" + r + "_" + c; 
					layer_hide(layer);
						
				} 
			} 
		} 
	}
}

// hide all players on field
function show_all_players_on_field()
{
	var layer;
	for (var r=0; r<snap_places.length; ++r) {  // 因为暂时没有门将，所以snap_places.length -> snap_places.length-1  
		for (var c=0; c<snap_places[r].length; ++c) {
		
			// show the player
			  
			if (player_formation[r][c] != 0) {
				var layer = "prompt_p_" + r + "_" + c; 
				layer_show(layer);
				if (player_formation[r][c] != -1) {
					var layer = "player_div_" + r + "_" + c; 
					layer_show(layer);
					
				} 
			} 
		} 
	}
}



/**
 * function: 重新排列右手边的球员列表  
 * first: show the player who has been on the field
 * then: show the five subs
 * last: show the free players
 */
function arrange_player_list()
{
	var data = document.save_form.players.value;
	if (data == "") return;
	
	var top_y = top_coordinate;
	var y_interval = 26;
	var x1 = left_coordinate;
	var x2 = place_left_coordinate;
	var x3 = player_name_left_coordinate;
	var cur_y = top_y;
	var layer;
	
	
	for (var r=player_formation.length-1; r>=0; --r) {  // 因为现在没有门将，所以player_formation.length-1 -> player_formation.length-2
		var c = player_formation[r].length-1;
		if (player_formation[r][c] != 0 && player_formation[r][c] != -1) {
			// remove the player_id from data
			player_id = player_formation[r][c];
			data = remove_player_id_from_data(data, player_id);
		  
			var place_str = "";
			if (i == 5) 
			  	place_str = "GK"; 
			else 
			  	place_str = place_name[r] + direction_name[c]; 
			//layer_write("place_"+player_id, place_str);
		
			// move the player layer
			layer_move("place_"+player_id, x2, cur_y, false);
			layer_move("player_name_"+player_id, x3, cur_y, false);
			
			cur_y += y_interval;     
		}
		  
		for (var c=0; c<player_formation[r].length-1; ++c) 
			if (player_formation[r][c] != 0 && player_formation[r][c] != -1) {
				// remove the player_id from data
				player_id = player_formation[r][c];
				data = remove_player_id_from_data(data, player_id);
				  
				var place_str = "";
				if (i == 5) 
				  	place_str = "GK"; 
				else 
				  	place_str = place_name[r] + direction_name[c]; 
				//layer_write("place_"+player_id, place_str);
				
				// move the player layer
				layer_move("place_"+player_id, x2, cur_y, false);
				layer_move("player_name_"+player_id, x3, cur_y, false);
				
				cur_y += y_interval;     
			}
	}
	// deal with the rest players    
	var players = data.split(",");
	for (var i=0; i<players.length; ++i) {
		if(i < 5) layer_write("place_"+players[i], "s"+(i+1));
		else layer_write("place_"+players[i], "");
			
		// move the player layer
		layer_move("place_"+players[i], x2, cur_y, false);
		layer_move("player_name_"+players[i], x3, cur_y, false);
		
		cur_y += y_interval; 
		
	}   
}

/**
 * function: remove the player_id from data
 */
function remove_player_id_from_data(data, player_id)
{
  if (data.indexOf(player_id)!=-1 && 
      data.indexOf(player_id)==0) {
    data = data.substr(player_id.length);
    if (data.length > 0) data = data.substr(1); 
  }
  else {
    data = data.substr(0, data.indexOf(player_id)-1) + 
           data.substr(data.indexOf(player_id)+player_id.length);
  }
  
  return data;
}

/**
 * function: 初始化球员在球场上的候选位置的坐标  
 */
function init_prompt_place()
{
  var r = 1;
  var c = 1;
  var layer;
  var x = 0;
  var y = 0;
	// 计算 y 轴方向候选位置的坐标 （共 6 条）  
  for (r=1; r<=6; ++r) {
    if (r == 1) {
      y = field_top + field_ycorner + Math.floor(grass_height*10/100) - half_prompt_height;
      y_snap_places[y_snap_places.length] = y;
    }
    else if (r == 6) {
      y = field_top+field_ycorner+goalie_y-half_prompt_height;
      y_snap_places[y_snap_places.length] = y;
    }
    else {
      y = y + Math.floor(grass_height*9/100)*2; 
      y_snap_places[y_snap_places.length] = y;
    }
  }
  
	// 计算 x 轴方向候选位置的坐标（横向有5个候选位置的情况） （共 5 个）  
  for (c=1; c<=5; ++c) {
      //layer = "prompt_five_" + r + "_" + c;
      if (c == 1) {
        x = field_left + field_xcorner + Math.floor(grass_width*12/100) - half_prompt_width;
      }
      else {
        x = x + Math.floor(grass_width*19/100);	
      }
      
      //layer_move(layer, x, y, true);
      
      // init five snap places
      five_x_snap_places[five_x_snap_places.length] = x;
  }
	// 计算 x 轴方向候选位置的坐标（横向有4个候选位置的情况） （共 5 个）  
  for (c=1; c<=5; ++c) {
      //layer = "prompt_four_" + r + "_" + c;
      if (c == 3) {
        var x_2 = field_left + field_xcorner + Math.floor(grass_width*12/100) + Math.floor(grass_width*19/100)*2 - half_prompt_width;
        //layer_move(layer, x_2, y, true);
        
        // init four snap places
        four_x_snap_places[four_x_snap_places.length] = x_2;
      }
      else {
        if(c == 1)
          x = field_left + field_xcorner + Math.floor(grass_width*14/100) - half_prompt_width;
        else
          x = x + Math.floor(grass_width*24/100);
        
        //layer_move(layer, x, y, true);
        
        // init four snap places
        four_x_snap_places[four_x_snap_places.length] = x;
      }
  }	
 
	// 门将的位置只有一个候选位置  
  one_x_snap_places[one_x_snap_places.length] = field_left+field_xcorner+goalie_x-half_prompt_width;
  
}



/**
 * function: 根据数据库中的阵型来初始化 player_formation
 *           5*5 + 1: all set to 0
 */
function init_player_formation()
{ 
	// 将 player_formation 中的元素的内容初始化为0  
	// 0 - 表示提示位置不会显示   
	// -1 - 表示提示位置显示，但没有球员在上面   
	for (var r=0; r<player_formation.length; ++r) {
    if (r == player_formation.length-1) { // gk's formation
      player_formation[r][0] = 0;
    }
    else { 
      for(var c=0; c<5; ++c) {
        player_formation[r][c] = 0;
      }	
    }
  }
  /*
	// 处理从数据库中读回来的阵型信息，然后对player_formation进行赋值  
  var data = ""; 
  data = document.save_form.data.value;   
	// 如果数据库中没有内容，则初始化为默认阵型 4-4-2  
  if (data == "") {
    player_formation[0][1] = "-1";
    player_formation[0][3] = "-1";
    player_formation[2][0] = "-1";
    player_formation[2][1] = "-1";
    player_formation[2][3] = "-1";
    player_formation[2][4] = "-1";
    player_formation[4][0] = "-1";
    player_formation[4][1] = "-1";
    player_formation[4][3] = "-1";
    player_formation[4][4] = "-1";
    player_formation[5][0] = "-1";
    return;
	// 如果数据库中有内容，则初始化 player_formation  
  }
  */
  
  /*
  var formation_array = data.split('&');
  for (var i=0; i<formation_array.length; ++i) { 
    var element_array = formation_array[i].split('_');
    if (element_array.length == 3) {
    	var player_id = element_array[0];
    	var row = element_array[1];
    	var col = element_array[2];
      player_formation[row][col] = player_id;  
    }
  }
  */
  	var pre_str = "place_select_"; 
  	for (i = 0; i < document.save_form.elements.length; i++) {
		select = document.save_form.elements[i].name;
		if (select.substring(0,13) == pre_str) { 
			var player_id = select.substring(13,select.length);
			var position = document.save_form.elements[i].value;
			if (position >= 100)
				continue;
			if (position == 0) {
		    	// 门将  
		    	player_formation[5][0] = player_id;
		    }
		    else if (position>=1 && position<=5) {
		    	// 后卫  
		    	var index = position-5;
		    	if (index < 0) index = 0 - index;
		    	player_formation[4][index] = player_id;
		    }
		    else if (position>=6 && position<=10) {
		    	// 中后场  
		    	var index = position-10;
		    	if (index < 0) index = 0 - index;
		    	player_formation[3][index] = player_id;
		    }
		    else if (position>=11 && position<=15) {
		    	// 中场  
		    	var index = position-15;
		    	if (index < 0) index = 0 - index;
		    	player_formation[2][index] = player_id;
		    }
		    else if (position>=16 && position<=20) {
		    	// 中前场  
		    	var index = position-20;
		    	if (index < 0) index = 0 - index;
		    	player_formation[1][index] = player_id;
		    }
		    else if (position>=21 && position<=25) {
		    	// 前锋  
		    	var index = position-25;
		    	if (index < 0) index = 0 - index;
		    	player_formation[0][index] = player_id;
		    }
		}
	}
}

// the event when the place change
function place_change(select_obj) {
	var pre_str = "place_select_";
	my_index  = select_obj.selectedIndex;
	my_name   = select_obj.name;
	
	var positions = new Array();
	// 查找place_select应有的selectedIndex，并把它存放在positions 数组中  
	for (i = 0; i < document.save_form.elements.length; i++) {
		select = document.save_form.elements[i].name;
		if (select.substring(0,13) == pre_str) { 
			obj = document.save_form.elements[i];
			for (var j=0; j<obj.options.length; ++j) {
		     	// Now store the index in an array
		   		positions[positions.length] = j;
			}
			break;
		}
	}
	
	// 遍历所有的place_select，取出它们的selectedIndex， 从positions中剔除该值  
	target_select_name = "";
	for (i = 0; i < document.save_form.elements.length; i++) {
		select = document.save_form.elements[i].name;
		if (select.substring(0,13) == pre_str) {
		   	index = document.save_form.elements[i].selectedIndex;
		   	name = document.save_form.elements[i].name;
		    for (var j=0; j<positions.length; ++j) {
		    	if (index == positions[j]) {
		    		if (my_index != positions[j]) {
			    		positions[j] = -1;
			    		break;
			    	}
			    	else if (name == my_name) {
			    		positions[j] = -1;
			    		break;
			    	}
		    	}
		    }
		    
		    if (index == my_index && name != my_name) {
		    	target_select_name = name;
		    }
		}
	}
	
	// 现在positions 中只剩下一个数值了，该数值就是另外一个需要做相应修改的place_select 的selectedIndex  
	target_select_index = 0;
	for (var j=0; j<positions.length; ++j) {
		if (positions[j] != -1) {
			target_select_index = positions[j];
		}	
	}
	
	eval("document.save_form." + target_select_name + ".selectedIndex=" + target_select_index);
	
	
	var my_player_id = my_name.substring(13, my_name.length);
	var target_player_id = target_select_name.substring(13, target_select_name.length);
	change_tpop_select_display(my_player_id, target_player_id);
	
	// 更新球场上球员的位置  
	init_player_formation();
	check_update_snaps();
	
	return true;
} 

// change the display of select: 
// KeyMan, TargetMan, Penalty
function change_tpop_select_display(my_player_id, target_player_id)
{
	// now this select's selectedIndex
	//alert(my_index);   alert(target_select_index);
	
	// another select's selectedIndex
	//alert(my_player_id); alert(target_player_id);	
	/*
	if ((my_index > 0 && my_index <= 10) && (target_select_index > 0 && target_select_index <= 10)) {
		// after changed, the two player are in tpop[0] ~ tpop[9]
		
	}
	else if ((my_index > 0 && my_index <= 10) && (target_select_index == 0 || target_select_index > 10)) {
		// after changed, this player is in tpop[0] ~ tpop[9], but another player is not in 
	}
	else if ((my_index == 0 || my_index > 10) && (target_select_index > 0 && target_select_index <= 10)) {
		// after changed, this player is not in tpop[0] ~ tpop[9], but another player is in
	}*/
	
	var select_name = "key_man";
	change_specify_select_display(select_name, my_player_id, target_player_id);
	
	select_name = "target_man";
	change_specify_select_display(select_name, my_player_id, target_player_id);
	
	select_name = "penalty";
	change_specify_select_display(select_name, my_player_id, target_player_id);
}

function change_specify_select_display(select_name, my_player_id, target_player_id)
{
	var select_obj = eval("document.save_form." + select_name);
	
	var select_value = select_obj.value;
	var o_my_option_index = -1, o_target_option_index = -1;
	
	
	var options_arr = select_obj.options;
	var len = options_arr.length;
	for (var i=0; i<len; ++i) {  
		if (options_arr[i].value == my_player_id) {  
			o_my_option_index = i;  
		}
		if (options_arr[i].value == target_player_id) {
			o_target_option_index = i;
		}		
	}
	
	if (o_my_option_index > 0) {
		options_arr[o_my_option_index].value = target_player_id;
		options_arr[o_my_option_index].text = get_gk_name(target_player_id);
	}
	if (o_target_option_index > 0) {
		options_arr[o_target_option_index].value = my_player_id;
		options_arr[o_target_option_index].text = get_gk_name(my_player_id);
	}
	
	var value_exist = false;
	for (var i=0; i<len; ++i) {  
		if (options_arr[i].value == select_value) {  
			options_arr[i].selected = true;
			value_exist = true;
		}
	}
	if (!value_exist) {
		select_obj.selectedIndex = 0;	
	}
}

// 根据 player_id 来查找 ppTable表，查出球员的姓名  
// 显示格式为： A, Smith  
// 其中，A为 given_name 的第一个字母的大写，Smith 为 family_name  
function get_player_name(player_id) 
{
	var given_name = eval("ppTable" + player_id + "[0]");
	var family_name = eval("ppTable" + player_id + "[1]");
	if (given_name == "")
		return format_player_name(family_name);
	else 
		return (format_player_name(given_name.substr(0, 1) + "."+ family_name));
}

function get_gk_name(player_id) 
{
	var given_name = eval("ppTable" + player_id + "[0]");
	var family_name = eval("ppTable" + player_id + "[1]");
	if (given_name == "")
		return family_name;
	else 
		return (given_name.substr(0, 1) + "."+ family_name);
}

// 格式化球员的名字  
function format_player_name(player_name) 
{
	var format_name = "";
	while (player_name.length > 0) {
		format_name += "<br>" +  player_name.substr(0, 6);
		player_name = player_name.substr(6, player_name.length);
	}
	
	format_name = format_name.substr(4, format_name.length-1);
	
	return format_name;
}




