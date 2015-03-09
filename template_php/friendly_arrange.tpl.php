<form method="post" action="/fmol/page/friendly/handle_friendly.php?myaction=friendlyArrange">
<table width="100%"  border="1" cellpadding="5" cellspacing="0" >
  <tr>
   {EMPTY_VALUE}{SPACE}
    <td bgcolor="#cccccc" class="BlackText"><p align="left">Step 1: Get Opponet</p>
      <p align="left">
          <input name="friendly_type" type="radio" value="app" checked>
        Team:
        <input name="opponent_team_id" type="text" value="{OPPONENT_TEAM_ID_VALUE}" class="inputField">
        (the team you challenge or put it's id here) . </p>
      <p align="left">
          <input type="radio" name="friendly_type" value="pool">
    Any team (Put into friendly pool).</p></td>
  </tr>
  <tr>
    <td bgcolor="#cccccc" class="BlackText"><p>Setp 2: Set Home or Away </p>
      <p>
          <input type="radio" name="home_or_away" value="home" checked>
        Home . </p>
        <p>
          <input type="radio" name="home_or_away" value="away">
        Away . </p>
    </td>
  </tr>
  <tr>
    <td bgcolor="#cccccc" class="BlackText"><p>Step 3: Check and submit</p>
        <p align="center">
          <input type="submit" name="friendly_arrange_submit" value="Check availably and submit" class="button">
      </p></td>
  </tr>
</table>
</form>
