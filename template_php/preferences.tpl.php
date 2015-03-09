<SCRIPT language=JavaScript> 
var SelRGB = ''; 
var DrRGB = ''; 
var SelGRAY = '120'; 
var SelRGB2 = ''; 
var DrRGB2 = ''; 
var SelGRAY2 = '120'; 

var hexch = new Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'); 
var cnum = new Array(1, 0, 0, 1, 1, 0, 0, 1, 0, 0, 1, 1, 0, 0, 1, 1, 0, 1, 1, 0, 0); 



// 
function ToHex(n) 
{ 
	var h, l; 
	
	n = Math.round(n); 
	l = n % 16; 
	h = Math.floor((n / 16)) % 16; 
	return (hexch[h] + hexch[l]); 
} 

// 
function DoColor(c, l) 
{ 
	var r, g, b; 
	
	r = '0x' + c.substring(1, 3); 
	g = '0x' + c.substring(3, 5); 
	b = '0x' + c.substring(5, 7); 
	
	if(l > 120) 
	{ 
		l = l - 120; 
		
		r = (r * (120 - l) + 255 * l) / 120; 
		g = (g * (120 - l) + 255 * l) / 120; 
		b = (b * (120 - l) + 255 * l) / 120; 
	}
	else 
	{ 
		r = (r * l) / 120; 
		g = (g * l) / 120; 
		b = (b * l) / 120; 
	} 

	return '#' + ToHex(r) + ToHex(g) + ToHex(b); 
} 

// ----------------------------------------
//
function draw_color_table()
{
	for(i = 0; i < 16; i ++) 
	{ 
		document.write('<TR>'); 
		for(j = 0; j < 30; j ++) 
		{ 
			n1 = j % 5; 
			n2 = Math.floor(j / 5) * 3; 
			n3 = n2 + 3; 
			
			wc((cnum[n3] * n1 + cnum[n2] * (5 - n1)), 
				(cnum[n3 + 1] * n1 + cnum[n2 + 1] * (5 - n1)), 
				(cnum[n3 + 2] * n1 + cnum[n2 + 2] * (5 - n1)), i); 
		} 
		
		document.writeln('</TR>'); 
	} 	
}

//
function draw_gray_table()
{
	for(i = 255; i >= 0; i -= 8.5) 
		document.write('<TR BGCOLOR=#' + ToHex(i) + ToHex(i) + ToHex(i) + '><TD onClick="click_gray_table_td(this)" TITLE=' + 
						Math.floor(i * 16 / 17) + ' height=4 width=20></TD></TR>'); 	
}

// 
function wc(r, g, b, n) 
{ 
	r = ((r * 16 + r) * 3 * (15 - n) + 0x80 * n) / 15; 
	g = ((g * 16 + g) * 3 * (15 - n) + 0x80 * n) / 15; 
	b = ((b * 16 + b) * 3 * (15 - n) + 0x80 * n) / 15; 
	
	document.write('<TD BGCOLOR=#' + ToHex(r) + ToHex(g) + ToHex(b) + ' onClick="click_color_table_td(this)" height=8 width=8></TD>'); 
} 

// 
function EndColor() 
{ 
	var i; 
	
	if(DrRGB != SelRGB) 
	{ 
		DrRGB = SelRGB; 
		for(i = 0; i <= 30; i ++) 
			document.getElementById("GrayTable").rows[i].bgColor = DoColor(SelRGB, 240 - i * 8); 
	} 
	   
	document.forms["preferences_form"].elements["bg_color"].value = DoColor(document.getElementById("RGB").innerText, document.getElementById("GRAY").innerText); 
	document.getElementById("ShowColor").bgColor = document.forms["preferences_form"].elements["bg_color"].value; 
	
	
	document.getElementById("example_tr").bgColor = document.forms["preferences_form"].elements["bg_color"].value;  
} 

// 
function click_color_table_td(td_obj)
{ 
	// SelRGB = event.srcElement.bgColor; 
	SelRGB = td_obj.bgColor;   
	// add 
	document.getElementById("RGB").innerText = SelRGB; 
	document.getElementById("GRAY").innerText = SelGRAY; 
	EndColor(); 
}

// 
function click_gray_table_td(td_obj)
{  
	// SelGRAY = event.srcElement.title; 
	SelGRAY = td_obj.title;  
	// add
	document.getElementById("RGB").innerText = SelRGB; 
	document.getElementById("GRAY").innerText = SelGRAY; 
	EndColor(); 
}

// ----------------------------------------
//
function draw_color_table2()
{
	for(i = 0; i < 16; i ++) 
	{ 
		document.write('<TR>'); 
		for(j = 0; j < 30; j ++) 
		{ 
			n1 = j % 5; 
			n2 = Math.floor(j / 5) * 3; 
			n3 = n2 + 3; 
			
			wc2((cnum[n3] * n1 + cnum[n2] * (5 - n1)), 
				(cnum[n3 + 1] * n1 + cnum[n2 + 1] * (5 - n1)), 
				(cnum[n3 + 2] * n1 + cnum[n2 + 2] * (5 - n1)), i); 
		} 
		
		document.writeln('</TR>'); 
	} 	
}

//
function draw_gray_table2()
{
	for(i = 255; i >= 0; i -= 8.5) 
		document.write('<TR BGCOLOR=#' + ToHex(i) + ToHex(i) + ToHex(i) + '><TD onClick="click_gray_table_td2(this)" TITLE=' + 
						Math.floor(i * 16 / 17) + ' height=4 width=20></TD></TR>'); 	
}

// 
function wc2(r, g, b, n) 
{ 
	r = ((r * 16 + r) * 3 * (15 - n) + 0x80 * n) / 15; 
	g = ((g * 16 + g) * 3 * (15 - n) + 0x80 * n) / 15; 
	b = ((b * 16 + b) * 3 * (15 - n) + 0x80 * n) / 15; 
	
	document.write('<TD BGCOLOR=#' + ToHex(r) + ToHex(g) + ToHex(b) + ' onClick="click_color_table_td2(this)" height=8 width=8></TD>'); 
} 

// 
function EndColor2() 
{ 
	var i; 
	
	if(DrRGB2 != SelRGB2) 
	{ 
		DrRGB2 = SelRGB2; 
		for(i = 0; i <= 30; i ++) 
			document.getElementById("GrayTable2").rows[i].bgColor = DoColor(SelRGB2, 240 - i * 8); 
	} 
	   
	document.forms["preferences_form"].elements["font_color"].value = DoColor(document.getElementById("RGB2").innerText, document.getElementById("GRAY2").innerText); 
	document.getElementById("ShowColor2").bgColor = document.forms["preferences_form"].elements["font_color"].value; 
	
	document.getElementById("example_tr").style.color = document.forms["preferences_form"].elements["font_color"].value;
} 
 
// 
function click_color_table_td2(td_obj)
{  
	SelRGB2 = td_obj.bgColor;  
	// add 
	document.getElementById("RGB2").innerText = SelRGB2; 
	document.getElementById("GRAY2").innerText = SelGRAY2; 
	EndColor2(); 
}

// 
function click_gray_table_td2(td_obj)
{  
	SelGRAY2 = td_obj.title;
	// add
	document.getElementById("RGB2").innerText = SelRGB2; 
	document.getElementById("GRAY2").innerText = SelGRAY2; 
	EndColor2(); 
}

// 
function change_bg_color(form_obj)
{
	var color_value = form_obj.elements["bg_color"].value;
	if (color_value.indexOf("#") == -1) {
		color_value = "#" + color_value;	
	}
	
 	form_obj.elements["bg_color"].value = color_value;
	document.getElementById("ShowColor").bgColor = color_value;
	
}

// 
function change_font_color(form_obj)
{ 
	var color_value = form_obj.elements["font_color"].value;
	if (color_value.indexOf("#") == -1) {
		color_value = "#" + color_value;	
	}
	
 	form_obj.elements["font_color"].value = color_value;
	document.getElementById("ShowColor2").bgColor = color_value;
	
}

</SCRIPT>  


<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr><td height="1">{SPACE}</td></tr>
  <tr><td>
  	<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td colspan="3" class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Theme Color </div></td>
	</tr>
	<tr>
	  
	  <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
		
		
        <form method="post" name="preferences_form" action="/fmol/page/preferences/handle_preferences.php?myaction=savePreferences" >
          <tr class="gSGRowEven_input">
            <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="80" align="right">bg color&nbsp;</td>
                  <td width="160"><input type="text" value="{BG_COLOR_VALUE}" name="bg_color" class="inputField" style="width:100px " />
                      <input type="button" class="button" value="show" onclick="change_bg_color(this.form)" />
                      <SPAN id="RGB" style="display:none "></SPAN> <SPAN id="GRAY" style="display:none ">120</SPAN> </td>
                  <td width=40 align="center"><TABLE border=1 cellPadding=0 cellSpacing=0 height=18 id="ShowColor" width=35>
                      <TR>
                        <TD></TD>
                      </TR>
                  </TABLE></td>
				  <td width="*">&nbsp;</td>
                </tr>
            </table></td>
            <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="80" align="right">font color&nbsp;</td>
                  <td width="160"><input type="text" value="{FONT_COLOR_VALUE}" name="font_color" class="inputField" style="width:100px " />
                      <input type="button" class="button" value="show" onclick="change_font_color(this.form)" />
                      <SPAN id="RGB2" style="display:none "></SPAN> <SPAN id="GRAY2" style="display:none ">120</SPAN> </td>
                  <td width=40 align="center"><TABLE border=1 cellPadding=0 cellSpacing=0 height=18 id="ShowColor2" width=35>
                      <TR>
                        <TD></TD>
                      </TR>
                  </TABLE></td>
				  
				  <td width="*">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr class="gSGRowEven">
            <td><TABLE border=0 cellPadding=0 cellSpacing=10>
                      <TR>
                        <TD><TABLE border=0 cellPadding=0 cellSpacing=0 id="ColorTable"  style="CURSOR: hand">
                            <SCRIPT language=JavaScript> draw_color_table(); </SCRIPT>
                        </TABLE></TD>
                        <TD><TABLE border=0 cellPadding=0 cellSpacing=0 id="GrayTable" style="CURSOR: hand">
                            <SCRIPT language=JavaScript> draw_gray_table(); </SCRIPT>
                        </TABLE></TD>
                      </TR>
                  </TABLE></td>
            <td><TABLE border=0 cellPadding=0 cellSpacing=10>
                      <TR>
                        <TD><TABLE border=0 cellPadding=0 cellSpacing=0 id="ColorTable2" style="CURSOR: hand">
                            <SCRIPT language=JavaScript> draw_color_table2(); </SCRIPT>
                        </TABLE></TD>
                        <TD><TABLE border=0 cellPadding=0 cellSpacing=0 id="GrayTable2" style="CURSOR: hand">
                            <SCRIPT language=JavaScript>  draw_gray_table2(); </SCRIPT>
                        </TABLE></TD>
                      </TR>
                  </TABLE></td>
          </tr>
		  
          <tr height="30" id="example_tr" style="FONT-SIZE: 15px; MARGIN: 2px 2px 2px 0px; FONT-FAMILY: verdana, arial, Helvetica, sans-serif;FONT-WEIGHT: bold;">
		    <td colspan="3">
			  <table border=0 cellPadding=0 cellSpacing=0 width="100%">
			    <tr>
				  <td align="right" width="120">team name: </td>
				  <td>&nbsp;{CLUB_NAME} </td>
				</tr>
			  </table>
			</td>
   
          </tr>
		  
		  
          <tr align="center" class="gSGRowEven_input">
            <td colspan="3">&nbsp;
                <input type="submit" class="button" value="save" style="width:100px " /></td>
          </tr>
        </form>
	    </table></td>
	</tr>
	
  </table></td>
  </tr>
</table>

  </td></tr>
  
  <tr><td height="2">
  </td></tr>

  

</table>

	


<script type="text/javascript" language="javascript">
function init_page_preferences()
{
	var form_obj = document.forms["preferences_form"];
	
	// bg_color
 	var bg_color_value = form_obj.elements["bg_color"].value;
	document.getElementById("ShowColor").bgColor = bg_color_value;
	document.getElementById("RGB").innerText = bg_color_value;
	//document.getElementById("example_tr").bgColor = bg_color_value;
	SelRGB = bg_color_value;
	EndColor();
	
	// font_color
 	var font_color_value = form_obj.elements["font_color"].value;
	document.getElementById("ShowColor2").bgColor = font_color_value;
	document.getElementById("RGB2").innerText = font_color_value; 
	//document.getElementById("example_tr").style.color = font_color_value;  
	SelRGB2 = font_color_value;
	EndColor2();
	
}

init_page_preferences();
</script> 

