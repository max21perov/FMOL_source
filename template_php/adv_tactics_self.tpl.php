
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
<script language="javascript" src="/fmol/script/drag_layer.js"></script>
<script language="javascript" src="/fmol/script/lay_tactics.js"></script>
<script language="javascript" src="/fmol/script/tactics_functions.js"></script>
<script language="javascript" src="/fmol/script/std_formation.js"></script>
</head>

<body style="overflow:auto;" >  <!-- be careful of this oncontextmenu: oncontextmenu=window.event.returnValue=false -->
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr><td height="2">{SPACE}</td></tr>
  <tr>
    <td height="565" width="392" id="graphic_td">  <!-- be careful of this id -->
    <div style="LEFT: 1px; POSITION: absolute; TOP: 2px"><img src="/fmol/images/field_new.jpg"></div>
	<!-- draw line div -->
	<DIV style="POSITION: absolute; " id="draw_line"> </DIV>
	
	<!-- BEGIN prompt_divs -->
	<DIV id="{PROMPT_DIV_NAME}" style="z-index:1; LEFT: 100px; VISIBILITY: hidden; POSITION: absolute; TOP: 100px; visible: false"><IMG src="/fmol/images/prompt.gif"></DIV>
	<DIV id="{PROMPT_PLACE_NOTMOVE_DIV_NAME}" style="z-index:2; LEFT: 100px; VISIBILITY: hidden; POSITION: absolute; TOP: 100px; visible: false"><IMG src="/fmol/images/{PROMPT_IMG_NAME}"></DIV>
	<DIV id="{PROMPT_PLACE_MOVE_DIV_NAME}" style="z-index:3; LEFT: 100px; TOP: 100px; width:{PRMOPT_IMG_WIDTH_VALUE}px; HEIGHT:{PROMPT_IMG_HEIGHT_VALUE}px; VISIBILITY: hidden; POSITION: absolute; visible: false"><IMG src="/fmol/images/{PROMPT_IMG_NAME}"></DIV>
	<DIV id="{PLAYER_DIV_NAME}" style="LEFT: 100px; VISIBILITY: hidden; POSITION: absolute; TOP: 100px; visible: false;" class="player_name"></DIV>
	<!-- END prompt_divs -->
  
	</td>
	<td height="662" rowspan="2" valign="top">
	  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
	  
	   <tr>
	     <td class="gSGSectionColumnHeadings">
		   <table width="100%" border="0" cellpadding="0" cellspacing="0">
		    <tr>
	         <td width="2">&nbsp;</td>
	         <td width="35">Pkd</td>
	         <td>Name</td>
			<tr>
		   </table>
		 </td> 
	   </tr>
	   
	  <div id="info_layer" onmouseover="over_info_layer('info_layer')" onmouseout="out_info_layer('info_layer')" style="Z-INDEX: 10; LEFT: 50px; VISIBILITY: hidden; WIDTH: 300px; POSITION: absolute; TOP: 100px; HEIGHT: 350px; visible: false; "> 
	  {IFRAME_CODE}
	  </div>
	   
	   <!-- BEGIN player_divs -->
	   <tr class="{PLAYER_DIVS_TR_CLASS}"><td>
		  <DIV id="np{PLAYER_ID}" style="z-index:4; left: {LEFT_COORDINATE}px; TOP: {TOP_COORDINATE}px; POSITION: absolute; VISIBILITY: hidden; visible: false"><IMG src="/fmol/images/{IMG_NAME}"></DIV>
		  <DIV id="p{PLAYER_ID}" style="z-index:5; left: {LEFT_COORDINATE}px; WIDTH: {WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute; VISIBILITY: hidden; visible: false"><IMG src="/fmol/images/{IMG_NAME}"></DIV>
		  <DIV id="place_{PLAYER_ID}" onMouseOver="show_info_layer('info_layer', '{PLAYER_ID}', true)" onMouseOut="hide_info_layer('info_layer')" style="z-index:6; left: {PLACE_LEFT_COORDINATE}px; WIDTH: {PLACE_WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute;"></DIV>
		  <DIV id="player_name_{PLAYER_ID}" onMouseOver="show_info_layer('info_layer', '{PLAYER_ID}', true)" onMouseOut="hide_info_layer('info_layer')" style="z-index:7; left: {PLAYER_NAME_LEFT_COORDINATE}px; WIDTH: {PLAYER_NAME_WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute;">{PLAYER_NAME}</DIV>
		  
		 
	   </td></tr>
	   <!-- END player_divs --> 

	   
	   
	   
	  </table>

	    
		
	
	</td>
  </tr>
  
  <form name="save_form" onSubmit="javascript:return save()" action="/fmol/page/tactics/handle_tactics.php?myaction=saveAdvTactics" target="_parent" method="post">
  <tr>
    <td><input type="hidden" name="p_tactics_id" value="{P_TACTICS_ID}">
      <input type="hidden" name="players" value="{PLAYERS_VALUE}">
      <input type="hidden" name="players_number" value="{PLAYERS_NUMBER}">
      <input type="hidden" name="data" value="{TACTICS_DATA}">
      <input type="hidden" name="subs_data" value="{TACTICS_SUBS_DATA}">
      <input type="hidden" name="others_data" value="{TACTICS_OTHERS_DATA}">
      <input type="hidden" name="subs_count" value="{TACTICS_SUBS_COUNT}">
      <input type="hidden" name="passing_style_value" value="{PASSING_STYLE_VALUE}">
      <input type="hidden" name="mentality_value" value="{MENTALITY_VALUE}">
	  
      <input type="hidden" name="return_page_url" value="{RETURN_PAGE_URL}">
	  
	  <input type="hidden" name="tactics_run_data" value="{TACTICS_RUN_DATA}">
	  
      <table width="391"  border="0" cellpadding="1" cellspacing="1" bgcolor="#0069b9">
	  <tr class="gSGRowEven_input">
        <td align="right" nowrap >Left Key Function:&nbsp; </td>
        <td nowrap> 
		    <input type="radio" name="choose_left_key_function" value="move_player" checked />move player 
		    <input type="radio" name="choose_left_key_function" value="draw_run_direction" /> draw run direction 
		</td>
      </tr>
      <tr class="gSGRowEven_input">
        <td align="right" nowrap >Standard Tactics:&nbsp;</td>
        <td nowrap>
		  <select style="width:125px" name="standard_tactics" onChange="standard_tactics_change(this)">
			  <!-- BEGIN std_formation_select -->
			  <option value="{STD_FORMATION_OPTION_VALUE}" {STD_FORMATION_SELECTED}>{STD_FORMATION_OPTION_TEXT}</option>
			  <!-- END std_formation_select -->
		  </select></td>
      </tr>
      <tr class="gSGRowEven_input" style="display:none ">
        <td align="right"> Passing Style:&nbsp;</td>
        <td><select style="width:125px" name="passing_style">
          <option value="0">Middle</option>
          <option value="1">LeftWing</option>
          <option value="2">RightWing</option>
          <option value="3">BothWing</option>
          <option value="4">SideMixed</option>
        </select></td>
      </tr>
      <tr class="gSGRowEven_input" style="display:none ">
        <td align="right">Mentality:&nbsp;</td>
        <td><select style="width:125px" name="mentality">
          <option value="0">UltraDefensive</option>
          <option value="1">Defensive</option>
          <option value="2">Normal</option>
          <option value="3">Attack</option>
          <option value="4">UltraAttack</option>
        </select></td>
      </tr>
      <tr class="gSGRowEven_input">
        <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right"><input name="save_tactics" type="submit" class="button" value="save" style="width:100px "></td>
            <td width="8"></td>
            <td><input name="clear" type="button" class="button" onClick="reset_all()" value="clear" style="width:100px "></td>
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

