
<script language="javascript" type="text/javascript">
function wrapRows(tableId, preRowId, spanObj)
{
	// 改变标志，提示用户折叠或展开
	var wrap_or_not = "";
	if (spanObj.innerHTML.toLowerCase() == "wrap") {
		spanObj.innerHTML = "unwrap";
		wrap_or_not = "none";
	}
	else {
		spanObj.innerHTML = "wrap";
		wrap_or_not = "";
	}
	
	// 将id为tableId的table中的行隐藏或显示
	// 如果行的id以preRowId开头的话，就根据变量wrap_or_not的值来隐藏或显示
	var tableObj = document.getElementById(tableId);
	var len = tableObj.rows.length;
	var rows = tableObj.rows;
	var rowId = "";
	for (var i=0; i<len; ++i) {
		rowId = rows[i].id; 
		if (rowId.substring(0, preRowId.length) == preRowId) {
			rows[i].style.display = wrap_or_not;
		}
	}
}

</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" >
    <tr><td height="1">{SPACE}</td></tr>
	
	
	<tr>
	 <td>
	   <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Finance Situation </div></td>
            </tr>
            <tr>
              <td bgcolor="#FFFFFF">
			  <table width="100%"  border="0" cellspacing="2" cellpadding="0">
			  <tr>
				<td>
				<div  style='width:97.8%;border: 1px dashed #CCCCCC;font-family: Verdana, Arial, Helvetica, sans-serif;padding: 5px;'>
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
				  <tr class="gSGRowOdd">
					<td>season begin fund</td>
					<td>{SEASON_BEGIN_FUND}</td>
					<td rowspan="3" align="center"><input type="button" class="button" name="" value="request to add fund" /></td>
				  </tr>
				  <tr>
				    <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  <tr class="gSGRowOdd">
					<td>current fund </td>
					<td>{CURRENT_FUND}</td>
				  </tr>
				</table>

				</div>
				</td>
			  </tr>
			</table>

			    
		      </td>
            </tr>
          </table></td>
        </tr>
      </table>
	  
	 </td>
	</tr>
	
	<tr><td height="2"></td></tr>
	
	<tr>
	  <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Business Situation </div></td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellpadding="0" cellspacing="0" id="finance_table">
                  <tr>
                    <td class="gSGSectionColumnHeadings" align="left">&nbsp;item</td>
                    <td class="gSGSectionColumnHeadings" align="right">current season </td>
                    <td class="gSGSectionColumnHeadings" align="right">last season </td>
                    <td class="gSGSectionColumnHeadings" align="right">last last season &nbsp;</td>
                  </tr>
				  
				  <!-- income -->
                  <tr class="gSGRowEven">
                    <td align="left">&nbsp;income</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right" ><span onClick="wrapRows('finance_table', 'income', this)" style="cursor:hand;">wrap</span></td>
                  </tr>
				  
				  <!-- BEGIN income -->
                  <tr class="gSGRowOdd" id="{INCOME_TR_ID}">
                    <td align="left">&nbsp;{INCOME_ITEM_NAME}</td>
                    <td align="right">{CURRENT_INCOME_ITEM_VALUE}</td>
                    <td align="right">{LAST_INCOME_ITEM_VALUE}</td>
                    <td align="right">{LAST_LAST_INCOME_ITEM_VALUE}&nbsp;</td>
                  </tr>
				  <tr  id="{INCOME_SEPARATOR_ID}">
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  <!-- END income -->
				  
				  <tr>
				    <td colspan="4" class="gSGRowOdd"></td>
				  </tr>
				  
				  <!-- expenditure -->
                  <tr class="gSGRowEven">
                    <td align="left">&nbsp;expenditure</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right"><span onClick="wrapRows('finance_table', 'expenditure', this)" style="cursor:hand;">wrap</span></td>
                  </tr>
				  
				  <!-- BEGIN expenditure -->
                  <tr class="gSGRowOdd" id="{EXPENDITURE_TR_ID}">
                    <td align="left">&nbsp;{EXPENDITURE_ITEM_NAME}</td>
                    <td align="right">{CURRENT_EXPENDITURE_ITEM_VALUE}</td>
                    <td align="right">{LAST_EXPENDITURE_ITEM_VALUE}</td>
                    <td align="right">{LAST_LAST_EXPENDITURE_ITEM_VALUE}&nbsp;</td>
                  </tr>
				  <tr id="{EXPENDITURE_SEPARATOR_ID}">
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  <!-- END expenditure -->
				  
				  <tr>
				    <td colspan="4" class="gSGRowOdd"></td>
				  </tr>
				  
				  <!-- assets -->
                  <tr class="gSGRowEven">
                    <td align="left">&nbsp;assets</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right"><span onClick="wrapRows('finance_table', 'assets', this)" style="cursor:hand;">wrap</span></td>
                  </tr>
				  
				  <!-- BEGIN assets -->
                  <tr class="gSGRowOdd" id="{ASSETS_TR_ID}">
                    <td align="left">&nbsp;{ASSETS_ITEM_NAME}</td>
                    <td align="right">{CURRENT_ASSETS_ITEM_VALUE}</td>
                    <td align="right">{LAST_ASSETS_ITEM_VALUE}</td>
                    <td align="right">{LAST_LAST_ASSETS_ITEM_VALUE}&nbsp;</td>
                  </tr>
				  <tr id="{ASSETS_SEPARATOR_ID}">
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  <!-- END assets -->
				  
				  <tr>
				    <td colspan="4" class="gSGRowOdd"></td>
				  </tr>
				  
				  <!-- balance -->
                  <tr class="gSGRowEven">
                    <td align="left">&nbsp;balance</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right"><span onClick="wrapRows('finance_table', 'balance', this)" style="cursor:hand;">wrap</span></td>
                  </tr>
				  
				  <!-- BEGIN balance -->
                  <tr class="gSGRowOdd" id="{BALANCE_TR_ID}">
                    <td align="left">&nbsp;{BALANCE_ITEM_NAME}</td>
                    <td align="right">{CURRENT_BALANCE_ITEM_VALUE}</td>
                    <td align="right">{LAST_BALANCE_ITEM_VALUE}</td>
                    <td align="right">{LAST_LAST_BALANCE_ITEM_VALUE}&nbsp;</td>
                  </tr>
				  <tr id="{BALANCE_SEPARATOR_ID}">
				    <td colspan="4" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  <!-- END balance -->
				  
				  
				  <tr>
				    <td colspan="4" class="gSGRowOdd"></td>
				  </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
	</tr>
</table>

