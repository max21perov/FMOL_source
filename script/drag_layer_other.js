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


//default values
limit_min_x = 0;
limit_max_x = 0;
limit_min_y = 300;
limit_max_y = 300;

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


