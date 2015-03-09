<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css">  
{SPACE}
<table width="100%" >
<tr><td>
	<table width="100%"  border="0" cellspacing="2" cellpadding="1">
	  <tr>
		<td>{ERROR_MESSAGE}</td>
	  </tr>
	</table>
</td></tr>

<tr><td>
	<form method="post" action="{ACTION}">
	<table width="100%"  border="0" cellpadding="1" cellspacing="2" bgcolor="#999999">
	  <tr>
		<td align="right">user name </td>
		<td><input name="user_name" type="text" class="inputField" value="{USER_NAME_VALUE}"/>
		  *</td>
		</tr>
	  <tr>
		<td align="right">Email</td>
		<td><input name="email" type="text" class="inputField" value="{EMAIL_VALUE}"/>
		  * (21cn.com, qq.com, yahoo.com, eyou.com, citiz.net, 2911.net)</td>
		</tr>
	  <tr>
		<td align="right">confirm Email </td>
		<td><input name="confirm_email" type="text" class="inputField" value="{CONFIRM_EMAIL_VALUE}"/>
		  *</td>
		</tr>
	  <tr>
		<td align="right">check_number</td>
		<td><input name="check_number" type="text" class="inputField" size="10" />
		  *&nbsp;<img src="/fmol/page/system/check_number.php" width="100" height="30" border=0 alt=""></td>
		</tr>
	  <tr>
		<td align="right"><input name="register" type="submit" class="button" value="submit" /></td>
		<td>&nbsp;&nbsp;<input name="reset" type="reset" class="button" value="reset" /></td>
		</tr>
	</table>
	</form>
</td></tr>
</table>
