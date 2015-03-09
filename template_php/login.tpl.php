<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css">  
<title>FMOL Proto v0.1</title>
<script language="javascript">
// init the page when the page is onload
function init_page()
{
  try {
    window.status = 'Welcome to FMOL!';
  } catch(err) {}
}

init_page();

</script>

<h2>{TITLE}</h2>
<div align="center">
<form name=form1 method=post action="{ACTION}">

<table width="350" border="0" align="center" cellpadding="0" cellspacing="3" bgcolor="#999999">
  <tr>
    <td align="right"><b>Account:</b></td>
    <td width="105"><input name="user_name" type=text class="inputField" tabindex="1" value="{USER_NAME_VALUE}" style="width:100px"></td>
    <td><input name="login" type=submit class="button" tabindex="3" value="Login"></td>
  </tr>
  <tr>
    <td width="50" align="right"><b>Password:</b></td>
    <td><input name="passwd" type='password' class="inputField" tabindex="2" style="width:100px"></td>
    <td><input name="reset" type='reset' class="button" tabindex="4" value="Clear"></td>
  </tr>
  <tr>
    <td height="20" colspan="3">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><a href="/fmol/page/system/register.php">register</a>&nbsp;</td>
          <td><a href="/fmol/page/system/retrieve_passwd.php">retrieve password</a> </td>
        </tr>
      </table></td>
    </tr>
</table>

</form>
{ERROR_MESSAGE}
</div>