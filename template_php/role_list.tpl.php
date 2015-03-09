
<!DOCTYPE HTML PUBLIC "-//W3C//Dtd HTML 4.01 Transitional//EN"
"http://www.w3.org/tr/html4/loose.dtd">
<html>
<head> 
<META http-equiv="imagetoolbar" content="no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>FMOL Proto v0.1</title>
<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css"> 


<script language="javascript" src="/fmol/script/drag_layer_role_list.js"></script>
<script language="javascript" src="/fmol/script/lay_role_list.js"></script>
<script language="javascript" src="/fmol/script/role_list_change.js"></script>
<script language="javascript" src="/fmol/script/tactics_functions.js"></script>

</head>

<body style="overflow:auto;">


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr><td height="1">{SPACE}</td></tr>
  
  <form name="save_form" action="/fmol/page/tactics/handle_role_list.php?myaction=saveRoleList"  onSubmit="return beforeSubmit_indexs(this)" target="_parent" method="post" >
  <input type="hidden" name="all_role_types" value="{ALL_ROLE_TYPES}" />
  
	
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            
			<td width="{PLAYER_LIST_WIDTH}" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#CAD6E8">
          
              <tr>
                <td  class="gSGSectionColumnHeadings" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="2" >&nbsp;</td>
                      <td width="80">Pkd</td>
                      <td>Name</td>
                    </tr>
                </table></td>
              </tr>
			  
			  <div id="info_layer" onmouseover="over_info_layer('info_layer')" onmouseout="out_info_layer('info_layer')" style="Z-INDEX: 10; LEFT: 50px; VISIBILITY: hidden; WIDTH: 300px; POSITION: absolute; TOP: 100px; HEIGHT: 350px; visible: false; "> 
			  {IFRAME_CODE}
			  </div>
			  
              <!-- BEGIN player_list -->
			  <tr class="{TRAINING_ITEM_TR_CLASS}"><td>
			  <DIV id="np{PLAYER_ID}" style="z-index:4; left: {LEFT_COORDINATE}px; TOP: {TOP_COORDINATE}px; POSITION: absolute; VISIBILITY: hidden; visible: false"><IMG src="/fmol/images/{IMG_NAME}"></DIV>
			  <DIV id="p{PLAYER_ID}" style="z-index:5; left: {LEFT_COORDINATE}px; WIDTH: {WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute; VISIBILITY: hidden; visible: false"><IMG src="/fmol/images/{IMG_NAME}"></DIV>
			  <DIV id="index_{PLAYER_ID}" onMouseOver="show_info_layer('info_layer', '{PLAYER_ID}', false)" onMouseOut="hide_info_layer('info_layer')"  style="z-index:6; left: {INDEX_LEFT_COORDINATE}px; WIDTH: {INDEX_WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute;">{INDEX}</DIV>
			  <DIV id="player_name_{PLAYER_ID}" onMouseOver="show_info_layer('info_layer', '{PLAYER_ID}', false)" onMouseOut="hide_info_layer('info_layer')"  style="z-index:7; left: {PLAYER_NAME_LEFT_COORDINATE}px; WIDTH: {PLAYER_NAME_WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute;">{PLAYER_NAME}</DIV>
			  </td></tr>
              <!-- END player_list -->
			  
            </table></td>
			
			<td width="1" class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
            <td width="10" valign="top" bgcolor="#cccccc">&nbsp;</td>
            <td width="1" class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
			
			<td width="*" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Role List</div></td>
              </tr>
              <tr>
                <td valign="top" class="gSGRowOdd_input">
				<table id="role_list_table" width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
				    <td class="gSGSectionColumnHeadings">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="80" align="right" nowrap> role type: </td>
						<td width="150">   <select style="width:125px " id="role_type_select" onChange="role_type_select_change(this)" >
							  <!-- BEGIN role_type_select -->
							  <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
							  <!-- END role_type_select -->
							</select>
						</td>
						<td>&nbsp;						</td>
						
					  </tr>
					</table>
					</td>
                  </tr>
				  <tr class="gSGRowOdd">
                    <td>
					  <!-- BEGIN role_list_table -->
                      <table id="table_{ROLE_TYPE}" style="display: {ROLE_TYPE_TABLE_DISPLAY};" width="100%"  border="1" cellspacing="0" cellpadding="0">
					    <tr id="blank_tr_{ROLE_TYPE}" style="display:{BLANK_TR_DISPLAY} "><td height="25" colspan="5"></td></tr>
						
                        <!-- BEGIN role_list_tr -->
						  <tr id="{ROW_ID}">
						    <td width="25" align="center">
							  <input type="checkbox" value="{ROW_ID}" />
							  <input type="hidden" name="player_ids_{ROLE_TYPE}[]" value="{PLAYER_ID}" />
						    </td>  
						    <td width="65">&nbsp;<select style="width:55px " id="{INDEX_SELECT_ID}" name="index_select_{ROLE_TYPE}[]"  onChange="index_select_change('{ROLE_TYPE}', this.form, this)" >
							  <!-- BEGIN index_select -->
							  <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
							  <!-- END index_select -->
							</select>
						    </td>
							<td>&nbsp;{PLAYER_NAME}</td>
							<td width="28"><input type="button" name="delete_button_{ROLE_TYPE}" value="del" onClick="delete_selected_role('{ROLE_TYPE}', '{ROW_ID}')" />  </td>
							
						  </tr>
						<!-- END role_list_tr -->
						
                      </table>
					  
					  <!-- END role_list_table -->
					  
					</td>
                  </tr>
				  <tr class="gSGRowOdd">
                    <td>&nbsp;                      </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
			
            
          </tr>
          <tr>
           <td valign="top">&nbsp;</td>
			
            <td class="cBBottom"></td>
            <td bgcolor="#cccccc">&nbsp;</td>
            <td class="cBBottom"></td>
			
            
			
			 <td align="center" valign="top" class="gSGRowOdd_input">
              <input type="button" class="button" style="width:90px " value="del selected" onClick="delete_selected_rows(this.form)" />
&nbsp;
              <input type="button" class="button" style="width:90px " name="clear_role_list" value="clear" onClick="delete_all_rows(this.form)" />
&nbsp;
<input type="submit" class="button" style="width:90px " name="save_role_list" value="save" />
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  
  </form>
  
</table>






	
<script language="javascript" type="text/javascript">
{SCRIPT_CODE}
</script>

</body>

</html>