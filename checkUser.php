<?php
	require_once('dbconfig.php');
	require_once('getPhoneNumber.php');
	
	session_start();
	$user_name=$_POST['user_name'];	
	$domain=$_POST['domain'];
	//connect
	$con=mysql_connect($db_host,$db_user,$db_pass) OR die('cannot connect!'.mysql_error());
	if(!$con)
	{
		die('Could not connect: '.mysql_error());
	}
	//select db
	mysql_select_db($db_name,$con);

	$phone_num = getPhone_num($domain,$user_name);

	if(0==$phone_num) 
		echo "-1";
	else {
		$_SESSION["phone_num"] = $phone_num;
		$_SESSION["domain"] = $domain;
		echo "1";
	}
	mysql_close($con);
?>