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

// setTimeout 函数的返回值       
var timer;

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
	
	
	// 因为发现只有先执行alter("show formation")后，函数check_update_snaps才会把球员显示出来      
	// 但弹出alter窗口不是很好，所以使用另外一种方式：setTimeout("check_update_snaps()", 10);	
	// 造成这种错误的原因是：函数check_update_snaps中需要先缓冲，才能显示球员，否则不能显示球员      
	timer = setTimeout("check_update_snaps()", 10);	
	// alter("show formation");
	//check_update_snaps();  
	
} 



/**
 * function: check whether need to update the snaps  
 */
function check_update_snaps()
{   
	// 清空timer，与setTimeout对称使用      
	clearTimeout(timer);
	
	
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




