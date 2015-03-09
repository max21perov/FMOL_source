
<!DOCTYPE HTML PUBLIC "-//W3C//Dtd HTML 4.01 Transitional//EN"
"http://www.w3.org/tr/html4/loose.dtd">
<html>
<head> 
<META http-equiv="imagetoolbar" content="no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>FMOL Proto v0.1</title>
<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css"> 
<style>
.hintsClass {
	FONT-FAMILY: Verdana, Arial, Helvetica; TEXT-ALIGN: center
}
.tool_tip_row {
	BACKGROUND: #ffffdd
}
.tool_tip_bg {
	BACKGROUND: black
}
</STYLE>

<script language="javascript" src="/fmol/script/drag_layer_training.js"></script>
<script language="javascript" src="/fmol/script/lay_training.js"></script>
<script language="javascript" src="/fmol/script/training_change.js"></script>

</head>

<body style="overflow:auto;">


<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> <td height="1"> {SPACE}</td></tr>
 
  <form name="save_form" target="_parent" onSubmit="javascript:return save()" action="/fmol/page/training/handle_training.php?myaction=saveTraining" method="post">
    <input type="hidden" name="players" value="{PLAYERS_VALUE}">
    <input type="hidden" name="players_number" value="{PLAYERS_NUMBER}">
	<input type="hidden" name="players_of_item_1" value="">
	<input type="hidden" name="players_of_item_2" value="">
	<input type="hidden" name="players_of_item_3" value="">
	<input type="hidden" name="players_of_item_4" value="">
	<input type="hidden" name="players_of_item_5" value="">
	<input type="hidden" name="pk_of_team_training" value="{PK_OF_TEAM_TRAINING}">
	<input type="hidden" name="pk_of_item_1" value="{PK_OF_ITEM_1}">
	<input type="hidden" name="pk_of_item_2" value="{PK_OF_ITEM_2}">
	<input type="hidden" name="pk_of_item_3" value="{PK_OF_ITEM_3}">
	<input type="hidden" name="pk_of_item_4" value="{PK_OF_ITEM_4}">
	<input type="hidden" name="pk_of_item_5" value="{PK_OF_ITEM_5}">
  
  <tr><td>
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="2" >
       		
          <tr>
            <td width="350" valign="top" class="ProText">
			<table width="100%"  border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td>
			<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
              <tr>
                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="gSGSectionColumnHeadings">&nbsp;Team Training</td>
                    </tr>
                    <tr>
                      <td bgcolor="#FFFFFF"><table width="100%"  border="1" cellspacing="1" cellpadding="0">
                          <tr>
                            <td colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr class="gSGRowOdd">
                                  <td>section</td>
                                  <td>coach1</td>
                                  <td>coach2</td>
                                </tr>
                                <tr>
                                  <td><select name="team_content_select" onchange="javascript:content_change(this)">
                                      <!-- BEGIN content_select_team -->
                                      <option value="{CONTENT_OPTION_VALUE}" {CONTENT_SELECTED}>{CONTENT_OPTION_TEXT}</option>
                                      <!-- END content_select_team -->
                                    </select>
                                  </td>
                                  <td><select name="coach_select_first_team" onchange="javascript:coach_change(this)">
                                      <!-- BEGIN coach_select_first_team -->
                                      <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                      <!-- END coach_select_first_team -->
                                  </select></td>
                                  <td><select name="coach_select_second_team" onchange="javascript:coach_change(this)">
                                      <!-- BEGIN coach_select_second_team -->
                                      <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                      <!-- END coach_select_second_team -->
                                  </select></td>
                                </tr>
                            </table></td>
                          </tr>
                          
                      </table></td>
                    </tr>
                </table></td>
              </tr>
            </table>
			</td>
			</tr>
			
			<tr><td height="5"></td></tr>
			
			<tr>
			<td>
            <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
			  <tr><td  class="gSGSectionColumnHeadings">&nbsp;Personal Training</td></tr>
              <tr>
                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
				  <td class="gSGSectionColumnHeadings">				  
				  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><DIV id="item_1">&nbsp;&nbsp;■</DIV></td>
                      <td>&nbsp;Item (1)</td>
                      <td><div id="item_num_1" align="right" ></div></td>
                    </tr>
                  </table></td>
				  </tr>
                  <tr>
                    <td bgcolor="#FFFFFF"><table width="100%"  border="1" cellspacing="1" cellpadding="0">
                        <tr>
                          <td colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
						    <tr class="gSGRowOdd">
                              <td>section</td>
                              <td>coach1</td>
                              <td>coach2</td>
                            </tr>
                            <tr>
                              <td>
							  <select name="content_select_1" onchange="javascript:content_change(this)">
                                <!-- BEGIN content_select_1 -->
                                <option value="{CONTENT_OPTION_VALUE}" {CONTENT_SELECTED}>{CONTENT_OPTION_TEXT}</option>
                                <!-- END content_select_1 -->
                              </select>
							  </td>
                              <td>
							  <select name="coach_select_first_1" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_first_1 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_first_1 -->
                              </select></td>
                              <td>
							  <select name="coach_select_second_1" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_second_1 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_second_1 -->
                              </select></td>
                            </tr>
                          </table></td>
                          </tr>
                        <tr>
                          <td height="20">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="20">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="gSGSectionColumnHeadings">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><DIV id="item_2">&nbsp;&nbsp;■</DIV></td>
                      <td>&nbsp;(2)</td>
                      <td><div id="item_num_2" align="right" ></div></td>
                    </tr>
                  </table></td>
                  </tr>
                  <tr>
                    <td bgcolor="#FFFFFF"><table width="100%"  border="1" cellspacing="1" cellpadding="0">
                        <tr>
                          <td colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
						    <tr>
                              <td>
							  <select name="content_select_2" onchange="javascript:content_change(this)">
                                <!-- BEGIN content_select_2 -->
                                <option value="{CONTENT_OPTION_VALUE}" {CONTENT_SELECTED}>{CONTENT_OPTION_TEXT}</option>
                                <!-- END content_select_2 -->
                              </select>
							  </td>
                              <td>
							  <select name="coach_select_first_2" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_first_2 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_first_2 -->
                              </select></td>
                              <td>
							  <select name="coach_select_second_2" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_second_2 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_second_2 -->
                              </select></td>
                            </tr>
                          </table></td>
                          </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="gSGSectionColumnHeadings">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><DIV id="item_3">&nbsp;&nbsp;■</DIV></td>
                      <td>&nbsp;(3)</td>
                      <td><div id="item_num_3" align="right" ></div></td>
                    </tr>
                  </table></td>
                  </tr>
                  <tr>
                    <td bgcolor="#FFFFFF"><table width="100%"  border="1" cellspacing="1" cellpadding="0">
                        <tr>
                          <td colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
						   <tr>
                              <td>
							  <select name="content_select_3" onchange="javascript:content_change(this)">
                                <!-- BEGIN content_select_3 -->
                                <option value="{CONTENT_OPTION_VALUE}" {CONTENT_SELECTED}>{CONTENT_OPTION_TEXT}</option>
                                <!-- END content_select_3 -->
                              </select>
							  </td>
                              <td>
							  <select name="coach_select_first_3" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_first_3 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_first_3 -->
                              </select></td>
                              <td>
							  <select name="coach_select_second_3" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_second_3 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_second_3 -->
                              </select></td>
                            </tr>
                          </table></td>
                          </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="gSGSectionColumnHeadings">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><DIV id="item_4">&nbsp;&nbsp;■</DIV></td>
                      <td>&nbsp;(4)</td>
                      <td><div id="item_num_4" align="right" ></div></td>
                    </tr>
                  </table></td>
                  </tr>
                  <tr>
                    <td bgcolor="#FFFFFF"><table width="100%"  border="1" cellspacing="1" cellpadding="0">
                        <tr>
                          <td colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
						    <tr>
                              <td>
							  <select name="content_select_4" onchange="javascript:content_change(this)">
                                <!-- BEGIN content_select_4 -->
                                <option value="{CONTENT_OPTION_VALUE}" {CONTENT_SELECTED}>{CONTENT_OPTION_TEXT}</option>
                                <!-- END content_select_4 -->
                              </select>
							  </td>
                              <td>
							  <select name="coach_select_first_4" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_first_4 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_first_4 -->
                              </select></td>
                              <td>
							  <select name="coach_select_second_4" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_second_4 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_second_4 -->
                              </select></td>
                            </tr>
                          </table></td>
                          </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="gSGSectionColumnHeadings">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><DIV id="item_5">&nbsp;&nbsp;■</DIV></td>
                      <td>&nbsp;(5) GK</td>
                      <td><div id="item_num_5" align="right" ></div></td>
                    </tr>
                  </table></td>
                  </tr>
                  <tr>
                    <td bgcolor="#FFFFFF"><table width="100%"  border="1" cellspacing="1" cellpadding="0">
                        <tr>
                          <td colspan="5"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
						    <tr >
                              <td>
							  <select name="gk_content_select_5">
                                <!-- BEGIN content_select_5 -->
                                <option value="{CONTENT_OPTION_VALUE}" {CONTENT_SELECTED}>{CONTENT_OPTION_TEXT}</option>
                                <!-- END content_select_5 -->
                              </select>
							  </td>
                              <td>
							  <select name="coach_select_first_5" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_first_5 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_first_5 -->
                              </select></td>
                              <td>
							  <select name="coach_select_second_5" onchange="javascript:coach_change(this)">
                                <!-- BEGIN coach_select_second_5 -->
                                <option value="{COACH_OPTION_VALUE}" {COACH_SELECTED}>{COACH_OPTION_TEXT}</option>
                                <!-- END coach_select_second_5 -->
                              </select></td>
                            </tr>
                          </table></td>
                          </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table>
			</td>
			</tr>
			</table>
			</td>
            <td valign="top" class="ProText"><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9" >
             <tr>
				 <td class="gSGSectionColumnHeadings">
				   <table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
					 <td width="20">&nbsp;</td>
					 <td width="35">id</td>
					 <td>player</td>
					<tr>
				   </table>
				 </td> 
			   </tr>
			  
			  
			  
              <!-- BEGIN training_player -->
			  <tr class="{TRAINING_ITEM_TR_CLASS}"><td>
			  <DIV id="np{PLAYER_ID}" style="z-index:4; left: {LEFT_COORDINATE}px; TOP: {TOP_COORDINATE}px; POSITION: absolute; VISIBILITY: hidden; visible: false"><IMG src="/fmol/images/{IMG_NAME}"></DIV>
			  <DIV id="p{PLAYER_ID}" style="z-index:5; left: {LEFT_COORDINATE}px; WIDTH: {WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute; VISIBILITY: hidden; visible: false"><IMG src="/fmol/images/{IMG_NAME}"></DIV>
			  <DIV id="index_{PLAYER_ID}"  style="z-index:6; left: {INDEX_LEFT_COORDINATE}px; WIDTH: {INDEX_WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute;">{INDEX}</DIV>
			  <DIV id="item_color_{PLAYER_ID}"  style="z-index:6; left: {IC_LEFT_COORDINATE}px; WIDTH: {IC_WIDTH_VALUE}px; TOP: {IC_COORDINATE}px; HEIGHT: {IC_VALUE}px; POSITION: absolute;">■</DIV>
			  <DIV id="player_name_{PLAYER_ID}"  style="z-index:7; left: {PLAYER_NAME_LEFT_COORDINATE}px; WIDTH: {PLAYER_NAME_WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute;">{PLAYER_NAME}</DIV>
			  <DIV id="player_div_nomove_{PLAYER_ID}" style="background-image:url(/fmol/images/prompt_small.gif); z-index:1; LEFT: -100px; VISIBILITY:hidden; POSITION: absolute; TOP: -100px; width:20px; height:20px; visible: false ">{INDEX}</DIV>
			  <DIV id="player_div_{PLAYER_ID}" style="background-image:url(/fmol/images/prompt_small.gif); z-index:2; LEFT: -100px; VISIBILITY:hidden; POSITION: absolute; TOP: -100px; width:20px; height:20px; visible: false ">{INDEX}</DIV>
			  </td></tr>
              <!-- END training_player -->
			  
            </table></td>
          </tr>
          <tr>
            <td colspan="2" class="ProText"><div align="center"><span class="gSGSectionColumnHeadings">
                <input name="clear" type="button" class="button" value="clear" onClick="javascript:reset_all()" style="width:100px ">
				<input name="save_tactics" type="submit" class="button" value="confirm" style="width:100px ">
            </span></div></td>
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