<link href="/fmol/css/Style1.css" rel="stylesheet" type="text/css">  

<table width="100%" >
<tr><td>
	<table width="100%"  border="0" cellspacing="2" cellpadding="1">
	  <tr>
		<td>{ERROR_MESSAGE}</td>
	  </tr>
	</table>
</td></tr>

<tr><td>
	<form name="form1" method="post" action="{ACTION}" >
	<table width="100%"  border="0" cellpadding="1" cellspacing="2" bgcolor="#CCCCCC">
	  <tr>
		<td align="right"> please input your user name here: </td>
		<td><input name="user_name" type="text" class="inputField" value="{USER_NAME_VALUE}" />
		  *</td>
		</tr>
		
		<tr>
		<td align="right">check_number</td>
		<td><input name="check_number" type="text" class="inputField" size="10" />
		  *&nbsp;<img src="/fmol/page/system/check_number.php" width="100" height="30" border=0 alt=""></td>
		</tr>
		
	  <tr>
		<td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="right"><input name="retrieve_passwd" type="submit" class="button" value="submit" /></td>
			<td width="10"></td>
			<td><input name="reset" type="reset" class="button" value="reset" /></td>
		  </tr>
		</table></td>
		</tr>
	</table>
	</form>
</td></tr>
</table>
