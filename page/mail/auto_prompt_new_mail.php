<?php
//----------------------------------------------------------------------------	
// get the data from session
//----------------------------------------------------------------------------
$s_primary_team_id = $_SESSION['s_primary_team_id']; 

echo '<script language="javascript" type="text/javascript">';
echo "var p_team_id=$s_primary_team_id";
echo '</script>';
?>

<script type="text/javascript" src="/fmol/script/xmlextras.js"></script>
<script language="javascript" type="text/javascript">
// get whether there is new mail
function getNewMailFlag()
{
  var postStr = "to_id=" + p_team_id;
  // use xmlhttp to visit the server, and get the suitable comment
  var xmlHttp = XmlHttp.create();
  var async = false;  // synchronous
  xmlHttp.open("POST", "/fmol/page/mail/new_mail_machine.php?"+postStr, async);
  xmlHttp.send(null);
  

  var xmlResponseDoc = XmlDocument.create();
  xmlResponseDoc.loadXML(xmlHttp.responseText);  
  
  if (xmlHttp.responseText != "") { 
	  var data_nodes = xmlResponseDoc.getElementsByTagName("data");
	  var mail_count_nodes = data_nodes[0].getElementsByTagName("mail_count");
	  var mail_count = mail_count_nodes[0].firstChild.nodeValue;
	
	  // prompt the user that there are new mails  
	  displayTheMailFlag(mail_count);
  }
  
}

function displayTheMailFlag(mail_count) 
{ 
  var tr_obj = document.getElementById("News_tr");
  if (mail_count > 0) {  
    tr_obj.style.backgroundColor = "orange";  
  }
  else {  
    tr_obj.style.backgroundColor = "";  
  }
}

getNewMailFlag();
// run the function per 60 seconds
var ctroltime = setInterval("getNewMailFlag()", 30000);
</script>
