<?php

/**
 * sanitize input data before using it into a sql query
 */
function sql_quote( $value ) 
{ 
	if( get_magic_quotes_gpc() ) 
	{ 
		$value = stripslashes( $value ); 
	} 
	
	//check if this function exists 
	if( function_exists( "mysql_real_escape_string" ) ) 
	{ 
		// I don't know whether it is good to connect database here
		mysql_connect("localhost", "fmolphp", "123") ;  //or die("Could not connect: " . mysql_error()); 
		
		$value = mysql_real_escape_string( $value ); 
	} 
	//for PHP version < 4.3.0 use addslashes 
	else 
	{ 
		$value = addslashes( $value ); 
	} 
	
	return $value; 
} 
	
	

?>
