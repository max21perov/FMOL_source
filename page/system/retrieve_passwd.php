<?php
session_start();

// $document_root = $_SERVER['DOCUMENT_ROOT'] . "/fmol";
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/fmol");
require_once(DOCUMENT_ROOT . "/lib/ITX.php"); 
require_once(DOCUMENT_ROOT . "/lib/sql_quote.inc.php");
require_once(DOCUMENT_ROOT . "/lib/Validate.class.php");
require_once(DOCUMENT_ROOT . "/lib/Email.class.php");
require_once(DOCUMENT_ROOT . "/lib/db_connect.inc.php");
require_once(DOCUMENT_ROOT . "/lib/page_config.inc.php");


if($_SERVER["REQUEST_METHOD"] == "POST")   //check the page is request by method "POST"
{
    $validate = & new Validate();
    $user_name = sql_quote($_POST['user_name']);
	
	if($user_name == '') {
        $errorMessage = 'Please input the user name';
    }
    else if( !$validate->string( $user_name, array('format'=>VALIDATE_ALPHA . VALIDATE_NUM . VALIDATE_SPACE ) ) ) 
	{ 
		//throw some user_name error 
		$errorMessage = 'Only alphanumeric and space characters are allowed in the user name';
	}
	else if (sql_quote($_POST['check_number']) != sql_quote($_SESSION['login_check_number'])) {
		
	    $errorMessage = 'Please input the right check_number';
	}
	else {
	    $query = sprintf(" SELECT name, o_passwd, email FROM user_info where name='%s'",
          				 $user_name);
		//$query  = " SELECT name, o_passwd, email FROM user_info ";
        //$query .= " WHERE name='$user_name' "; // the passwd must be encrypted, will be done in future
		$rs = &$db->Execute($query);
	    if (!$rs) {
			$errorMessage = 'Database error'; 
	    }
		else if ($rs->RecordCount() <= 0) {
			$errorMessage = "There is not any user whose name is: $user_name. "; 
		}
		else {
			$user_name = $rs->fields['name'];
		    $o_passwd = $rs->fields['o_passwd'];
		    $email = $rs->fields['email'];
			
			$mail = new Email();
			$mail->setTo($email); // the address to receive the email
			$mail->setFrom("fmol@fmol.cn"); // the email source
			$mail->setSubject('your user name and password from FMOL') ; // the subject 
			$mail->setText("user_name: $user_name  passwd: $o_passwd") ;
			
			if ( $mail->send() ) {
				// $welcome_info is a php variable in the file "welcome.php"
				$welcome_info = "The user name and password has been sent to your email, <br>";
				$welcome_info .= "please receive your email and get them.<br>"; 
				require (DOCUMENT_ROOT . "/page/system/welcome.php");
				// go back to the page "index.php"
				echo "<meta http-equiv='refresh' content='3;URL=/fmol/index.php'>";
				exit;
			}
			else {
				$errorMessage = 'fail to send the email';
			}
		}
	}
	
}

//----------------------------------------------------------------------------
// print out the data into the templates and show them
//----------------------------------------------------------------------------

$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("retrieve_passwd.tpl.php", true, true); 

// set the variable of the template
$tpl->setVariable("TITLE", $PAGE_TITLE);
$tpl->setVariable("ACTION", $_SERVER['PHP_SELF']);
$tpl->setVariable("ERROR_MESSAGE", $errorMessage);

// replay the info which the user has inputted
$tpl->setVariable("USER_NAME_VALUE", $_POST['user_name']);

// show the template
$tpl->show();


?>
