<?php
session_start();
// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
// check whether the user can access the page
require_once(DOCUMENT_ROOT . "/lib/access_control.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/Session.class.php");


$session = new Session($db); 
$user_id = $_SESSION['s_primary_user_id'];
$con = "user_id=$user_id";
$returnValue = $session->delete($con);  //在session表中删除用户信息。

session_destroy();  //结束当前的会话，并清空会话中的所有资源

?>

<form>
<table width="500" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td height="100"></td>
    </tr>
  <tr>
    <td>
    	You have been logged out. 
	  </td>
  </tr>
  <tr>
    <td>Thank you for using fmol!</td>
  </tr>
  <tr>
    <td>If you would like to re-login, click <a href="/fmol/index.php">here</a>. </td>
  </tr>
  
</table>
</form>
