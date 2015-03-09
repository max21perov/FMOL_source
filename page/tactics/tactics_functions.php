<?php


/**
 * form the player property script from result set
 *
 * @param [rs]		the result set
 *
 * @return  $script
 */	
function getPlayerPropertyScript($rs)
{
	$script = "";
	$script = "ppTable". $rs->fields['player_id']. " = new Array( \"".
        	$rs->fields['given_name']. "\", \"". $rs->fields['family_name']. "\", ". intval($rs->fields['position']). ", ". 
        	intval($rs->fields['prefer_foot']). ", ". intval($rs->fields['cloth_number']). ", ". intval($rs->fields['age']). ", ". 
        	intval($rs->fields['pace']). ", ". intval($rs->fields['power']). ", ". 
        	intval($rs->fields['stamina']). ", ". intval($rs->fields['height']). ", ". intval($rs->fields['finishing']). ", ". 
        	intval($rs->fields['passing']). ", ". intval($rs->fields['crossing']). ", ". intval($rs->fields['ball_control']). ", ". 
        	intval($rs->fields['tackling']). ", ". intval($rs->fields['heading']). ", ". intval($rs->fields['play_making']). ", ". 
        	intval($rs->fields['off_awareness']). ", ". intval($rs->fields['def_awareness']). ", ". intval($rs->fields['experience']). ", ". 
        	intval($rs->fields['agility']). ", ". intval($rs->fields['reflex']). ", ". intval($rs->fields['handing']). ", ". 
        	intval($rs->fields['rushing_out']). ", ". intval($rs->fields['positioning']). ", ". intval($rs->fields['aerial_ability']). ", ". 
        	intval($rs->fields['judgment']). ", ". intval($rs->fields['form']). ", ". intval($rs->fields['condition']). ", ". 
        	intval($rs->fields['morale']). ", ". intval($rs->fields['happiness']). 
        	" );
        ";	

		
	return $script;
}



/**
 * get the browser info
 *
 * @param  
 *
 * @return the info
 */	
function browser_info()
{
	$browser="";
	$browserver="";
	$Browsers =array("Lynx","MOSAIC","AOL","Opera","JAVA","MacWeb","WebExplorer","OmniWeb");
	$Agent = $_SERVER['HTTP_USER_AGENT'];//  $GLOBALS["HTTP_USER_AGENT"];

	for ($i=0; $i<=7; $i++)
	{
		if (strpos($Agent, $Browsers[$i]))
		{
			$browser = $Browsers[$i];
			$browserver ="";
		}
	}
	if (ereg("Mozilla",$Agent) && !ereg("MSIE",$Agent))
	{
		$browser = "Netscape Navigator";
		return $browser;
	}
	else if (ereg("Mozilla",$Agent) && ereg("Opera",$Agent))
	{
		$browser = "Opera";
		return $browser;
	}
	else if (ereg("Mozilla",$Agent) && ereg("MSIE",$Agent))
	{
		$browser = "Internet Explorer";
		return $browser;
	}
	
	return "Unknown";
	
}


?>

