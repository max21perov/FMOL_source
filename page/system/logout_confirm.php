<?php
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
// check whether the user can access the page
require_once(DOCUMENT_ROOT . "/lib/access_control.php");
?>
<style type="text/css">
<!--
.style1 {font-family: Geneva, Arial, Helvetica, sans-serif}
-->
</style>



<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css">  

<form action="/fmol/page/system/logout.php" method="post" name="opeForm">
<table width="100%"  border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td height="100" colspan="2"></td>
    </tr>
  <tr align="center">
    <td colspan="2"><span class="style1">ARE YOU SURE?</span></td>
  </tr>
  <tr>
    <td align="right"><input type="submit" value="yes" class="button" style="width:100px " /></td>
    <td><input type="button" value="no" onClick="javascript:history.go(-1)" class="button" style="width:100px " /></td>
  </tr>
</table>

</form>