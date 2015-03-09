{SPACE}

<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
      <tr>
        <td colspan="3" class="gSGSectionTitle"><div align="center" class="gSGSectionTitle">Search Result</div></td>
      </tr>
	  
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td class="gSGSectionColumnHeadings">&nbsp; team_id</td>
              <td class="gSGSectionColumnHeadings">team_name</td>
              <td class="gSGSectionColumnHeadings">Message</td>
              <td class="gSGSectionColumnHeadings">Challenge</td>
              <td class="gSGSectionColumnHeadings">Country</td>
            </tr>
        
		
        <!-- BEGIN search_result -->
        <tr class="{SEARCH_RESULT_TR_CLASS}" onMouseOver="listover(this)" onMouseOut="listout(this)">
          <td class="BlackText">&nbsp; <a href="/fmol/page/info/club_info.php?team_id={PRIMARY_TEAM_ID}">{TEAM_ID}</a></td>
          <td class="BlackText"><a href="/fmol/page/info/club_info.php?team_id={PRIMARY_TEAM_ID}">{TEAM_NAME}</a></td>
		  <td class="BlackText"><a href="/fmol/page/mail/send_mail.php?team_id={PRIMARY_TEAM_ID}" >Message</a></td>
          <td class="BlackText"><a href="/fmol/page/friendly/friendly_arrange.php?team_id={PRIMARY_TEAM_ID}" >Challenge</a></td>
          <td class="BlackText">{COUNTRY}</td>                    
        </tr>
        <!-- END search_result -->
		
        </table></td>
      </tr>
	  
	<!-- buttom line -->
	<tr>
	  <td class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
	</tr>
  
	<tr class="gSGRowOdd_input">
	  <td>{PAGER_TOOLBAR}</td>
	</tr>
	  
    </table></td>
  </tr>
</table>
