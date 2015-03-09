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
	
	// drop, drag, pick
	if (with_count)
	  	on_drop = drop_with_count;
	else
	  	on_drop = drop;
	  
	on_drag = move;
	on_pick = pick;
	
	// ��ʼ����Ա�����ϵĺ�ѡλ�õ�����  
	init_prompt_place();
	// ��ʼ�����ϵķָ��ߵ�����  
	init_snap_lines();
	// �������ݿ��е���������ʼ�� player_formation
	init_player_formation();
	// ��ʼ��ҳ���е���������  
	init_other_parameters();
	
	
	
	check_update_snaps_begin();
	

	// ��ʼ����Ա���ܶ�·�� ( ע�⣺�ú���Ҫ���ں���check_update_snaps_begin() ����ִ�� )   
	init_run_direction();
} 

/**
 * function: check whether need to update the snaps
 */
function check_update_snaps()
{ 
	// ���� player_formation���� player_formation �����ݲ�Ϊ 0 ��Ԫ����ʾ  
	for (var r=0; r<player_formation.length-1; ++r) {
		if (player_formation[r][2] != 0) { 
			// �������Ա�ں���ĵ�3��λ�ã���ʹ�� five_x_snap_places �� five_x_snap_lines ��five��  
			for (var c=0; c<five_x_snap_places.length; ++c) { 
			  	snap_places[r][c] = new snap_point(five_x_snap_places[c], y_snap_places[r]);
			}
			x_snap_lines[r] = five_x_snap_lines;
		}
		else if (player_formation[r][1] != 0 || player_formation[r][3] != 0) {
			// ���û������Ա�ں���ĵ�3��λ�ã�   
			// ��������Ա�ں���ĵ�2����4��λ�ã���ʹ�� four_x_snap_places �� four_x_snap_lines ��four��  
			for (var c=0; c<four_x_snap_places.length; ++c) { 
			  	snap_places[r][c] = new snap_point(four_x_snap_places[c], y_snap_places[r]);
			}
			x_snap_lines[r] = four_x_snap_lines;
		}
		else if (player_formation[r][0] != 0 || player_formation[r][4] != 0){
			// ���û������Ա�ں���ĵ�3��λ�ã�   
			// ����û����Ա�ں���ĵ�2����4��λ�ã�  
			// ��������Ա�ں���ĵ�1����5��λ�ã���ʹ�� five_x_snap_places �� five_x_snap_lines ��five��  
			for (var c=0; c<five_x_snap_places.length; ++c) { 
			  	snap_places[r][c] = new snap_point(five_x_snap_places[c], y_snap_places[r]);
			}
			x_snap_lines[r] = five_x_snap_lines;
		}
		else {
			// �������û����Ա������һ��λ�ã���ʹ�� four_x_snap_places �� four_x_snap_lines ��four��  
			for (var c=0; c<four_x_snap_places.length; ++c) { 
			  	snap_places[r][c] = new snap_point(four_x_snap_places[c], y_snap_places[r]);;
			}
			x_snap_lines[r] = four_x_snap_lines;
		}
	}
	
	// ���Ŷ��Ž���λ�ý��д���  
	for (var c=0; c<one_x_snap_places.length; ++c) { 
	  	snap_places[player_formation.length-1][c] = new snap_point(
	                                                one_x_snap_places[c], 
	                                                y_snap_places[player_formation.length-1]);
	}
	x_snap_lines[player_formation.length-1] = one_x_snap_lines;
	
	// clear the player place on the player list
	//clear_player_place();
	// adjust the place of "prompt" and "player" on the field
	var layer;
	for (var r=0; r<snap_places.length; ++r) {  // ��Ϊ��ʱû���Ž�������snap_places.length -> snap_places.length-1  
		for (var c=0; c<snap_places[r].length; ++c) {
			// move the player
			var layer_p = "prompt_p_" + r + "_" + c; 
			var layer_x_p = layer_get_x(layer_p);
			var layer_y_p = layer_get_y(layer_p);
			// �����Ա�㵱ǰ��λ���� snap_places �е�λ����ͬʱ�������κζ���������������һ����Ա��  
			if (layer_x_p == snap_places[r][c].px && layer_y_p == snap_places[r][c].py) { 
				continue;		
			} 
			
			// �����Ա�㵱ǰ��λ���� snap_places �е�λ�ò���ͬʱ����Ҫ����Ա���λ�ý���΢��  
			layer = "prompt_" + r + "_" + c;
			layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, true); 

			if (player_formation[r][c] != 0) {
				layer = "prompt_p_n_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, false); 
				layer = "prompt_p_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, false); 
				layer = "player_div_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px-10, snap_places[r][c].py+30, false); 
			}
			else {
				layer = "prompt_p_n_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, true); 
				layer = "prompt_p_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, true); 
				layer = "player_div_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px-10, snap_places[r][c].py+30, true); 
			}
			
		} //for (var c=0; c<snap_places[r].length; ++c)
	}//for (var r=0; r<snap_places.length; ++r)
	
	// arrange the player list
	arrange_player_list();
}


function check_update_snaps_begin()
{ 
	// ���� player_formation���� player_formation �����ݲ�Ϊ 0 ��Ԫ����ʾ  
	for (var r=0; r<player_formation.length-1; ++r) {
		if (player_formation[r][2] != 0) { 
			// �������Ա�ں���ĵ�3��λ�ã���ʹ�� five_x_snap_places �� five_x_snap_lines ��five��  
			for (var c=0; c<five_x_snap_places.length; ++c) { 
			  	snap_places[r][c] = new snap_point(five_x_snap_places[c], y_snap_places[r]);
			}
			x_snap_lines[r] = five_x_snap_lines;
		}
		else if (player_formation[r][1] != 0 || player_formation[r][3] != 0) {
			// ���û������Ա�ں���ĵ�3��λ�ã�   
			// ��������Ա�ں���ĵ�2����4��λ�ã���ʹ�� four_x_snap_places �� four_x_snap_lines ��four��  
			for (var c=0; c<four_x_snap_places.length; ++c) { 
			  	snap_places[r][c] = new snap_point(four_x_snap_places[c], y_snap_places[r]);
			}
			x_snap_lines[r] = four_x_snap_lines;
		}
		else if (player_formation[r][0] != 0 || player_formation[r][4] != 0){
			// ���û������Ա�ں���ĵ�3��λ�ã�   
			// ����û����Ա�ں���ĵ�2����4��λ�ã�  
			// ��������Ա�ں���ĵ�1����5��λ�ã���ʹ�� five_x_snap_places �� five_x_snap_lines ��five��  
			for (var c=0; c<five_x_snap_places.length; ++c) { 
			  	snap_places[r][c] = new snap_point(five_x_snap_places[c], y_snap_places[r]);
			}
			x_snap_lines[r] = five_x_snap_lines;
		}
		else {
			// �������û����Ա������һ��λ�ã���ʹ�� four_x_snap_places �� four_x_snap_lines ��four��  
			for (var c=0; c<four_x_snap_places.length; ++c) { 
			  	snap_places[r][c] = new snap_point(four_x_snap_places[c], y_snap_places[r]);;
			}
			x_snap_lines[r] = four_x_snap_lines;
		}
	}
	
	// ���Ŷ��Ž���λ�ý��д���  
	for (var c=0; c<one_x_snap_places.length; ++c) { 
	  	snap_places[player_formation.length-1][c] = new snap_point(
	                                                one_x_snap_places[c], 
	                                                y_snap_places[player_formation.length-1]);
	}
	x_snap_lines[player_formation.length-1] = one_x_snap_lines;
	
	// clear the player place on the player list
	//clear_player_place();
	// adjust the place of "prompt" and "player" on the field
	var layer;
	for (var r=0; r<snap_places.length; ++r) {  // ��Ϊ��ʱû���Ž�������snap_places.length -> snap_places.length-1  
		for (var c=0; c<snap_places[r].length; ++c) {
			// move the player
			
			layer = "prompt_" + r + "_" + c;
			layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, true); 
			
			if (player_formation[r][c] != 0) {
				layer = "prompt_p_n_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, false); 
				layer = "prompt_p_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, false); 
				layer = "player_div_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px-10, snap_places[r][c].py+30, false); 
				if (player_formation[r][c] != -1) {
					layer = "player_div_" + r + "_" + c; 
					layer_show(layer);
					
					if (r == (snap_places.length-1)) {
	  					// GK
	  					layer_write(layer, get_gk_name(player_formation[r][c])); // write the player id
	  				}
	  				else {
	  					layer_write(layer, get_player_name(player_formation[r][c])); // write the player id
	  				}
	  				
				} //if (player_formation[r][c] != -1)
			}
			else {
				layer = "prompt_p_n_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, true); 
				layer = "prompt_p_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px, snap_places[r][c].py, true); 
				layer = "player_div_" + r + "_" + c; 
				layer_move(layer, snap_places[r][c].px-10, snap_places[r][c].py+30, true); 
			}
			
		} //for (var c=0; c<snap_places[r].length; ++c)
	}//for (var r=0; r<snap_places.length; ++r)
	
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
	var data = document.save_form.players.value;
	if (data == "") return;
	
	var top_y = top_coordinate;
	var y_interval = 26;
	var x1 = left_coordinate;
	var x2 = place_left_coordinate;
	var x3 = player_name_left_coordinate;
	var cur_y = top_y;
	var layer;
	       
	for (var r=player_formation.length-1; r>=0; --r) {  // ��Ϊ����û���Ž�������player_formation.length-1 -> player_formation.length-2
		if (r == player_formation.length-1) {
			// GK
			var c = 0;
			if (player_formation[r][c] != 0 && player_formation[r][c] != -1) {
				var player_id = player_formation[r][c];
				var place_str = "GK";   
				layer_write("place_"+player_id, place_str);
				
				// move the player layer
				layer_move("np"+player_id, x1, cur_y, true);
				layer_move("p"+player_id, x1, cur_y, true);
				layer_move("place_"+player_id, x2, cur_y, false);
				layer_move("player_name_"+player_id, x3, cur_y, false);
		   	
		   	 	cur_y += y_interval; 
			}
			
			continue;
		}
        
		for (var c=player_formation[r].length-1; c>=0; --c) {
			if (player_formation[r][c] != 0 && player_formation[r][c] != -1) {
				// remove the player_id from data
				player_id = player_formation[r][c];
				//data = remove_player_id_from_data(data, player_id);
				
				var place_str = "";
				place_str = place_name[r] + direction_name[c]; 
				layer_write("place_"+player_id, place_str);
				
				// move the player layer
				layer_move("np"+player_id, x1, cur_y, true);
				layer_move("p"+player_id, x1, cur_y, true);
				layer_move("place_"+player_id, x2, cur_y, false);
				layer_move("player_name_"+player_id, x3, cur_y, false);
    			
				cur_y += y_interval;     
			}
		}
	}
	
	// subs
	var subs_count = document.save_form.subs_count.value;
	var sub_num = 0;
	for (var i=0; i<formation_subs.length; ++i) {
		var player_id = formation_subs[i];
		
		if (player_id == -1) continue;
		++sub_num;
		layer_write("place_"+player_id, "S"+sub_num);
		
		// move the player layer
		layer_move("np"+player_id, x1, cur_y, true);
		layer_move("p"+player_id, x1, cur_y, true);
		layer_move("place_"+player_id, x2, cur_y, false);
		layer_move("player_name_"+player_id, x3, cur_y, false);
    	
		cur_y += y_interval; 		
	}   
	
	// others
	var other_num = 0;
	for (var i=0; i<formation_others.length; ++i) {
		var player_id = formation_others[i];
		
		if (player_id == -1) continue;
		
		if (sub_num < subs_count) {
			formation_subs[formation_subs.length] = player_id;
			formation_others[i] = -1;
			++ sub_num;
			layer_write("place_"+player_id, "S"+sub_num);
		}
		else {
			++ other_num;
			layer_write("place_"+player_id, "R"+other_num);
		}
		
		// move the player layer
		layer_move("np"+player_id, x1, cur_y, true);
		layer_move("p"+player_id, x1, cur_y, true);
		layer_move("place_"+player_id, x2, cur_y, false);
		layer_move("player_name_"+player_id, x3, cur_y, false);
    	
		cur_y += y_interval; 
		
	}   
	
}

/**
 * function: remove the player_id from data
 */
function remove_player_id_from_data(data, player_id)
{
	if (data.indexOf(player_id) != -1 && 
		  data.indexOf(player_id) == 0) {
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
 * function: ��ʼ����Ա�����ϵĺ�ѡλ�õ�����  
 */
function init_prompt_place()
{
	var r = 1;
	var c = 1;
	var layer;
	var x = 0;
	var y = 0;
	// ���� y �᷽���ѡλ�õ����� ���� 6 ����  
	for (r=1; r<=6; ++r) {
		if (r == 1) {
			y = field_top + field_ycorner + Math.floor(grass_height*10/100) - half_prompt_height;
		}
		else if (r == 6) {
		 	y = field_top+field_ycorner+goalie_y-half_prompt_height;
		}
		else {
		  	y = y + Math.floor(grass_height*9/100)*2; 
		}
		
		y_snap_places[y_snap_places.length] = y;
	}
	
	// ���� x �᷽���ѡλ�õ����꣨������5����ѡλ�õ������ ���� 5 ����  
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
	// ���� x �᷽���ѡλ�õ����꣨������4����ѡλ�õ������ ���� 5 ����  
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
	
	// �Ž���λ��ֻ��һ����ѡλ��  
	one_x_snap_places[one_x_snap_places.length] = field_left+field_xcorner+goalie_x-half_prompt_width;
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
	// ����x�᷽��ķָ��ߵ����꣨��5����ѡλ�õ������ ����6����  
	five_x_snap_lines[five_x_snap_lines.length] = field_left + field_xcorner; // ��1��  
	x = field_left + field_xcorner + Math.floor(grass_width*12/100 + grass_width*19/(100*2)); //12% + 19%/2 
	five_x_snap_lines[five_x_snap_lines.length] = x; // ��2��  
	for (var i=1; i<=3; ++i) {
	  	x += Math.floor(grass_width*19/100);  //19%
	  	five_x_snap_lines[five_x_snap_lines.length] = x; // �� 3 4 5 ��  
	} 
	five_x_snap_lines[five_x_snap_lines.length] = field_left + field_xcorner + grass_width; // ��6��  
	
	//four x
	// ����x�᷽��ķָ��ߵ����꣨��4����ѡλ�õ������ ����6����  
	four_x_snap_lines[four_x_snap_lines.length] = field_left + field_xcorner; // ��1��  
	x = field_left + field_xcorner + Math.floor(grass_width*14/100 + grass_width*24/(100*2)); //14% + 24%/2
	four_x_snap_lines[four_x_snap_lines.length] = x;  // ��2��  
	
	var second_four_center_x = field_left + field_xcorner + Math.floor(grass_width*14/100 + grass_width*24/100);
	var third_five_center_x = field_left + field_xcorner + Math.floor(grass_width*12/100) + Math.floor(grass_width*19/100)*2;
	var distance = third_five_center_x-second_four_center_x;
	var half_distance = Math.floor((distance)/2);
	x = second_four_center_x + half_distance;
	four_x_snap_lines[four_x_snap_lines.length] = x; // ��3��  
	
	x = x + half_distance*2;
	four_x_snap_lines[four_x_snap_lines.length] = x; // ��4��  
	
	x = x + half_distance + Math.floor(grass_width*24/(100*2));  //24%/2
	four_x_snap_lines[four_x_snap_lines.length] = x; // ��5��  
	
	four_x_snap_lines[four_x_snap_lines.length] = field_left + field_xcorner + grass_width; // ��6��  
	
	// one x
	// ����x�᷽��ķָ��ߵ����꣨�Ž�ֻ��1����ѡλ�õ������ ����2����  
	one_x_snap_lines[one_x_snap_lines.length] = field_left + field_xcorner; // ��1��  
	one_x_snap_lines[one_x_snap_lines.length] = field_left + field_xcorner + grass_width; // ��2��  
	
	
	// -------------------------------------------------------------------------
	// y �᷽��  
	// -------------------------------------------------------------------------
	// ����y�᷽��ķָ��ߵ����� ����7����  
	y_snap_lines[y_snap_lines.length] = field_top + field_ycorner; // ��1��  
	y = field_top + field_ycorner + Math.floor(grass_height*10/100 + grass_height*9/100);
	y_snap_lines[y_snap_lines.length] = y; // ��2��  
	
	for (var i=1; i<=4; ++i) {
	  	y += Math.floor(grass_height*9/100)*2;	
	  	y_snap_lines[y_snap_lines.length] = y; // �� 3 4 5 6 ��  
	}
	
	y_snap_lines[y_snap_lines.length] = field_top + field_ycorner + grass_height; // ��7��  
}

/**
 * function: �������ݿ��е���������ʼ�� player_formation  
 *           5*5 + 1: all set to 0
 */
function init_player_formation()
{
	// �� player_formation �е�Ԫ�ص����ݳ�ʼ��Ϊ0  
	// 0 - ��ʾ��ʾλ�ò�����ʾ   
	// -1 - ��ʾ��ʾλ����ʾ����û����Ա������   
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
	
	var default_formation_index = 
			new Array(
	            new Array(5, 0),  // �Ž�
	            new Array(0, 1), new Array(0, 3),   // ǰ��
	            new Array(2, 0), new Array(2, 1), new Array(2, 3), new Array(2, 4),  // �г�
	            new Array(4, 0), new Array(4, 1), new Array(4, 3), new Array(4, 4)   // ����
	                 );
	                                
	
	// ��������ݿ��ж�������������Ϣ��Ȼ���player_formation���и�ֵ  
	var data = "";
	data = document.save_form.data.value; 
	// ������ݿ���û�����ݣ����ʼ��ΪĬ������ 4-4-2  
	if (data == "") {
		for (var i=0; i<default_formation_index.length; ++i) {
			var row = default_formation_index[i][0];
			var col = default_formation_index[i][1];
			
			player_formation[row][col] = "-1";
		}
		
		return;
	}
	// ������ݿ��������ݣ����ʼ�� player_formation  
	var formation_array = data.split('&');
	var player_count = 0;
	for (var i=0; i<formation_array.length; ++i) { 
		var element_array = formation_array[i].split('_');
		if (element_array.length == 3) {
			var player_id = element_array[0];
			var row = element_array[1];
			var col = element_array[2];
		  	player_formation[row][col] = player_id;
		  	
		  	player_count ++;
		}
	}
	
	// ���´�����Ϊ�˱�֤������Ա����Ŀ���� 11 �������Ž���
	var rest = 11 - player_count;	
	for (var i=0; i<default_formation_index.length && rest>0; ++i) {
		var row = default_formation_index[i][0];
		var col = default_formation_index[i][1];
		if (player_formation[row][col] == 0) {
			
			player_formation[row][col] = "-1";  // �ø�λ�õ�prompt����ʾ����
			
			-- rest;
			
		}	
		else {
			continue;	
		}
	}
	
	
	var subs_data = "";
	subs_data = document.save_form.subs_data.value; 
	if (subs_data != "") {
		formation_subs = subs_data.split('&');
	}
	
	var others_data = "";
	others_data = document.save_form.others_data.value; 
	if (others_data != "") {
		formation_others = others_data.split('&');
	}
}

/**
 * function: ��ʼ��ҳ���е���������  
 * focus passing
 * mentality
 */
function init_other_parameters()
{
	var passing_style_value = document.save_form.passing_style_value.value;
	var mentality_value = document.save_form.mentality_value.value;
	
	if (passing_style_value != "")
	  	document.save_form.passing_style.value = passing_style_value;
	if (mentality_value != "")
	  	document.save_form.mentality.value = mentality_value;
}

/**
 * function: ��ʼ����Ա���ܶ�·��    
 */
function init_run_direction()
{
	var tactics_run_data = document.save_form.tactics_run_data.value;
	 
	var run_arr = tactics_run_data.split("&");
	var len = run_arr.length;
	for (var i=0; i<len; ++i) {  
		var one_run_str = run_arr[i];    
		var one_run_arr = one_run_str.split("_");
		
		var j = 0;
		var player_id 	= one_run_arr[j++];
		var from_r 		= one_run_arr[j++];
		var from_c 		= one_run_arr[j++];
		var to_r 		= one_run_arr[j++];
		var to_c 		= one_run_arr[j++];
		
		// ��һ����ͷ�ߣ�����������ʼλ��ָ����ֹλ��
		draw_one_run_direction(player_id, from_r, from_c, to_r, to_c);
		
	}
	
}


/**
 * function: when the player layer is not layed on the field, 
 *           then remove it back to its original place
 */
function reset_player(layer)
{
	if (layer.indexOf("_p_") == -1) { 
		// layer �����ϵ���Ա�㣬���� player_list �ϵĲ�  
		var layer2 = "n" + layer; // the "blank shirt" layer
		var x = layer_get_x(layer2); // for the "blank shirt" layer has not been moved
		var y = layer_get_y(layer2);
		layer_move(layer, x, y, true);
	}
	else { 
		// layer �����ϵ���Ա��  
		var row_col_arr = get_row_col(layer);
		var old_r_index = row_col_arr[0];
		var old_c_index = row_col_arr[1];
		if (old_r_index >=0 && old_r_index <= player_formation.length) { // do not know how to make sure the "old_c_index" is valuable
			layer_write("player_div_"+old_r_index+"_"+old_c_index, ""); 
			formation_others[formation_others.length] = player_formation[old_r_index][old_c_index];
			player_formation[old_r_index][old_c_index] = -1; // "-1" means keep showing the place, but the player name is null
		}
	}
} 

/**
 * function: remove all the player layers back to its original place
 */
function reset_all()
{
	// �����ϵ���Ա���  
	for (var r=0; r<snap_places.length; ++r) { // ��Ϊ��ʱû���Ž�������snap_places.length -> snap_places.length-1  
		for (var c=0; c<snap_places[r].length; ++c) {
			if (player_formation[r][c] != 0 && player_formation[r][c] != -1) {
				// ���prompt��������Ա��ʱ�򣬽����ϵ���Ա���  
				var layer = "prompt_p_" + r + "_" + c; 
				reset_player(layer); 
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

  var data = "";
  var c1 = 0, c2 = 0, c3 = 0;

  var forw = 0, mids = 0, defs = 0;
  // get the info of player layers on the field
 

  for (var r=0; r<player_formation.length; ++r) {// ��Ϊ��ʱû���Ž�������player_formation.length -> player_formation.length-1
    for (var c=0; c<player_formation[r].length; ++c) {
      if (player_formation[r][c] == 0 || player_formation[r][c] == -1)
        continue;
        
      //var primary_player_id = eval("ppTable" + player_formation[r][c] + "[0]");  
      var primary_player_id = player_formation[r][c]; 
      var index = 25 - ((r*5) + c); 
      data += "&" + primary_player_id + "_" + index;
      
      
      if (r == player_formation.length-1) {
      	// goal keeper
        ++c1;
      }
      else {
      	// normal player
      	++c3;
        if (r == 0)
          ++forw;
        else if (r >=1 && r <=player_formation.length-3)
          ++mids;
        else if (r == player_formation.length-2)
          ++defs;
      }
        
    }  
  }
  

  // check whether the number on the field is appropriate
  if (forw < min_forw)
    alert('not_enough_forwards');
  else if (mids < min_mids)
    alert('not_enough_midfielders');
  else if (defs < min_defs)
    alert('not_enough_defenders');
//  else if (c1 == 0)
//    alert('no_goalie_selected');
//  else if (c3 != 10)
//    alert('not_11_players');
  else if (c2 > 5)
    alert('to_many_substitutes');
  else if (c1 > 1)
    alert('to_many_goalies');
  else {  
  	var sub_index = 99;  // ��Ϊ�油�Ǵ� 100 ��ʼ������  
  	var subs_data = "";
  	for (var i=0; i<formation_subs.length; ++i) {
  		var primary_player_id = formation_subs[i];
  		if (primary_player_id != -1) {
  			++ sub_index;
      		subs_data += "&" + primary_player_id + "_" + sub_index;
  		}
  	}
  	var other_index = 0;
  	var others_data = "";
  	for (var i=0; i<formation_others.length; ++i) {
  		var primary_player_id = formation_others[i];
  		if (primary_player_id != -1) {
  			++ other_index;
      		others_data += "&" + primary_player_id + "_" + other_index;
  		}
  	}
  	
    data = data.substr(1); //remove & from the beginning
    subs_data = subs_data.substr(1); //remove & from the beginning
    others_data = others_data.substr(1); //remove & from the beginning
    document.save_form.data.value = data; 
    document.save_form.subs_data.value = subs_data; 
    document.save_form.others_data.value = others_data; 
    // run_direction
    document.save_form.tactics_run_data.value = get_tactics_run_data(); 
    
    disable_submit_button(document.save_form);
    return true;
  }
  return false;
}


/**
 * function: Call from <form> tag: onSubmit='disable_submit_button(this)'
 */
function get_tactics_run_data()
{
	var agt = navigator.userAgent.toLowerCase();
	var is_ie = (agt.indexOf("msie") != -1);
	var is_ie5 = (agt.indexOf("msie 5") != -1);
	var all_elements = (is_ie || is_ie5) ? document.all : document.getElementsByTagName( "*" );
	
	var tactics_run_data = "";
	var index = 0;
	var pre_str = "run_direction_";
	var len = all_elements.length;    
	for (var i=0; i<len; ++i) {   
		// ֻ�� div ��Ա���д���  
		if (all_elements[i].tagName != "DIV") continue;  
		
		var id = all_elements[i].id;
		
		
		if ( id.substr(0, pre_str.length) == pre_str ) 
		{  
			var run_from = all_elements[i].run_from;  
			var run_to = all_elements[i].run_to;  
			
			var run_from_arr = run_from.split("_");
			if (run_from_arr.length != 3) continue;
			var run_to_arr = run_to.split("_");
			if (run_to_arr.length != 3) continue;
			
			var player_id = id.replace(pre_str, "");
			var from_r = run_from_arr[1];
			var from_c = run_from_arr[2];
			var to_r = run_to_arr[1];
			var to_c = run_to_arr[2];
			
			var pop_id = getPopIdByRowCol(from_r, from_c); 
			
			// 
			if (index != 0) {
				tactics_run_data += "&";
			}	
			
			tactics_run_data += player_id + "_"  
			                  + pop_id + "_"
			                  + from_r + "_"
			                  + from_c + "_"
			                  + to_r + "_"
			                  + to_c;
		
			++ index;
		}
	}
	
	return tactics_run_data;
}

function getPopIdByRowCol(from_r, from_c)
{
	var pop_id = 0;
	
	for (var r=0; r<player_formation.length-1; ++r) 
	  for(var c=0; c<5; ++c) 
	  	if (player_formation[r][c] != 0) {
	  		
	  		pop_id ++;
	  		
	  		if (r == from_r && c == from_c) {
	  			return pop_id;	
	  		}
	  	}
	
	return 0;
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
 * function: get the real pos of the player layer
 * which=1: get the real x; else: get the real y
 */
//function get_rel_pos(layer, which)
//{
//	var ret;
//	if (which == 1) {
//		var x = layer_get_x(layer) - field_left  - field_xcorner;
//		x += half_shirt_width;
//		x = (x*1000)/grass_width;
//		x = Math.ceil(x);
//		ret = x;
//	}
//	else {
//		var y = layer_get_y(layer) - field_top  - field_ycorner;
//		y += half_shirt_height;
//		y = (y*1000)/grass_height;
//		y = Math.ceil(y);
//		ret = y+2;
//	}
//	return ret;
//}

/**
 * function: check whether the player layer is on the field
 */
function is_normal(layer) 
{
	var x = layer_get_x(layer);
	var y = layer_get_y(layer);
	var a = false, b = false;
	if (x >= field_left + field_xcorner && x <= field_left + field_xcorner+grass_width - shirt_width)
		a = true;
	if (y >= field_top + field_ycorner && y <= field_top + field_ycorner + grass_height - shirt_height)
		b = true; 
	if (a && b)
		return true;
	else
		return false;
}

/**
 * function: check whether the player layer is as a goal keeper
 */
function is_goalie(layer) 
{
	var x = layer_get_x(layer);
	var y = layer_get_y(layer);
	if (x == goalie_x + field_left + field_xcorner - half_shirt_width && 
	    y == goalie_y + field_top + field_ycorner - half_shirt_height)
	  	return true;
	return false;
}

/**
 * function: check whether the player layer is as a sub player
 */
function is_sub(layer) 
{
//  var x = layer_get_x(layer);
//  var y = layer_get_y(layer);
//
//  if (x == subst_gk_x + field_left && y == subst_gk_y + field_top)
//    return true;
//  if (x == subst_def_x + field_left && y == subst_def_y + field_top)
//    return true;
//  if (x == subst_mid_x + field_left && y == subst_mid_y + field_top)
//    return true;
//  if (x == subst_att_x + field_left && y == subst_att_y + field_top)
//    return true;
//  if (x == subst_ext_x + field_left && y == subst_ext_y + field_top)
//    return true;
//
  return false;
}


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

//  var a = false;
  // ���������Ա�����油������ͨ��Ա�������˵Ļ����Ϳ��Է��ڳ���  
//  if (is_goalie(layer) || is_sub(layer) || is_normal(layer)) { 
//    a = true;
//  }
  
//  if (!a) //send him back
//    reset_player(layer);
    
  //writePlayerInfo("nodata");

  
	// drop������ʱ�����ؿ��Դ����Ա������ [4-1]  
	if (layer.indexOf("_p_") != -1) { // ֻ��drop�˳��ϵ���Ա�Ż�������ʾ  
		for (var r=0; r<snap_places.length; ++r) { // ��Ϊ��ʱû���Ž�������snap_places.length -> snap_places.length-1
			for (var c=0; c<snap_places[r].length; ++c) { 
			    var layer = "prompt_" + r + "_" + c;
			    layer_hide(layer);
			}
		}
	}
}

/**
 * function: pick the player layer
 */
function pick() 
{
	var layer = active_layer;
	last_x = layer_get_x(active_layer);
	last_y = layer_get_y(active_layer);
	
	// pick������ʱ����ʾ���Դ����Ա������ [3-10]  
	if (layer.indexOf("_p_") != -1) { // ֻ��pick�˳��ϵ���Ա�Ż�����ʾ����  
		for (var r=0; r<snap_places.length; ++r) { // ��Ϊ��ʱû���Ž�������snap_places.length -> snap_places.length-1  
			for (var c=0; c<snap_places[r].length; ++c) {
			    var layer = "prompt_" + r + "_" + c;
			    layer_show(layer);
			}
		}
	}
    
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

/**
 * function: get the "row" index and "col" from prompt layer
 */
function get_row_col(prompt_layer) 
{
	var row_col_str = prompt_layer.substr(prompt_layer.indexOf("_p_")+3);
	var row_col_arr = row_col_str.split("_");
	return row_col_arr;
}

/**
 * function: when the standard_tactics changed, this event begin
 */
function standard_tactics_change(control) 
{
	// get the standard tactics value and store it into array "tactics_array"
	var standard_tactics_value = "f" + control.value;
	standard_tactics_value = standard_tactics_value.replace(/\-/g, "");
	standard_tactics_value = standard_tactics_value.replace(/\ /g, "");
	
	var tactics_array = eval(standard_tactics_value);
	
	
	// store the player_formation into a array "temp_array"
	var temp_array = new Array();
	var temp_index = 0;
	for (var r=0; r<player_formation.length-1; ++r) 
	  for(var c=0; c<5; ++c) 
	  	if (player_formation[r][c] != 0) {
	  		temp_array[temp_index++] = player_formation[r][c];
	  		player_formation[r][c] = 0;
	  	}
	  
	temp_index = 0;
	var row = 0;
	var col = 0;
	for(var i=0; i<tactics_array.length; ++i) {
		if (tactics_array[i] != 0) {
			row = parseInt(i / 5);
			col = parseInt(i % 5); 
			player_formation[row][col] = temp_array[temp_index++];	
		}
	}
	
	// update the formation display
	check_update_snaps_begin();
}

// ���� player_id ������ ppTable�������Ա������  
// ��ʾ��ʽΪ�� A.Smith  
// ���У�AΪ given_name �ĵ�һ����ĸ�Ĵ�д��Smith Ϊ family_name  
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

// ��ʽ����Ա������  
function format_player_name(player_name) 
{
	var format_name = "";
	while (player_name.length > 0) {
		format_name += "<br>" +  player_name.substr(0, 7);
		player_name = player_name.substr(7, player_name.length);
	}
	
	format_name = format_name.substr(4, format_name.length-1);
	
	return format_name;
}



