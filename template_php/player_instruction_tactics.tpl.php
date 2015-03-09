{SPACE}
<table  width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
  	<td class="gSGSectionColumnHeadings" colspan="2">&nbsp;Player Instruction</td>
  </tr>
  
  <tr class="gSGRowEven_input">
    <td align="right" nowrap  title="Try Forward Run">Forward Run:&nbsp;</td>
    <td nowrap style="width:130px">
      <select style="width:125px " name="forward_run_{POP_INDEX}" >
	  	<!-- BEGIN forward_run_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END forward_run_select -->
      </select>
    </td>
  </tr>
  <tr class="gSGRowEven_input">
    <td align="right" title="Try Run With Ball"> Run With Ball:&nbsp;</td>
    <td>
      <select style="width:125px " name="run_with_ball_{POP_INDEX}">
	  	<!-- BEGIN run_with_ball_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END run_with_ball_select -->
      </select>
    </td>
  </tr>
  <tr class="gSGRowEven_input">
    <td align="right" title="Try Long Shot">Long Shot:&nbsp;</td>
    <td>
      <select style="width:125px " name="long_shot_{POP_INDEX}">
	  	<!-- BEGIN long_shot_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END long_shot_select -->
      </select>
    </td>
  </tr>
  <tr class="gSGRowEven_input">
    <td align="right" title="Try Hold The Ball"> Hold The Ball:&nbsp;</td>
    <td>
      <select style="width:125px " name="hold_the_ball_{POP_INDEX}">
	  	<!-- BEGIN hold_the_ball_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END hold_the_ball_select -->
      </select>
    </td>
  </tr>
  <tr class="gSGRowEven_input">
    <td align="right" title="Try Through Pass" nowrap>Through Pass:&nbsp;</td>
    <td>
      <select style="width:125px " name="through_pass_{POP_INDEX}">
	  	<!-- BEGIN through_pass_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END through_pass_select -->
      </select>
    </td>
  </tr>
  <tr class="gSGRowEven_input" >
    <td align="right" title="Try Crossing">Crossing:&nbsp;</td>
    <td>
      <select style="width:125px " name="crossing_{POP_INDEX}">
	  	<!-- BEGIN crossing_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END crossing_select -->
      </select>
    </td>
  </tr>
  <tr class="gSGRowEven_input" >
    <td align="right" title="Try Crossing">Pressing:&nbsp;</td>
    <td>
      <select style="width:125px " name="pressing_{POP_INDEX}">
	  	<!-- BEGIN pressing_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END pressing_select -->
      </select>
    </td>
  </tr>
  <tr class="gSGRowEven_input" >
    <td align="right" title="Try Crossing">Tackling:&nbsp;</td>
    <td>
      <select style="width:125px " name="tackling_{POP_INDEX}">
	  	<!-- BEGIN tackling_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END tackling_select -->
      </select>
    </td>
  </tr>
  <tr class="gSGRowEven_input" >
    <td align="right" title="Try Crossing">Passing Style:&nbsp;</td>
    <td>
      <select style="width:125px " name="passing_style_{POP_INDEX}">
	  	<!-- BEGIN passing_style_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END passing_style_select -->
      </select>
    </td>
  </tr>

  <tr bgcolor="#e0dfe3" >
  	<td colspan="2" height="2"></td>
  </tr>
  
  <tr >
  <td colspan="2">
  <table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr class="gSGRowEven_input">
    <td align="center">reset&nbsp;
	<select style="width:55px " id="reset_select_{POP_INDEX}">
	  	<!-- BEGIN reset_select -->
        <option value="{OPTION_VALUE}" {OPTION_SELECTED}>{OPTION_TEXT}</option>
        <!-- END reset_select -->
      </select>&nbsp;
	<input type="button" class="button" value="reset" onclick="resetPlayerInstruction('{POP_INDEX}')" /> </td>
  </tr>
</table>
  </td>
  </tr>

  <tr bgcolor="#e0dfe3" >
  	<td colspan="2" height="2"></td>
  </tr>
  
</table>
