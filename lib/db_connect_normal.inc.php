<?php

define('DB_HOST', "localhost");  // local database
define('DB_USER', "fmolphp");
define('DB_PASSWORD', "123");
define('DB_DATABASE', "fmol");



$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) ;  //or die("Could not connect: " . mysql_error()); 
mysql_select_db(DB_DATABASE);



mysql_query('set names "utf8"');  // added to show the chinese character in the page
mysql_query('SET CHARACTER SET "utf8"');  // added to show the chinese character in the page
mysql_query('SET COLLATION_CONNECTION="utf8_general_ci"');  // added to show the chinese character in the page




?> 
