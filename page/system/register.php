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


//----------------------------------------------------------------------------
// validate the user and passwd
//----------------------------------------------------------------------------

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register']))   //login is the name of the submit button
{
    $validate = & new Validate();
    $user_name = sql_quote($_POST['user_name']);
    $email = sql_quote($_POST['email']);
    $confirm_email = sql_quote($_POST['confirm_email']);

    if($user_name == '')
    {
        $errorMessage = 'Please input the user name';
    }
    else if( !$validate->string( $user_name, array('format'=>VALIDATE_ALPHA . VALIDATE_NUM . VALIDATE_SPACE ) ) ) 
	{ 
		//throw some user_name error 
		$errorMessage = 'Only alphanumeric and space characters are allowed in the user name';
	}
    else if($email == '' || !$validate->email($email))
    {
        $errorMessage = 'Please input the email of correct format';
    }
    else if($confirm_email == '' || !$validate->email($confirm_email))
    {
        $errorMessage = 'Please input the confirm email of correct format';
    }
    else if($email != $confirm_email)
    {
        $errorMessage = 'The email and the confirm email is not the same';
    }
	else if (sql_quote($_POST['check_number']) != sql_quote($_SESSION['login_check_number'])) {
	    $errorMessage = 'Please input the right check_number';
	}
    else
    {
	    $query = sprintf(" select 1 from user_info where name='%s' ", $user_name);
			
		$rs = &$db->Execute($query);
	    if (!$rs) {
			$errorMessage = 'Database error'; 
	    }
	    else if ($rs->RecordCount() > 0) {
	        $errorMessage = sprintf("the user name : '%s' has exists", $user_name);
	    }
		else {			
		    // get current date
		    $reg_time = date('y-m-d H:m:s'); 

		    // create a user
			srand(time() + 0); 
		    $passwd = md5(rand());
			$o_passwd = substr($passwd, 0, 8);
			$passwd = md5($o_passwd);
			
			// begin transaction
			$db->BeginTrans();
			
			$returnValue = insertIntoUser_info($db, $user_name, $passwd, $o_passwd, $reg_time, $email);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$errorMessage = $returnValue;
				showTheTemplate($tpl, $TPL_PATCH, $PAGE_TITLE, $errorMessage,
						 $user_name, $email, $confirm_email);
				exit (0);
			}
			
			// 注释以下这段代码，因为发现该函数没什么用
			/*			
			$returnValue = updateUser_info($db, $user_name, $passwd);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$errorMessage = $returnValue;
				showTheTemplate($tpl, $TPL_PATCH, $PAGE_TITLE, $errorMessage,
						 $user_name, $email, $confirm_email);
				exit (0);
			}
			*/
			
			$user_id = 0;
			$returnValue = selectUserIdFromUser_info($db, $user_name, $passwd, $user_id);
			if ($returnValue != "0") {
				$db->RollbackTrans(); 
				$errorMessage = $returnValue;
				showTheTemplate($tpl, $TPL_PATCH, $PAGE_TITLE, $errorMessage,
						 $user_name, $email, $confirm_email);
				exit (0);
			}
			
			// send mail
			$mail = new Email();
			$mail->setTo($email); // the address to receive the email
			$mail->setFrom("fmol@fmol.cn"); // the email source
			$mail->setSubject('activate letter from FMOL') ; // the subject 
			$mail->setText("hello") ;
			$HTMLContent = "<html><a href=\"http://192.168.1.10:8080/fmol/page/system/redirect.html?$passwd&$user_id\">";
			$HTMLContent .= "click this link to activate you account in FMOL</a></html>";
			$mail->setHTML($HTMLContent) ;// send by html
						
			if ( $mail->send() ) {
				// commit transaction
				$db->CommitTrans();
				
			    // $welcome_info is a php variable in the file "welcome.php"
		    	$welcome_info = "The email has been sent to your email, <br>";
				$welcome_info .= "please receive your email and click the link inside, <br>"; 
				$welcome_info .= "then the user name and the password will be send to your email.<br><br>";
				$welcome_info .= "Thank you for using fmol! <a href=\"/fmol/index.php\">back</a><br>";
				require_once (DOCUMENT_ROOT . "/page/system/welcome.php");
				
				// go back to the page "index.php"
				//echo "<meta http-equiv='refresh' content='5;URL=/fmol/index.php'>";
				exit(0);
			}
			else {
				$db->RollbackTrans(); 
				$errorMessage = 'fail to send the email';
				showTheTemplate($tpl, $TPL_PATCH, $PAGE_TITLE, $errorMessage,
						 $user_name, $email, $confirm_email);
				exit (0);
			}
		}
    }
} 

//----------------------------------------------------------------------------
// print out the data into the templates and show them
//----------------------------------------------------------------------------

$tpl = new HTML_Template_ITX($TPL_PATCH); 
$tpl->loadTemplatefile("register.tpl.php", true, true); 

// set the variable of the template
$tpl->setVariable("TITLE", $PAGE_TITLE);
$tpl->setVariable("ACTION", $_SERVER['PHP_SELF']);
$tpl->setVariable("ERROR_MESSAGE", $errorMessage);

// replay the info which the user has inputted
$tpl->setVariable("USER_NAME_VALUE", $_POST['user_name']);
$tpl->setVariable("EMAIL_VALUE", $_POST['email']);
$tpl->setVariable("CONFIRM_EMAIL_VALUE", $_POST['confirm_email']);


// show the template
$tpl->show();


//----------------------------------------------------------------------------	
// 使用到的函数
//----------------------------------------------------------------------------

/**
 * insert a record into user_info
 *
 * @param [db]						database
 * @param [user_name]				user_name
 * @param [passwd]					passwd
 * @param [o_passwd]				o_passwd	
 * @param [reg_time]				reg_time	
 * @param [email]					email
 *
 * @return:
 * "0" - success
 * error msg - failure
 */	
function insertIntoUser_info($db, $user_name, $passwd, $o_passwd, $reg_time, $email) 
{
	$query = sprintf("insert into user_info(name, passwd, o_passwd, reg_time, email) " .
					" values('%s', '%s', '%s', '%s', '%s') ", 
					$user_name, $passwd, $o_passwd, $reg_time, $email);
					
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	
	return "0";
}

/**
 * update the record of user_info
 *
 * @param [db]						database
 * @param [user_name]				user_name
 * @param [passwd]					passwd
 *
 * @return:
 * "0" - success
 * error msg - failure
 */	
function updateUser_info($db, $user_name, $passwd) 
{
	$query = sprintf("update user_info set user_id=id " .
					 " where name='%s' and passwd='%s' ", 
					 $user_name, $passwd);
					
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() < 0 ){
		return "There is not this right record in the database.";
	}
	
	return "0";
}

/**
 * update the user_id from user_info
 *
 * @param [db]						database
 * @param [user_name]				user_name
 * @param [passwd]					passwd
 *
 * @return:
 * "0" - success
 * error msg - failure
 */	
function selectUserIdFromUser_info($db, $user_name, $passwd, &$user_id) 
{
	$query = sprintf(" select user_id from user_info " .
					 " where name='%s' and passwd='%s' ", 
					 $user_name, $passwd);
					
	$rs = &$db->Execute($query);
	if (!$rs) {
		return "Database error.";
	}
	else if ($rs->RecordCount() <= 0 ){
		return "There is not this right record in the database.";
	}
	
	if ($rs->RecordCount() > 0) {
		$user_id = $rs->fields['user_id'];
		return "0";
	}		
}


/**
 * show the template
 *
 * @param [tpl]						tpl
 * @param [TPL_PATCH]				TPL_PATCH
 * @param [PAGE_TITLE]				PAGE_TITLE
 * @param [errorMessage]			errorMessage
 * @param [user_name]				user_name
 * @param [email]					email
 * @param [confirm_email]			confirm_email
 *
 * @return:
 */	
function showTheTemplate($tpl, $TPL_PATCH, $PAGE_TITLE, $errorMessage,
						 $user_name, $email, $confirm_email) 
{
	$tpl = new HTML_Template_ITX($TPL_PATCH); 
	$tpl->loadTemplatefile("register.tpl.php", true, true); 
	
	// set the variable of the template
	$tpl->setVariable("TITLE", $PAGE_TITLE);
	$tpl->setVariable("ACTION", $_SERVER['PHP_SELF']);
	$tpl->setVariable("ERROR_MESSAGE", $errorMessage);
	
	// replay the info which the user has inputted
	$tpl->setVariable("USER_NAME_VALUE", $user_name);
	$tpl->setVariable("EMAIL_VALUE", $email);
	$tpl->setVariable("CONFIRM_EMAIL_VALUE", $confirm_email);
	
	
	// show the template
	$tpl->show();	
}

?>


