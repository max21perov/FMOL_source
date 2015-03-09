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
  

  
	// ��ʼ����Ա�����ϵĺ�ѡλ�õ�����  
  init_prompt_place();

	// �������ݿ��е���������ʼ�� player_formation  
  init_player_formation();


  check_update_snaps_begin();

} 

/**
 * function: check whether need to update the snaps
 */
function check_update_snaps_begin()
{ 
	// ���� player_formation���� player_formation �����ݲ�Ϊ 0 ��Ԫ����ʾ  
	for (var r=0; r<player_formation.length-1; ++r) {
		if (player_formation[r][2] != 0) { 
			// �������Ա�ں���ĵ�3��λ�ã���ʹ�� five_x_snap_places �� five_x_snap_lines ��five��  
			for (var c=0; c<five_x_snap_places.length; ++c) { 
				snap_places[r][c] = new snap_point(five_x_snap_places[c], y_snap_places[r]);
			}
		}
		else if (player_formation[r][1] != 0 || player_formation[r][3] != 0) {
			// ���û������Ա�ں���ĵ�3��λ�ã�   
			// ��������Ա�ں���ĵ�2����4��λ�ã���ʹ�� four_x_snap_places �� four_x_snap_lines ��four��  
			for (var c=0; c<four_x_snap_places.length; ++c) { 
				snap_places[r][c] = new snap_point(four_x_snap_places[c], y_snap_places[r]);
			}
		}
		else if (player_formation[r][0] != 0 || player_formation[r][4] != 0){
			// ���û������Ա�ں���ĵ�3��λ�ã�   
			// ����û����Ա�ں���ĵ�2����4��λ�ã�  
			// ��������Ա�ں���ĵ�1����5��λ�ã���ʹ�� five_x_snap_places �� five_x_snap_lines ��five��  
			for (var c=0; c<five_x_snap_places.length; ++c) { 
				snap_places[r][c] = new snap_point(five_x_snap_places[c], y_snap_places[r]);
			}
		}
		else {
			// �������û����Ա������һ��λ�ã���ʹ�� four_x_snap_places �� four_x_snap_lines ��four��  
			for (var c=0; c<four_x_snap_places.length; ++c) { 
				snap_places[r][c] = new snap_point(four_x_snap_places[c], y_snap_places[r]);;
			}
		}
	}
	
	// ���Ŷ��Ž���λ�ý��д���  
	for (var c=0; c<one_x_snap_places.length; ++c) { 
		snap_places[player_formation.length-1][c] = new snap_point(
		                                              one_x_snap_places[c], 
		                                              y_snap_places[player_formation.length-1]);
	}
  
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
			 
			
			if (player_formation[r][c] != 0) {
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



