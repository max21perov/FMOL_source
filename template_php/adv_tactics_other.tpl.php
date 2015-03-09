
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
<script language="javascript" src="/fmol/script/drag_layer_other.js"></script>
<script language="javascript" src="/fmol/script/lay_tactics_other.js"></script>
<script language="javascript" src="/fmol/script/tactics_functions.js"></script>
</head>

<body style="overflow:auto;">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr><td height="2">{SPACE}</td></tr>
  <tr>
    <td height="565" width="392">
    <div style="LEFT: 1px; POSITION: absolute; TOP: 2px"><img src="/fmol/images/field_new.jpg"></div>
	
	<!-- BEGIN prompt_divs -->
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
		  <DIV id="place_{PLAYER_ID}" onMouseOver="show_info_layer('info_layer', '{PLAYER_ID}', true)" onMouseOut="hide_info_layer('info_layer')" style="z-index:6; left: {PLACE_LEFT_COORDINATE}px; WIDTH: {PLACE_WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute;"></DIV>
		  <DIV id="player_name_{PLAYER_ID}" onMouseOver="show_info_layer('info_layer', '{PLAYER_ID}', true)" onMouseOut="hide_info_layer('info_layer')" style="z-index:7; left: {PLAYER_NAME_LEFT_COORDINATE}px; WIDTH: {PLAYER_NAME_WIDTH_VALUE}px; TOP: {TOP_COORDINATE}px; HEIGHT: {HEIGHT_VALUE}px; POSITION: absolute;">{PLAYER_NAME}</DIV>
		  
		  <!-- 
		  <DIV id="info_{PLAYER_ID}" onMouseOver="over_info_layer('info_{PLAYER_ID}')" onMouseOut="out_info_layer('info_{PLAYER_ID}')" style="z-index:10; left:{INFO_LEFT_COORDINATE}px; top:{INFO_TOP_COORDINATE}px; width:300px; height:350px; POSITION: absolute; VISIBILITY: hidden; visible: false" class="halfalpha">
		    {IFRAME_CODE}
		    <table width="100%" height="100%"  border="0" cellspacing="1" cellpadding="2" bgcolor="#6A71A3">
			  <tr bgcolor="#FFCC33">
			    <td colspan="4"> Common</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
				<td align="right">given name</td>
			    <td>{GIVEN_NAME}</td>
			    <td align="right">family name</td>
			    <td>{FAMILY_NAME}</td>
			  </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">position</td>
			    <td>{POSITION}</td>
			    <td align="right">height</td>
			    <td>{HEIGHT}</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">prefer_foot</td>
			    <td colspan="3">{PREFER_FOOT}</td>
			    </tr>
			  <tr bgcolor="#FFCC33">
			    <td colspan="4">Physical</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">accelerate</td>
			    <td>{ACCELERATE}</td>
			    <td align="right">agility</td>
			    <td>{AGILITY}</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">jumping</td>
			    <td>{JUMPING}</td>
			    <td align="right">pace</td>
			    <td>{PACE}</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">stamina</td>
			    <td>{STAMINA}</td>
			    <td align="right">strength</td>
			    <td>{STRENGTH}</td>
			    </tr>
			  <tr bgcolor="#FFCC33">
			    <td colspan="4"> Technical</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">ball_control</td>
			    <td>{BALL_CONTROL}</td>
			    <td align="right">crossing</td>
			    <td>{CROSSING}</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">finishing</td>
			    <td>{FINISHING}</td>
			    <td align="right">heading</td>
			    <td>{HEADING}</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">passing</td>
			    <td>{PASSING}</td>
			    <td align="right">tackling</td>
			    <td>{TACKLING}</td>
			    </tr>
			  <tr bgcolor="#FFCC33">
			    <td colspan="4"> Technical</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">anticipation</td>
			    <td>{ANTICIPATION}</td>
			    <td align="right">creativity</td>
			    <td>{CREATIVITY}</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">decision</td>
			    <td>{DECISION}</td>
			    <td align="right">off_the_ball</td>
			    <td>{OFF_THE_BALL}</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">positioning</td>
			    <td>{POSITIONING}</td>
			    <td align="right">teamwork</td>
			    <td>{TEAMWORK}</td>
			    </tr>
			  <tr bgcolor="#FFFFCC">
			    <td align="right">work_rate</td>
			    <td>{WORK_RATE}</td>
			    <td colspan="2">&nbsp;</td>
			    </tr>
			</table>
            
		  
		  </DIV>
		  -->
	   </td></tr>
	   <!-- END player_divs --> 

	   
	   
	   
	  </table>

	    
		
	
	</td>
  </tr>
  
  <form name="save_form" onSubmit="javascript:return save()" action="#" method="post">
  <tr>
    <td><input type="hidden" name="tactics_id" value="1">
      <input type="hidden" name="players" value="{PLAYERS_VALUE}">
      <input type="hidden" name="players_number" value="{PLAYERS_NUMBER}">
      <input type="hidden" name="data" value="{TACTICS_DATA}">
      <input type="hidden" name="subs_data" value="{TACTICS_SUBS_DATA}">
      <input type="hidden" name="others_data" value="{TACTICS_OTHERS_DATA}">
      <input type="hidden" name="subs_count" value="{TACTICS_SUBS_COUNT}">
      <input type="hidden" name="passing_style_value" value="{PASSING_STYLE_VALUE}">
      <input type="hidden" name="mentality_value" value="{MENTALITY_VALUE}">
      <table width="391"  border="0" cellpadding="1" cellspacing="1" bgcolor="#0069b9">
      
      <tr class="gSGRowEven_input">
        <td align="right"> Passing Style:</td>
        <td><select style="width:125px" name="passing_style">
          <option value="0">Middle</option>
          <option value="1">LeftWing</option>
          <option value="2">RightWing</option>
          <option value="3">BothWing</option>
          <option value="4">SideMixed</option>
        </select></td>
      </tr>
      <tr class="gSGRowEven_input">
        <td align="right">Mentality:</td>
        <td><select style="width:125px" name="mentality">
          <option value="0">UltraDefensive</option>
          <option value="1">Defensive</option>
          <option value="2">Normal</option>
          <option value="3">Attack</option>
          <option value="4">UltraAttack</option>
        </select></td>
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

