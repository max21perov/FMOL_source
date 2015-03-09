
<script type="text/javascript" language="javascript">
function display_table_cols(tableId, buttonObj)
{
	var buttonValue = (buttonObj.value).toLowerCase(); 
	var display_value = "";
	if (buttonValue == "brief") {
		buttonObj.value = "detail";
		display_value = "none";
	}
	else {
		buttonObj.value = "brief";	
		display_value = "";
	}
	
	// 将id为tableId的table中的列隐藏或显示
	var tableObj = document.getElementById(tableId);  
	var rowObj = tableObj.rows[0]; 
	var len = rowObj.cells.length;
	var cell_arr = [];
	// 查询第一行中的列，把列id为display_td_head的列记录下来，放在数组cell_arr中
	for (var i=0; i<len; ++i) {
		if (rowObj.cells[i].id == "display_td_head") { 
			cell_arr[cell_arr.length] = i;
		}
	}
	// 遍历tableObj，将需要处理的列隐藏或显示
	len = tableObj.rows.length;
	for (var r=0; r<len; ++r) {
		// 只对id为"display_row"的行处理
		if (tableObj.rows[r].id == "display_row") {
			for (var c=0; c<cell_arr.length; ++c) {
				var cell_index = cell_arr[c];  
				tableObj.rows[r].cells[cell_index].style.display = display_value;
			}
		}
	}
	
}

// 显示所有的match，或者显示最近5场match
function change_match_num(buttonObj) {
	var buttonValue = (buttonObj.value).toLowerCase(); 
	var match_num = "";
	if (buttonValue == "all") {
		match_num = "all";
		document.location.href = "/fmol/page/fans/fans.php?match_num=" + match_num;
	}
	else {
		match_num = "5";
		document.location.href = "/fmol/page/fans/fans.php";
	}
	
	
}
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" >
    <tr><td height="1">{SPACE}</td></tr>
	
	
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Route Choices </div></td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="gSGSectionColumnHeadings" title="scale" >&nbsp;scale</td>
                    <td class="gSGSectionColumnHeadings" title="scale flag" >flag </td>
                    <td class="gSGSectionColumnHeadings" title="type" >type </td>
                    <td class="gSGSectionColumnHeadings" title="route choice" >route </td>
                    <td class="gSGSectionColumnHeadings" title="finish time" >finish </td>
                    <td align="right" class="gSGSectionColumnHeadings" title="cost" >cost</td>
                    <td align="right" class="gSGSectionColumnHeadings" title="capacity" >capacity</td>
                    <td align="right" class="gSGSectionColumnHeadings" title="next season fund impact" >impact</td>
                    <td align="center" class="gSGSectionColumnHeadings" title="note" >note &nbsp;</td>
                  </tr>
				  
				  
				  <!-- BEGIN alter_stadium -->
				  <tr>
				    <td colspan="9" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
                  <tr class="gSGRowOdd">
                    <td >&nbsp;{SCALE}</td>
                    <td class="{SCALE_FLAG_CLASS}">{SCALE_FLAG}</td>
                    <td >{ALTER_TYPE}</td>
                    <td >{ROUTE_CHOICE}</td>
                    <td >{FINISH_TIME}</td>
                    <td align="right" >{ALTER_COST}</td>
                    <td align="right" >{CAPACITY}</td>
                    <td align="right" >{NEXT_SEASON_FUND_IMPACT}</td>
                    <td align="center">{NOTE}&nbsp;</td>
                  </tr>
				  <!-- END alter_stadium -->
				  
				  
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
	</tr>
	
	<tr><td height="2"></td></tr>
	
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Current Scale Flag </div></td>
            </tr>
            <tr>
              <td class="gSGRowOdd">&nbsp;
			  {CURRENT_SCALE_FLAG}
			  </td>
            </tr>
          </table></td>
        </tr>
      </table></td>
	</tr>
		
</table>

