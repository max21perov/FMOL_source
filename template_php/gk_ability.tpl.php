<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td height="1">{SPACE}</td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="3" cellpadding="1">
  <tr>
    <td colspan="2"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="5" class="gSGSectionColumnHeadings" > &nbsp;Common</td>
          </tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;name</td>
            <td align="right">{PLAYER_NAME}&nbsp;</td>
            <td rowspan="8" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
            <td >&nbsp;position</td>
            <td align="right">{POSITION}&nbsp;</td>
          </tr>
        
		
          <tr>
            <td colspan="5" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;prefer_foot</td>
            <td align="right">{PREFER_FOOT}&nbsp;</td>
            <td>&nbsp;type</td>
            <td align="right">{PLAYER_OR_GK}&nbsp;</td>
          </tr>
			
			
          <tr>
            <td colspan="5" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;cloth number</td>
            <td align="right">{CLOTH_NUMBER}&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
          </tr>
			
			
          <tr>
            <td colspan="5" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
          </tr>
          <tr class="gSGRowOdd">
            <td>&nbsp;age</td>
            <td align="right">{AGE}&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
          </tr>
			
			
        </table></td>
      </tr>
    </table></td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2" class="gSGSectionColumnHeadings" >Physical</td>
            </tr>
            <tr class="gSGRowOdd">
              <td>&nbsp;agility</td>
              <td align="right">{AGILITY}&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
            </tr>
            <tr class="gSGRowOdd">
              <td>&nbsp;reflex</td>
              <td align="right">{REFLEX}&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
            </tr>
            <tr class="gSGRowOdd">
              <td>&nbsp;height</td>
              <td align="right">{HEIGHT}&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2" class="gSGSectionColumnHeadings" >&nbsp;Technical</td>
            </tr>
            <tr class="gSGRowOdd">
              <td>&nbsp;handing</td>
              <td align="right">{HANDING}&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
            </tr>
            <tr class="gSGRowOdd">
              <td>&nbsp;rushing out</td>
              <td align="right">{RUSHING_OUT}&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
            </tr>
            <tr class="gSGRowOdd">
              <td>&nbsp;positioning</td>
              <td align="right">{POSITIONING}&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" class=cBBottom2><img height=1 src="/fmol/images/blank.gif"></td>
            </tr>
            <tr class="gSGRowOdd">
              <td>&nbsp;aerial ability</td>
              <td align="right">{AERIAL_ABILITY}&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#0069b9">
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2" class="gSGSectionColumnHeadings" >&nbsp;Mental</td>
            </tr>
            <tr class="gSGRowOdd">
              <td>&nbsp;judgment</td>
              <td align="right">{JUDGMENT}&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  
  
	<tr>
	  <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
		  <form name="save_form" action="/fmol/page/youth/handle_youth.php?myaction=elevateNewPlayer" method="post">
	
		  <input type="hidden" name="player_id" value="{PLAYER_ID}">
		  <input type="hidden" name="return_page_url" value="{RETURN_PAGE_URL}">
		  
        <tr>
          <td>&nbsp;</td>
          <td height="30" style="width:100px ">
		  <input type="submit" name="save" value="elevate" class="button" style="width:100px " /> </td>
          <td>&nbsp;</td>
        </tr>
		
		  </form>
      </table></td>
  </tr>
  
  
</table>
</td>
  </tr>
</table>
