<script type="text/javascript" language="javascript">

function changeBitsType(control) {
	var frm = document.forms["opeForm"];
	var bids_type = control.value; 
	frm.action = "/fmol/page/transfer/transfer_history.php?bids_type=" + bids_type;
	
	frm.submit();
}


function changePositionType(control) {
	var frm = document.forms["opeForm"];
	var position_filter = control.value; 
	frm.action = "/fmol/page/transfer/transfer_history.php?position_filter=" + position_filter;
	
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
		<form name="opeForm" method="post" action="/fmol/page/transfer/handle_transfer.php" >
            <tr>
              <td class="gSGSectionTitle"><div class="gSGSectionTitle">&nbsp;Transfer History </div></td>
			  <td class="gSGSectionTitle">
				
				<div align="right" class="gSGSectionTitle">
				  Filter:
				  <select name="bids_type" onChange="changeBitsType(this)" style="display:none " >
				    <option value="" {ALL_SELECTED}>All</option>
				    <option value="0" {SUCCESSFUL_BIDS_SELECTED}>Successful Bids</option>
				    <option value="1" {UNSUCCESSFUL_BIDS_SELECTED}>Unsuccessful Bids</option>
				    <option value="2" {FAILED_BIDS_SELECTED}>Failed Bids</option>
				  </select>
				  
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
                    <td class="gSGSectionColumnHeadings">&nbsp;Source Team</td>
                    <td class="gSGSectionColumnHeadings">Target Team</td>
                    <td class="gSGSectionColumnHeadings" nowrap title="Player Name">Player</td>
                    <td class="gSGSectionColumnHeadings" >Pos</td>
                    <td class="gSGSectionColumnHeadings">Price</td>
                    <td class="gSGSectionColumnHeadings">Time</td>
                    <td class="gSGSectionColumnHeadings">Type</td>
                  </tr>
                  <!-- BEGIN transfer_history -->
				  <tr style="display:{LIST_SEPARATOR}">
				    <td colspan="8" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
				  </tr>
				  
                  <tr class="{TRAN_TR_CLASS}" >
                    <td class="BlackText">&nbsp;<a href="/fmol/page/info/club_info.php?team_id={S_TEAM_ID}" >{S_TEAM_NAME}</a></td>
                    <td class="BlackText"><a href="/fmol/page/info/club_info.php?team_id={T_TEAM_ID}" >{T_TEAM_NAME}</a></td>
                    <td class="BlackText"><a href="/fmol/page/players/player_info.php?player_id={PLAYER_ID}" >{PLAYER_NAME}</a></td>
                    <td class="BlackText">{POS}</td>
                    <td class="BlackText">{PRICE}</td>
                    <td class="BlackText">{TIME}</td>
                    <td class="BlackText">{TYPE}</td>

                  </tr>
                  <!-- END transfer_history -->
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

