<?php
session_start();
// config the session

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once(DOCUMENT_ROOT . "/lib/Validate.class.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");


//----------------------------------------------------------------------------==================	
// validate the user and passwd
//----------------------------------------------------------------------------==================	
if (isset($_GET["errorMessage"])) {
	$errorMessage = $_GET["errorMessage"];
}


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login']))   //login is the name of the submit button
{  
    $validate = & new Validate(); 
    $user_name = sql_quote($_POST['user_name']);
    $passwd = sql_quote($_POST['passwd']);

    if($user_name == '')
    {
        $errorMessage = 'Please input the user name';
    }
    else if($passwd == '')
    {
        $errorMessage = 'Please input the passwd';
    }
    else
    {
	    $md5_passwd = md5($passwd);  
	    $query = sprintf("select user_id, passwd, enable from user_info " .
						 " where name='%s' and passwd='%s' ",
          				 $user_name, $md5_passwd);
		
		   
		$rs = &$db->Execute($query);
	    if (!$rs) {
	        //print $db->ErrorMsg(); // Displays the error message if no results could be returned
			$errorMessage = 'Database error'; 
	    }
	    else {
	        if ($rs->RecordCount() > 0) {
			    $enable = $rs->fields['enable'];
				
				if ($enable != 1) {
				    $errorMessage = "The account '$user_name' has not been activated.<br>"; 
				    $errorMessage .= "Please check your email to activate this account."; 
				}
				else {
					$_SESSION['s_primary_user_id'] = $rs->fields['user_id'];
					$_SESSION['s_self_primary_user_id'] = $rs->fields['user_id'];
					$_SESSION['s_user_name'] = $user_name;
					$_SESSION['s_self_user_name'] = $user_name;
					//$_SESSION['s_passwd'] = $rs->fields['passwd'];
					$_SESSION['s_user_id'] = $rs->fields['user_id'];
					
					// include the file to associate the user->club->team->div
					require_once (DOCUMENT_ROOT . "/page/system/after_login.php");
					
					/*echo "<script>window.location =\"club_info.php\";</script>";*/
					//echo "<meta http-equiv='refresh' content='0;URL=/fmol/page/info/club_info.php'>";
					//exit (0);
				}
	        }
			else {
			    $errorMessage = 'The user or passwd is incorrect'; 
			}
	    }

    }
} 

//----------------------------------------------------------------------------==================	
// print out the data into the templates and show them
//----------------------------------------------------------------------------==================	

$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("login.tpl.php", true, true); 

// set the variable of the template
$tpl->setVariable("TITLE", $PAGE_TITLE);
$tpl->setVariable("ACTION", $_SERVER['PHP_SELF']);
$tpl->setVariable("ERROR_MESSAGE", $errorMessage);

// replay the info which the user has inputted
$tpl->setVariable("USER_NAME_VALUE", $_POST['user_name']);

// show the template
$tpl->show();

?>


