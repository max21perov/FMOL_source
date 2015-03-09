<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="MenuText"  valign="bottom" title="go back to home team">&nbsp;&nbsp;<a href="/fmol/page/info/club_info.php"><img src="/fmol/images/home.gif" height="22" border="0" > home</td>
            </tr>
           
        </table></td>
      </tr>
	  <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="MenuTitle">&nbsp;News</td>
            </tr>
            <tr id="News_tr">
              <td class="MenuText">&nbsp;&nbsp;<a href="/fmol/page/mail/mail.php?team_id=<?=$team_id?>">News</a></td>
              <?php 
  			  // $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol"; 
  			  define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
			  require_once(DOCUMENT_ROOT . "/page/mail/auto_prompt_new_mail.php"); 
			?>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="MenuTitle">&nbsp;Operation</td>
            </tr>
            <tr>
              <td class="MenuText">&nbsp;&nbsp;<a href="javascript:window.history.back();">Back</a></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="MenuTitle">&nbsp;Search</td>
            </tr>
            <tr>
              <td class="MenuText">&nbsp;&nbsp;<a href="/fmol/page/search/search.php?team_id=<?=$team_id?>">Search</a></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="MenuTitle">&nbsp;Options</td>
            </tr>
            <tr>
              <td class="MenuText">&nbsp;&nbsp;<a href="/fmol/page/system/logout_confirm.php">Logout</a></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
