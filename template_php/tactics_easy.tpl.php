
<!DOCTYPE HTML PUBLIC "-//W3C//Dtd HTML 4.01 Transitional//EN"
"http://www.w3.org/tr/html4/loose.dtd">
<html>
<head> 
<META http-equiv="imagetoolbar" content="no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>FMOL Proto v0.1</title>
<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css"> 

<script language="javascript" src="/fmol/script/drag_layer_easy.js"></script>
<script language="javascript" src="/fmol/script/lay_tactics_easy.js"></script>
<script language="javascript" src="/fmol/script/tactics_easy_instruction.js"></script>
<script language="javascript" src="/fmol/script/tactics_functions.js"></script>
<script language="javascript" src="/fmol/script/default_player_instruction.js"></script>
<script language="javascript"> 
// the event is touched off when standard_tactics is changed
function standard_tactics_change(formObj)
{   
	formObj.action = "/fmol/page/tactics/handle_tactics.php?myaction=stdFormationChange";
	formObj.target = "_parent";
	formObj.submit();
}


function copy_to_cur_tactics()
{   
	var formObj = document.forms["save_form"];
	formObj.action = "/fmol/page/tactics/handle_tactics.php?myaction=copyToCurTactics";
	formObj.target = "_parent";
	formObj.submit();
}



// before_tactics_easy_save
function before_tactics_easy_save(form_obj)
{
	// because the "key_man_select" have a default row, so we must reduce the selectedIndex by 1
	// just as: parseInt(form_obj.elements["key_man"].selectedIndex) - 1
	// the result is from 0 to 9, stand for the tpop index
	
	
	return true;
}


</script>

</head>

<body style="overflow:auto;">
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">
  <tr><td height="1">{SPACE}
    </td>
  </tr> 
  <form name="save_form" target="_parent" action="/fmol/page/tactics/handle_tactics.php?myaction=saveTactics" onSubmit="return before_tactics_easy_save(this)" method="post">
 
      <input type="hidden" name="p_tactics_id" value="{P_TACTICS_ID}">
	  
      <input type="hidden" name="players_number" value="{PLAYERS_NUMBER}">
      <input type="hidden" name="players" value="{PLAYERS_VALUE}">
      <input type="hidden" name="data" value="{TACTICS_DATA}">
      <input type="hidden" name="return_page_url" value="{RETURN_PAGE_URL}">
  
  <tr>
    <td colspan="2">
	  <table width="100%" border="0" cellpadding="1" cellspacing="0">
      <tr>
       
		
		
		<td id="formation_td" width="282" align="left" valign="top"><img src="/fmol/images/field_new_small.jpg">
			    <table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                  <tr class="gSGRowEven_input">
                    <td align="right" nowrap >Standard Tactics:&nbsp;</td>
                    <td nowrap><select style="width:125px" name="standard_tactics" onChange="standard_tactics_change(this.form)">
                        <!-- BEGIN std_formation_select -->
                        <option value="{STD_FORMATION_OPTION_VALUE}" {STD_FORMATION_SELECTED}>{STD_FORMATION_OPTION_TEXT}</option>
                        <!-- END std_formation_select -->
                      </select>
                    </td>
                  </tr>
				  
				  <tr class="gSGRowOdd_input" onclick="showTeamInstruction(this)"  style="cursor:hand " title="Click to set the team instructions">
			  <td align="center" colspan="2">
			  {TEAM_NAME}
			  </td>
            </tr>
			
                </table></td>
		
		
		<td width="2"></td>
		
		 <td id="player_list_td" width="219" valign="top">
		  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
		  	
			<tr class="gSGRowOdd_input" onclick="showTeamInstruction(this)"  style="cursor:hand " title="Click to set the team instructions">
			  <td align="center" colspan="2">
			  {TEAM_NAME}
			  </td>
            </tr>
			
            <tr>
			  <td  class="gSGSectionColumnHeadings" >
			  <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			  <td width="2" >&nbsp;</td>
			  <td width="80">Pkd</td>
			  <td>Name</td>
			  </tr>
			  </table>
			  </td>
            </tr>
              <!-- BEGIN player_list -->
            <tr class="{PLAYER_DIVS_TR_CLASS}" >
              <td>
			   <table width="100%" border="0" cellpadding="0" cellspacing="0">
			   <tr>
			   <td width="2" >&nbsp;</td>
			   <td width="80">
			   
				  <select name="place_select_{PLAYER_ID}" onchange="javascript:place_change(this)">
                    <!-- BEGIN place_select -->
                    <option value="{PLACE_OPTION_VALUE}" {PLACE_SELECTED}>{PLACE_OPTION_TEXT}</option>
                    <!-- END place_select -->
                  </select>

			  </td>
			  <td align="left" onclick="showPlayerInstruction(this.parentNode, 'place_select_{PLAYER_ID}', '{PLAYER_ID}')" title="Click to set the player instructions" style="cursor:hand ">
			  {PLAYER_NAME}
			  </td>
			  </tr>
			  </table>
			  </td>
            </tr>
            <!-- END player_list -->
        </table></td>
		
        <td  valign="top" width="*">
		  <table width="100%"  border="0" cellpadding="1" cellspacing="0">
		    <tr>
			  
		      			
			  <td id="instruction_td" align="left" valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
                <tr>
                  <td>
				  
				  <!-- BEGIN instruction_div -->
				  <div id="{INSTRUCTION_DIV_ID}" style="display:{INSTRUCTION_DIV_DISPLAY} " >
				  {INSTRUCTION_DIV_CONTENT}
				  </div>
				  <!-- END instruction_div -->
				  
				  <div id="player_property_div" style="display:none " >
					<span id="player_property_span"></span>
					 </div>
					 
					</td>
                </tr>
				
				
              </table>
			  
			  </td>
		    </tr>
		    
          </table>
		  
		   <div style="LEFT: {FIELD_LEFT_COORDINATE}px; POSITION: absolute; TOP: {FIELD_TOP_COORDINATE}px"></div>
            <!-- BEGIN prompt_divs -->
            <DIV id="{PROMPT_PLACE_MOVE_DIV_NAME}" style="z-index:3; LEFT: 100px; TOP: 100px; width:{PRMOPT_IMG_WIDTH_VALUE}px; HEIGHT:{PROMPT_IMG_HEIGHT_VALUE}px; VISIBILITY: hidden; POSITION: absolute; visible: false"><IMG src="/fmol/images/{PROMPT_IMG_NAME}"></DIV>
            <DIV id="{PLAYER_DIV_NAME}" style="LEFT: 100px; VISIBILITY: hidden; POSITION: absolute; TOP: 100px; visible: false;" class="player_name_small"></DIV>
            <!-- END prompt_divs -->
			
        </td>
      </tr>
    </table>

	  </td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td width="120" align="center">
        <input name="save_tactics" type="submit" class="button" value="confirm" style="width:100px "></td>
		
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  
  
  </form>
  <tr><td align="center" style="display:{COPY_TO_CUR_TACTICS_DISPLAY} ">  <input name="copy_to_cur_tactics" type="button" onclick="copy_to_cur_tactics()" class="button" value="copy to cur tactics" style="width:160px "></td></tr>
 
</table>


<script language="javascript" >
{SCRIPT_CODE}



// the function which will init the page
function pageOnLoad()
{
/*
	var focus_passing = document.save_form.focus_passing;
	var focus_passing_value = document.save_form.elements["focus_passing_value"].value;
	setValueOfSelect(focus_passing, focus_passing_value);

	var mentality = document.save_form.mentality;
	var mentality_value = document.save_form.mentality_value.value;
	setValueOfSelect(mentality, mentality_value);
*/
}

// set the value of select
// select: the select to be set
// value: the valeu to be set
function setValueOfSelect(select, value) 
{
	var options = select.options;
	var length = select.options.length;
	for (var i=0; i<length; ++i) {
		if (options[i].value == value) {
			options[i].selected = true;
		}
		else {
			options[i].selected = false;
		}
	}	
}

			
			
// the function which is called when the page is loaded
pageOnLoad();

</script>

</body>

</html>

