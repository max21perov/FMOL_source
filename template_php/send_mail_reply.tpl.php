
<form method="post" name="opeForm" action="send_mail_procedure.php" >
<input type="hidden" name="to_team_id" value="<?=$to_team_id?>" />

<table width="100%"  border="0" cellspacing="0" cellpadding="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
      <tr align="center">
        <td colspan="2" class="gSGSectionTitle_gray"><div align="center" class="gSGSectionTitle">Send mail</div></td>
      </tr>
      <tr class="gSGRowOdd_input">
        <td><table width="100%"  border="0" cellpadding="1" cellspacing="1">
            <tr>
              <td height="5"></td>
            </tr>
            <tr>
              <td width="150" align="right">Subject:&nbsp;</td>
              <td colspan="2"><input type="text" class="inputField" name="subject" style="width:250px " value="<?=$subject?>" /></td>
            </tr>
            <tr>
              <td align="right">Message:&nbsp;</td>
              <td colspan="2"><textarea class="inputField" name="content" style="width:250px;height:200px;OVERFLOW:auto;"></textarea></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td width="250" align="center"><input type="submit" name="send" value="Send It" class="button" style="width:150px " /></td>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>


</form>
