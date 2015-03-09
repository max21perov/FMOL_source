// this file stores the common functions of tactics

// -------------------------------------------------

/**
 * function: handle the layer: "info_layer"
 * show, hide, over, out
 */
function show_info_layer(layer, player_id, left_or_right)
{  
   for(var i=0; i<timers.length; ++i)clearTimeout(timers[i]);
   timers = [];
   
   if (document.layers) {
     if(document.layers[layer].visibility == "show") return;}
   else if(document.all){
     if(document.all(layer).style.visibility == "visible") return;}
   else if(document.getElementById){
     if(document.getElementById(layer).style.visibility == "visible") return;}
   
   timers[timers.length] = setTimeout("show_layer_under_mouse('"+layer+"', '" + player_id + "', " + left_or_right + ")", 1000);
}

function show_layer_under_mouse(layer, player_id, left_or_right) 
{  
	var layer_name = layer;
	//var player_id = layer_name.replace("info_", "");
	// 得到“网页正文全文高”  
	var scrollHeight = document.body.scrollHeight;
	var layer_height = layer_get_h(layer);
	var show_info_y = mouse_y-1;
	// 如果要显示的层要超出页面的可视范围，则要将层向上移动  
	if (mouse_y + layer_height >= scrollHeight)
		show_info_y = scrollHeight - layer_height;
	
	if (left_or_right == true) {
		layer_move(layer, 
	           mouse_x+5-layer_get_w(layer), 
	           show_info_y,
	           false);
	}
	else {
		layer_move(layer, 
	           mouse_x-5, 
	           show_info_y,
	           false);
	}
	
  	
  	// get the layer_obj by the layer_name  
  	var layer_obj;
  	if (document.all) {  
	  	layer_obj = document.all(layer); 
	}
	else if (document.layers) {  
	  	layer_obj = document.layers[layer];
	}
	else if (document.getElementById) {  
	  	layer_obj = document.getElementById(layer);
	}
    
  	draw_info_layer_content(layer_obj, player_id);
}

function hide_info_layer(layer)
{
  for(var i=0; i<timers.length; ++i) clearTimeout(timers[i]);
  timers = [];
}

function over_info_layer(layer)
{
  if (document.layers && document.layers[layer].visibility == "hide" ) {
  	layer_show(layer);
  }
  else if(document.all && document.all(layer).style.visibility == "hidden") {//alert("over");
    layer_show(layer);
  }
  else if(document.getElementById && document.getElementById(layer).style.visibility == "hidden") {
    layer_show(layer);
  }
  
}

function out_info_layer(layer)
{
  if (document.layers && document.layers[layer].visibility == "show" ) {
  	layer_hide(layer);
  }
  else if(document.all && document.all(layer).style.visibility == "visible") {//alert("out");
    layer_hide(layer);
  }
  else if(document.getElementById && document.getElementById(layer).style.visibility == "visible") {
    layer_hide(layer);
  }
}

function draw_info_layer_content(layerObj, player_id)
{
	// because in Internet Explorer, the div must contain the <frame> to be shown above the "select" element
	var layer_html = layerObj.innerHTML;
	var frame_str = "</IFRAME>";
	var pos = layer_html.indexOf(frame_str);
	if (pos != -1) {
		layerObj.innerHTML = layer_html.substr(0, (pos+frame_str.length));	
	}
	else { 
		layerObj.innerHTML = "";
	}
	
	
	layerObj.appendChild(getPlayerPropertyTable(player_id));  // getPlayerPropertyTable is defined in file "tactics_functions.js"
	
}


// -------------------------------------------------

// get the player property table 
function getPlayerPropertyTable(player_id)
{
	// the property of player
	var ppTable = eval("ppTable" + player_id);
	var i = 0;
	var given_name 		= ppTable[i++];
	var family_name 	= ppTable[i++];
	var position 		= ppTable[i++];
	var prefer_foot 	= ppTable[i++];
	var cloth_number 	= ppTable[i++];
	var age 			= ppTable[i++];
	var pace 			= ppTable[i++];
	var power 			= ppTable[i++];
	var stamina 		= ppTable[i++];
	var height 			= ppTable[i++];
	var finishing 		= ppTable[i++];
	var passing 		= ppTable[i++];
	var crossing 		= ppTable[i++];
	var ball_control 	= ppTable[i++];
	var tackling 		= ppTable[i++];
	var heading 		= ppTable[i++];
	var play_making 	= ppTable[i++];
	var off_awareness 	= ppTable[i++];
	var def_awareness 	= ppTable[i++];
	var experience 		= ppTable[i++];
	var agility 		= ppTable[i++];
	var reflex 			= ppTable[i++];
	var handing 		= ppTable[i++];
	var rushing_out 	= ppTable[i++];
	var positioning 	= ppTable[i++];
	var aerial_ability 	= ppTable[i++];
	var judgment 		= ppTable[i++];
	var form_ 			= ppTable[i++];
	var condition 		= ppTable[i++];
	var morale 			= ppTable[i++];
	var happiness 		= ppTable[i++];
	
	// can add other property here
	var table_obj = createTable(0);
	var row_obj = createRow(table_obj);
	var col_obj = createColumn(row_obj, "left"); 
	col_obj.colSpan = "4";
	col_obj.className = "gSGSectionColumnHeadings";
	col_obj.innerHTML = "&nbsp; Player Property";
	
	row_obj = createRow(table_obj);
	col_obj = createColumn(row_obj, "left"); 
	col_obj.colSpan = "4";
	col_obj.innerHTML = "&nbsp;" + getPlayerName(given_name, family_name);
	
	row_obj = createRow(table_obj);
	col_obj = createColumn(row_obj, "left"); 
	col_obj.colSpan = "4";
	col_obj.innerHTML = "&nbsp;" + getPositionStr(position) + ", " + height + " cm, " + getPreferFootStr(prefer_foot);
	
	// the child tables of table_obj
	var table_obj_child1 = createTable(1, "#6A71A3");
	var table_obj_child2 = createTable(1, "#6A71A3");
	
	row_obj = createRow(table_obj);
	var col_obj_1 = createColumn(row_obj, "left"); 
	var col_obj_2 = createColumn(row_obj, "left");
	col_obj_1.vAlign = "top";
	col_obj_2.vAlign = "top"; 
	col_obj_1.innerHTML = "";
	col_obj_2.innerHTML = ""; 
	col_obj_1.appendChild(table_obj_child1);
	col_obj_2.appendChild(table_obj_child2);
	
	var property_arr;
	if (position == 0) {
		// GK
		property_arr = [["&nbsp;Physical"], 
						["agility", agility], ["reflex", reflex],
						["&nbsp;Mental"], 
						["judgment", judgment], ["experience", experience], 
						["&nbsp;Routine"], 
						["form", form_], ["condition", condition+"%"], 
						["morale", morale]];
		table_obj_child1 = produceRowObj(table_obj_child1, property_arr);
		
		
		property_arr = [["&nbsp;Technical"], 
						["handing", handing], ["rushing_out", rushing_out], 
						["positioning", positioning], ["aerial ability", aerial_ability]];
		table_obj_child2 = produceRowObj(table_obj_child2, property_arr);
		
	}
	else {
		// non GK, normal player
		
		property_arr = [["&nbsp;Physical"], 
						["pace", pace], ["power", power], 
						["stamina", stamina], 
						["&nbsp;Mental"], 
						["off aware", off_awareness], ["def aware", def_awareness], 
						["play making", play_making], ["experience", experience], 
						["&nbsp;Routine"], 
						["form", form_] , ["condition", condition+"%"], 
						["morale", morale]];
		table_obj_child1 = produceRowObj(table_obj_child1, property_arr);
		
		
		property_arr = [["&nbsp;Technical"], 
						["finishing", finishing], ["ball control", ball_control], 
						["passing", passing], ["crossing", crossing],
						["heading", heading], ["tackling", tackling]];
		table_obj_child2 = produceRowObj(table_obj_child2, property_arr);
	}
	
	return table_obj;
	
}

// form the player name by the given_name and family_name
function getPlayerName(given_name, family_name)
{
	var player_name = "";
	if (given_name != "") { player_name = given_name + "." + family_name; }
	else { player_name = family_name; }	
		
	return player_name;
}

// translate the position_code to position_str
function getPositionStr(position_code)
{
	var position_str = "";
	var position_arr = [
			"GK", "DC", "DL", "DR", "DMC", 
			"DML", "DMR", "MC", "ML", "MR",
			"AMC", "AML", "AMR", "F"];
	position_str = position_arr[position_code];
	
	return position_str;
}

// translate the prefer_foot_code to prefer_foot_str
function getPreferFootStr(prefer_foot_code)
{
	var prefer_foot_str = "";
	var prefer_foot_arr = [
			"Left Only", "Left", "Either", "Right", "Right Only"];
	prefer_foot_str = prefer_foot_arr[prefer_foot_code+2];
	
	return prefer_foot_str;
}

// by the property_arr, produce the row of table_obj
function produceRowObj(table_obj, property_arr)
{
	var row_obj, col_obj;
	
	var len = property_arr.length;
	for (var i=0; i<len; ++i) {
		
		var property_obj = property_arr[i];
		if (property_obj.length == 1) {
			// title
			row_obj = createRow(table_obj);			
			col_obj = createColumn(row_obj, "left"); 
			col_obj.colSpan = "2";
			col_obj.bgColor = "#FFCC00";
			col_obj.height = "20";
			col_obj.innerHTML = property_obj[0];
		}
		else {
			// content
			row_obj = createRow(table_obj);
			col_obj = createColumn(row_obj, "right"); 
			col_obj.innerHTML = property_obj[0] + "&nbsp;";
			col_obj = createColumn(row_obj, "left"); 
			col_obj.innerHTML = "&nbsp;" + property_obj[1];
		}
	}
	
	return table_obj;	
}

// create table
function createTable(cell_spacing, bg_color) 
{   
	var oTable = document.createElement("table");
	oTable.width = "100%";
	oTable.border = 0;
	oTable.cellSpacing = cell_spacing;
	oTable.cellPadding = 0;
	oTable.bgColor = bg_color;
	
	return oTable;
}

// create row of table
function createRow(table) {
	var rowNode = table.insertRow(-1);
	rowNode.className = "gSGRowEven";
	return rowNode;
}

// create column of row
function createColumn(row, align_flag) {
	var colNode = row.insertCell(-1);
	colNode.align = align_flag;
	colNode.wrap = true;
	
	return colNode;
}




