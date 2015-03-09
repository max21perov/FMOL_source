<?php 


function goBackInTime($time, $backCount)
{
	echo "<script>setTimeout('history.go($backCount)', $time); </script>";
	exit (0);
}

function goToPageInTime($time, $pageURL) 
{
	// go back to the page "friendly_list.php"
	echo "<meta http-equiv='refresh' content='$time;URL=$pageURL'>";
	exit(0);
}

function goToPageByPost($pageURL, $params)
{
	echo "<form name='opeForm' action='$pageURL' method=post>";
	foreach ($params as $key => $value){
		echo "<input type=hidden name='$key' value='$value'>";
	}
	echo "</form>";
	echo "<script>document.opeForm.submit();</script>";
}
?>
