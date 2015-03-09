
<script type="text/javascript" src="/fmol/script/xmlextras.js"></script>

<script language="javascript" type="text/javascript">

{SCRIPT_CODE}

</script>

<script language="javascript" type="text/javascript">
var display_minute = last_minute_value;

function formPost()
{   
  if (cur_minute_index >= minutesArr.length) {
  	clearInterval(ctroltime);
	
	document.getElementById("show_time").innerHTML += " Over.";
	
	return;
  }
  
  
  
  if (display_minute < minutesArr[cur_minute_index]) {
  	// display the last time
	++display_minute;     
	document.getElementById("show_time").innerHTML = display_minute + " min.";
  	return;
  }
  else if (display_minute > minutesArr[cur_minute_index]) {
  	cur_minute_index ++ ;
	return;
  }

  var postStr = "comment_id=" + comment_id;
  postStr += "&cur_minute_index=" + cur_minute_index;
  
  // use the xmlhttp to visit the server, and get back the coresspond comment
  var xmlHttp = XmlHttp.create();
  var async = false;  // tong bu
  xmlHttp.open("POST", "./match_report_machine.php?"+postStr, async);
  xmlHttp.send(null);
 
  var xmlResponseDoc = XmlDocument.create();

  xmlResponseDoc.loadXML(xmlHttp.responseText);     

  var data_nodes = xmlResponseDoc.getElementsByTagName("data");  
  var comment_nodes = data_nodes[0].getElementsByTagName("comment");
  var comment = comment_nodes[0].firstChild.nodeValue;
  //comment = comment.replace(/\n/g,'<br>');  
  
  // display the comment
  displayTheComment(comment);
  
  // change the cur_minute_index
  ++cur_minute_index;
}

function displayTheComment(comment) 
{
	var seperater_middle = "**";
	var seperater_small = "^^";
  	var minute_and_comment_arr = comment.split(seperater_middle); 
	var cur_minute = minute_and_comment_arr[0];
	var one_comment_str = minute_and_comment_arr[1];
	
	one_comment_str = one_comment_str.substr(0, (one_comment_str.length - seperater_small.length));
	var one_comment_arr = one_comment_str.split(seperater_small); 
	var one_comment_arr_len = one_comment_arr.length;
  
  	var tableHTML = '<table width="96%"  border="0" cellspacing="0" cellpadding="0">';
	tableHTML += '<tr><td class="cBBottom2" colspan="5"><img height="1" src="/fmol/images/blank.gif"></td></tr>';
	tableHTML += '<tr class="gSGRowOdd"><td align="center" width="100" class="gSGRowOrange">&nbsp;' 
				+ cur_minute + 
				'</td><td >&nbsp;</td><td align="center" width="100">&nbsp;</td></tr>';
					
  	for (var i=0; i<one_comment_arr_len; ++i) {
		var comment_content = one_comment_arr[i];
		
		tableHTML += '<tr><td class="cBBottom2" colspan="5"><img height="1" src="/fmol/images/blank.gif"></td></tr>';
		tableHTML += '<tr><td align="center" width="100"></td><td >' 
					+ comment_content + 
					'</td><td align="center" width="100"></td></tr>';
  	}
	
  	tableHTML += "</table>";
  
  	document.getElementById("report").innerHTML += tableHTML;
  
  	// report 
  	document.getElementById("report").scrollTop = document.getElementById("report").scrollHeight;
}

var ctroltime = setInterval("formPost()", 1000);

</script>


<table width="739"  border="0" cellspacing="0" cellpadding="0">
  <tr><td height="1">{SPACE}</td></tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20" class="gSGRowEven"><span id="show_time">0 Min.</span>&nbsp;</td>
          </tr>
          <tr>
            <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Report</div></td>
                </tr>
                <tr>
                  <td class="gSGRowOdd"><div id="report" style="OVERFLOW:auto; height:350px; width:100%; text-align:left;"></div></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

