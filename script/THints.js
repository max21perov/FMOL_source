// Title: Tigra Hints
// URL: http://www.softcomplex.com/products/tigra_hints/
// Version: 1.2
// Date: 04/18/2003 (mm/dd/yyyy)
// Feedback: feedback@softcomplex.com (specify product title in the subject)
// Note: Permission given to use this script in ANY kind of applications if
//    header lines are left unchanged.
// About us: Our company provides offshore IT consulting services.
//    Contact us at sales@softcomplex.com if you have any programming task you
//    want to be handled by professionals. Our typical hourly rate is $20.

function THints (o_cfg, items) {
	this.top = o_cfg.top ? o_cfg.top : 0;
	this.left = o_cfg.left ? o_cfg.left : 0;
	this.n_dl_show = o_cfg.show_delay;
	this.n_dl_hide = o_cfg.hide_delay;
	this.b_wise = o_cfg.wise;
	this.b_follow = o_cfg.follow;
	this.out_delay = o_cfg.out_delay;
	this.x = 0;
	this.y = 0;
	this.divs = [];
	this.show  = TTipShow;
	this.showD = TTipShowD;
	this.hide = TTipHide;
	this.hideD = TTipHideD;
	this.move = TTipMove;
	this.items = items;
	if (document.layers) return;
	this.b_IE = navigator.userAgent.indexOf('MSIE') > -1 && !window.opera,
	s_tag = ['<div id="TTip%name%" style="visibility:hidden;position:absolute;top:0px;left:0px;',   this.b_IE ? 'width:1px;height:1px;' : '', o_cfg['z-index'] != null ? 'z-index:' + o_cfg['z-index'] : '', '" onMouseOver="myHint.show();" onMouseOut="myHint.hide(' + o_cfg.out2_delay + ');"><table cellpadding="0" cellspacing="0" border="0"><tr><td id="ToolTip%name%" class="', o_cfg.css, '" nowrap>%text%</td></tr></table></div>'].join('');


	this.getElem = 
		function (id) { return document.all ? document.all[id] : document.getElementById(id); };
	this.showElem = 
		function (id, hide) { this.divs[id].o_css.visibility = hide ? 'hidden' : 'visible'; };
	this.getWinSz = window.innerHeight != null 
		? function (b_hight) { return b_hight ? innerHeight : innerWidth; }
		: function (b_hight) { return document.body[b_hight ? 'clientHeight' : 'clientWidth']; };	
	this.getWinSc = window.innerHeight != null 
		? function (b_hight) { return b_hight ? pageYOffset : pageXOffset; }
		: function (b_hight) { return document.body[b_hight ? 'scrollTop' : 'scrollLeft']; };	
//	if (document.all) {
//		document.onclick = function (e) {
//      whichIt = event.srcElement;
//  		while (whichIt != myHint.divs[0].o_obj) {
//	  		whichIt = whichIt.parentElement;
//		  	if (whichIt == null){
//    		  myHint.hide(0);
//		  	  return true;
//		  	}
//		  }
//		  return true;
//		};
//	}
//	if (window.opera) {
//		this.getSize = function (id, b_hight) { 
//			return this.divs[id].o_css[b_hight ? 'pixelHeight' : 'pixelWidth']
//		};
//		document.onmousemove = function () {
//			myHint.x = event.clientX;
//			myHint.y = event.clientY;
//			if (myHint.b_follow && myHint.visible!=null) myHint.move(0);
//			return true;
//		};
//	}
//	else {
//		this.getSize = function (id, b_hight) { 
//			return this.divs[id].o_obj[b_hight ? 'offsetHeight' : 'offsetWidth'] 
//		};
//		if(!document.all){
//		window.onmousedown = function (evt) {
//      whichIt = evt.target;
//  		while (whichIt != myHint.divs[0].o_obj) {
//	  		whichIt = whichIt.parentNode;
//		  	if (whichIt == null){
//    		  myHint.hide(0);
//		  	  return true;
//		  	}
//		  }
//		  return true;
//		};
//		}
//		document.onmousemove = this.b_IE
//		? function () {
//			myHint.x = event.clientX + document.body.scrollLeft;
//			myHint.y = event.clientY + document.body.scrollTop;
//			if (myHint.b_follow && myHint.visible!=null) myHint.move(0)
//			return true;
//		} 
//		: function (e) {
//			myHint.x = e.pageX;
//			myHint.y = e.pageY;
//			if (myHint.b_follow && myHint.visible!=null) myHint.move(0)
//			return true;
//		};
//	}
	document.write (s_tag.replace(/%text%/g, '').replace(/%name%/g, 0));
	this.divs[0] = { 'o_obj' : this.getElem('TTip' + 0), 'o_content' : this.getElem('ToolTip' + 0) };
	this.divs[0].o_css = this.divs[0].o_obj.style;
/*	for (i in items) {
		document.write (s_tag.replace(/%text%/, items[i]).replace(/%name%/, i));
		this.divs[i] = { 'o_obj' : this.getElem('TTip' + i) };
		this.divs[i].o_css = this.divs[i].o_obj.style;
	}*/
}

function TTipShow (id) {
	if (document.layers) return;
  if (this.timer2) clearTimeout(this.timer2);
  if (id==null){
//    if (this.divs[0].timer) clearTimeout(this.divs[0].timer);
    return;
  }
	if (this.visible!=null && this.visible==id){
	  return;
	}
 	this.hide(0);
	if (this.divs[0] && this.items[id]) {
		if (this.n_dl_show) this.divs[0].timer = setTimeout("myHint.showD(" + id + ")", this.n_dl_show);
		else this.showD(id);
	}
}

function TTipShowD (id) {
  this.divs[0].o_content.innerHTML = this.items[id];
  init_btchina(this.divs[0].o_content);
	if((this.move(0)&2)!=0 && this.b_IE){
  	var mySels = document.getElementsByTagName('select');
    for (var i = 0; i < mySels.length; i++){
  	  var e = mySels[i];
  	  var t=e.offsetTop;  
  	  var l=e.offsetLeft;  
  while(e=e.offsetParent){  
    t+=e.offsetTop;  
    l+=e.offsetLeft; 
    }
    var obj = this.divs[0].o_obj;
  	  if(obj.offsetTop<t+mySels[i].offsetHeight 
  	  && obj.offsetTop + obj.offsetHeight>=t
  	  && obj.offsetLeft<l+mySels[i].offsetWidth
  	  && obj.offsetLeft + obj.offsetWidth>=l){
  	    mySels[i].style.display='none';
  	  }
  	}
	}
	this.showElem(0);
	if (this.n_dl_hide) this.timer = setTimeout("myHint.hide(0)", this.n_dl_hide);
	this.visible = id;
}

function TTipMove (id, onlyx) {
	var n_x = this.x + this.left, n_y = this.y + this.top;
	if(window.opera){
		n_x += this.getWinSc();
		n_y += this.getWinSc(true);
	}
	var ret = 0;
	if (this.b_wise!=0) {
		var n_w = this.getSize(id), n_h = this.getSize(id, true),
		n_win_w = this.getWinSz(), n_win_h = this.getWinSz(true),
		n_win_l = this.getWinSc(), n_win_t = this.getWinSc(true);
		if (n_x + n_w > n_win_w + n_win_l) n_x = n_win_w + n_win_l - n_w;
		if (n_x < n_win_l) n_x = n_win_l;
		if (n_x != this.x + this.left) ret++;
		if (this.b_wise==2 || n_x > this.x){
  		if (n_y + n_h > n_win_h + n_win_t) n_y = n_win_h + n_win_t - n_h;
	  	if (n_y < n_win_t) n_y = n_win_t;
	  	if (n_y != this.y + this.top) ret+=2;
	  }
	}
	if(null==onlyx || !onlyx){
	  this.divs[id].o_css.left = n_x;
		this.divs[id].o_css.top = n_y;
	}
	else if(n_x<parseInt(this.divs[id].o_css.left)){
	  this.divs[id].o_css.left = n_x;
	}
  else{
	  ret &= ~2;
	}
	return ret;
}

function TTipHide (delay) {
	if (this.timer) clearTimeout(this.timer);
  if (this.divs[0].timer) clearTimeout(this.divs[0].timer);
	if (this.visible != null) {
		if(delay==null){
		  delay = this.out_delay;
		}
		if(delay){
			this.timer2 = setTimeout("myHint.hideD()", delay);
		}
		else{
		  this.hideD();
		}
	}
}

function TTipHideD () {
  myHint.showElem(0, true);
	this.visible = null;
	if(this.b_IE){
	  var mySels = document.getElementsByTagName('select');
    for (var i = 0; i < mySels.length; i++)
      mySels[i].style.display='';
 	}
}

var btchina_text_color = '#000000';

var btchina_link_colors = new Array('#ffff66','#a0ffff','#99ff99','#ff9999','#ff66ff');

function init_btchina(container){
	if(btchina_query!=null && btchina_query!=''){
		go_btchina(btchina_query,container);
		return;
	}
/*
	var url_parts = document.location.href.split('?');
	if (url_parts[1]){ 
		var url_args = url_parts[1].split('&');
		for(var i=0; i<url_args.length; i++){
			var keyval = url_args[i].split('=');
			if (keyval[0] == 'query'){
				var query = decode_url(keyval[1]);
				if(query!=''){
					go_btchina(decode_url(keyval[1]),container);
					return;
				}
			}
		}
	}
*/
}

function decode_url(url){
	return unescape(url.replace(/\+/g,' '));
}

function go_btchina(terms,container){
	terms = terms.replace(/([*\"]| and | or | not )/g," ");
	var terms_split = terms.split(/^-[ -]*| [ -]*/);
	var c = 0;
	for(var i=0; i<terms_split.length; i++){
		highlight_btchina(terms_split[i], container==null?document.body:container,btchina_link_colors[c]);
		c = (c == btchina_link_colors.length-1)?0:c+1;
	}
}

function highlight_btchina(term, container, color){
	if(term.length==0){
		return;
	}
	var term_low = term.toLowerCase();

	for(var i=0; i<container.childNodes.length; i++){
		var node = container.childNodes[i];

		if (node.nodeType == 3){
			var data = node.data;
			var data_low = data.toLowerCase();
			if (data_low.indexOf(term_low) != -1){
				//term found!
				var new_node = document.createElement('SPAN');
				node.parentNode.replaceChild(new_node,node);
				var result;
				while((result = data_low.indexOf(term_low)) != -1){
					new_node.appendChild(document.createTextNode(data.substr(0,result)));
					new_node.appendChild(create_node_btchina(document.createTextNode(data.substr(result,term.length)),color));
					data = data.substr(result + term.length);
					data_low = data_low.substr(result + term.length);
				}
				new_node.appendChild(document.createTextNode(data));
			}
		}else{
			//recurse
			highlight_btchina(term, node, color);
		}
	}
}

function create_node_btchina(child, color){
	var node = document.createElement('SPAN');
	node.style.backgroundColor = color;
	node.style.color = btchina_text_color;
	node.appendChild(child);
	return node;
}