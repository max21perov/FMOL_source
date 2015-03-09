<?php 
// when the page go back, it will not appear time out
ob_start(); 
if(function_exists(session_cache_limiter)) { 
    session_cache_limiter("private, must-revalidate"); 
} 

session_start();

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/access_control.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//Dtd HTML 4.01 Transitional//EN"
"http://www.w3.org/tr/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/origin.dwt" codeOutsideHTMLIsLocked="false" -->
<head> 
<META http-equiv="imagetoolbar" content="no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META HTTP-EQUIV="pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
<META HTTP-EQUIV="expires" CONTENT="0">
<title>FMOL Proto v0.1</title>
  
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css">  


<script src="/fmol/script/main.js" language="JavaScript"></script>
<script language="javascript">
// init the page when the page is onload
function init_page()
{
  try {
    window.status = 'Welcome to FMOL!';
  } catch(err) {}
}
</script>

</head>

<body onLoad="init_page()" style="overflow:auto">

<?php

  define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
  
?>
<table width="995" border="0" align="center" cellpadding="0" cellspacing="1">
   <tr class="BodyTable">
    <td width="135" height="100" >
	<?php
	   require_once(DOCUMENT_ROOT . "/page/system/team_logo.php"); 
	?>
	</td>
    <td width="737">
    <?php
	  require(DOCUMENT_ROOT . "/page/system/advertising.php"); 
	?>
	</td> 
    <td width="116" valign="middle">
	<?php
	  require(DOCUMENT_ROOT . "/page/system/advertising.php"); 
	?>
	</td>
  </tr>
  <tr class="BodyTable">
    <td colspan="3" height="30">
	<?php
	  require_once(DOCUMENT_ROOT . "/page/system/status_bar.php"); 
	?>
	</td>
  </tr>
  
   <tr class="BodyTable">
    <td colspan="3" height="25"> 
	  <table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#CAD6E8">
      <tr>
        <td height="25" ><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
          <tr class="MenuText">
		    <td width="2">&nbsp;</td>
			<!-- InstanceBeginEditable name="EditNavigation" -->
	 
            <td width="120" align="center" nowrap><a href="/fmol/page/transfer/transfer_market.php">Transfer Market</a></td>
            <td width="20" align="center">-&gt;</td>
            <td width="80" align="center"><a href="<?=$_SERVER['REQUEST_URI']?>">Hot List</a></td>
            <td>&nbsp;</td>

	<!-- InstanceEndEditable -->
	      </tr>
        </table></td>
        </tr>
    </table>
	</td>
  </tr>
  
  
  <tr class="BodyTable">  
    <td height="100" valign="top"><div align="left">
	<!-- InstanceBeginEditable name="EditMenu" -->
	
	<?php 
		$player_id = $_GET['player_id'];
		require_once (DOCUMENT_ROOT . "/template_php/menu_self.tpl.php");
	?>
	<!-- InstanceEndEditable -->
      </div></td> 
    <td rowspan="2" valign="top">
	<!-- InstanceBeginEditable name="EditMain" -->
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><table width="100%" cellspacing="0" class="TableMenu" >
              <tr>
                <td width="20%" class="ButtonSel">Hot List</td>
                <td width="20%" class="ButtonPro"><a href="/fmol/page/transfer/transfer_market.php">Transfer Market</a></td>
                <td width="20%" class="ButtonPro"><a href="/fmol/page/transfer/free_market.php">Free Market</a></td>
				<td width="20%" class="ButtonPro"><a href="/fmol/page/transfer/contract_being_full.php">Contract Being Full</a></td>
                <td width="20%" class="ButtonPro"><a href="/fmol/page/transfer/transfer_history.php">Transfer History</a></td>
              </tr>
			  <tr>
                <td width="20%" class="ButtonPro"><a href="/fmol/page/transfer/free_coach_pool.php">Free Coach Pool</a></td>
                <td width="20%" class="ButtonPro">&nbsp;</td>
                <td width="20%" class="ButtonPro">&nbsp;</td>
                <td width="20%" class="ButtonPro">&nbsp;</td>
                <td width="20%" class="ButtonPro">&nbsp;</td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td>
		  <?php 
		    require_once ('transfer_hotlist_maker.php'); 
		  ?>
          </td>
        </tr>
      </table>  
    <!-- InstanceEndEditable --></td>
    <td rowspan="2" bgcolor="#FFFFFF"><div align="center">Advertising</div></td>
  </tr>
  <tr>
    <td height="133" valign="top" bgcolor="#FFFFFF" class="BodyTable">
	<!-- InstanceBeginEditable name="EditAction" -->
	
	<!-- InstanceEndEditable --></td>
  </tr>
</table>
</body>
<!-- InstanceEnd --></html>
