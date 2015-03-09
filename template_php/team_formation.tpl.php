
<!DOCTYPE HTML PUBLIC "-//W3C//Dtd HTML 4.01 Transitional//EN"
"http://www.w3.org/tr/html4/loose.dtd">
<html>
<head> 
<META http-equiv="imagetoolbar" content="no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>FMOL Proto v0.1</title>
<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css"> 

<script language="javascript" src="/fmol/script/drag_layer_match_formations.js"></script>
<script language="javascript" src="/fmol/script/lay_tactics_match_formations.js"></script>

</head>

<body style="overflow:auto;">

<table width="100%" border="0" cellpadding="0" cellspacing="0" >

<tr><td height="1">{SPACE} </td></tr>
<form name="save_form" method="post" >
      <input type="hidden" name="p_tactics_id" value="{P_TACTICS_ID}">
      <input type="hidden" name="players_number" value="{PLAYERS_NUMBER}">
      <input type="hidden" name="players" value="{PLAYERS_VALUE}">
      <input type="hidden" name="data" value="{TACTICS_DATA}">
  
<tr><td><img src="/fmol/images/field_new_small.jpg"></td></tr>

            <!-- BEGIN prompt_divs -->
            <DIV id="{PROMPT_PLACE_MOVE_DIV_NAME}" style="z-index:3; LEFT: 100px; TOP: 100px; width:{PRMOPT_IMG_WIDTH_VALUE}px; HEIGHT:{PROMPT_IMG_HEIGHT_VALUE}px; VISIBILITY: hidden; POSITION: absolute; visible: false"><IMG src="/fmol/images/{PROMPT_IMG_NAME}"></DIV>
            <DIV id="{PLAYER_DIV_NAME}" style="LEFT: 100px; VISIBILITY: hidden; POSITION: absolute; TOP: 100px; visible: false;" class="player_name_small"></DIV>
            <!-- END prompt_divs -->

	
	
	</form>
</table>


<script language="javascript" >
{SCRIPT_CODE}
</script>

</body>

</html>