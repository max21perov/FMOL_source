			<!-- wait_friendly -->
			
			 <tr>
				<td colspan="7" class="cBBottom2"><img height=1 src="/fmol/images/blank.gif"></td>
			  </tr>
			  
            <form method="post" action="/fmol/page/friendly/handle_friendly.php?myaction=cancelWaitFriendly&next_page={NEXT_PAGE}&friendly_filter={FRIENDLY_FILTER}" onSubmit="javascript:return OnClickConfirm(this, 3)">
              <tr class="{WAIT_FRIENDLY_TR_CLASS}">
                <td>&nbsp;{STATUS}</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;?</td>
                <td>&nbsp;?</td>
                <td>{HOME_OR_AWAY}</td>
                <td>{MATCH_DATE}</td>
                <td>{MATCH_TIME}</td>
                <td align="right"><input type="hidden" name="friendly_pool_id" value="{FRIENDLY_POOL_ID}">
                    <input name="cancel_wait_friendly" type="submit" class="button" value="cancel">
                    <input name="details" type="button" class="button" value="details">
      &nbsp;</td>
              </tr>
            </form>