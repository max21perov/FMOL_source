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
$returnValue = $session->delete($con);  //��session����ɾ���û���Ϣ��

session_destroy();  //������ǰ�ĻỰ������ջỰ�е�������Դ

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
