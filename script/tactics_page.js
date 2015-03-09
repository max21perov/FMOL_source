// this is the init code of tactics page
alert(0);
var rowNo = 0;
var content_ads = null;
var btchina_query = '';


var HINTS_CFG = {
	'top'        : 5, // a vertical offset of a hint from mouse pointer
	'left'       : 5, // a horizontal offset of a hint from mouse pointer
	'css'        : 'hintsClass', // a style class name for all hints, TD object
	'show_delay' : 1000, // a delay between object mouseover and hint appearing
	'hide_delay' : 25000, // a delay between hint appearing and hint hiding
	'out_delay'  : 100,
	'out2_delay' : 15000,
	'wise'       : 1,
	'follow'     : false,
	'z-index'    : 10 // a z-index for all hint layers
};

var HINTS_ITEMS = [];

var myHint = THints==null?null:new THints (HINTS_CFG, HINTS_ITEMS);

function mywrap (title, content) 
{
  var temp = '<table border="0" cellspacing="1" cellpadding="3" class="tool_tip_bg"'
			  + (content==''?'':' width="'
			  //+ Math.max(myHint.getWinSz()*0.382,305)
			  + '"')
			  + '><tr class="tool_tip_row"><td><table border="0"  cellpadding="0" width=100%><tr><td nowrap><b>'
			  + title 
			  + '</b></td></tr></table></td></tr>' 
			  + (content==''||content==' '
			  ? (null==content_ads?'':('<tr class="tool_tip_row"><td>' + content_ads[rowNo%content_ads.length] + '</td></tr>'))
			  :'<tr class="tool_tip_row"><td>' + content
			  + (null==content_ads?'':('<br>' + content_ads[rowNo%content_ads.length]) )
			  + '</td></tr>')
			  + '</table>';
  return temp;			
}
