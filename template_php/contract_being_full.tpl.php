<script type="text/javascript" language="javascript">

function changePositionType(control) {
	var frm = document.forms["contract_being_full_form"];
	var position_filter = control.value; 
	frm.action = "/fmol/page/transfer/contract_being_full.php?position_filter=" + position_filter;
	
	frm.submit();
}

var curRow = null;
// click one row
function clickRow(control) 
{
	if (curRow != null)
		curRow.className = "gSGRowOdd";
	
	curRow = control;
	curRow.className = "gSGRowEven";
}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr><td height="1">{SPACE}</td></tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
		  <form name="contract_being_full_form" method="post" action="/fmol/page/transfer/handle_transfer.php" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Contract Being Full</div></td>
			  <td class="gSGSectionTitle">
				
				<div align="right" class="gSGSectionTitle">
				  Filter:
				  <select name="position_filter_select" onChange="changePositionType(this)" >
				    <option value="" {ALL_SELECTED}>All</option>
				    <option value="0" {GOAL_KEEPERS_SELECTED}>Goal Keepers</option>
				    <option value="1" {DEFENDERS_SELECTED}>Defenders</option>
				    <option value="2" {MIDFIELDERS_SELECTED}>Midfielders</option>
				    <option value="3" {STRIKERS_SELECTED}>Strikers</option>
				  </select>
				</div>
				
				</td>
            </tr>
            <tr>
              <td class="ProText" colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
				    <td class="gSGSectionColumnHeadings">&nbsp;Team Name</td>
                    <td class="gSGSectionColumnHeadings" nowrap title="Player Name">&nbsp;Name</td>
                    <td class="gSGSectionColumnHeadings" >Age</td>
                    <td class="gSGSectionColumnHeadings" >Pos</td>
                    <td class="gSGSectionColumnHeadings">Worth</td>
                    <td class="gSGSectionColumnHeadings">bids</td>
                  </tr>
                  <!-- BEGIN contract_being_full -->
				  <tr style="display:{LIST_SEPARATOR}">
				    <td colspan="8" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
                  <tr class="{TRAN_TR_CLASS}"  onClick="clickRow(this)" >
                    <td class="BlackText">&nbsp;<a href="/fmol/page/info/club_info.php?team_id={TEAM_ID}" >{TEAM_NAME}</a></td>
                    <td class="BlackText"><a href="/fmol/page/players/player_info.php?player_id={PLAYER_ID}" >{PLAYER_NAME}</a></td>
					<td class="BlackText">{AGE}</td>
                    <td class="BlackText">{POS}</td>
					<td class="BlackText">{WORTH}</td>
                    <td class="BlackText">{BIDS}</td>
                  </tr>
                  <!-- END contract_being_full -->
              </table></td>
            </tr>
			</form>
			
			<!-- buttom line -->
			<tr>
			  <td colspan="2" class="cBBottom"><img height=1 src="/fmol/images/blank.gif"></td>
			</tr>
		  
			<tr class="gSGRowOdd_input">
			  <td colspan="2">{PAGER_TOOLBAR}</td>
			</tr>
			
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

