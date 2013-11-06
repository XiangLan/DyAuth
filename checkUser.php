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

	$ret = array();

	if(0==$phone_num){
		$ret["pass"] = 0;
		$ret["key"] = 0;
	}
	else {
		$_SESSION["phone_num"] = $phone_num;
		$_SESSION["domain"] = $domain;
		$_SESSION["key"] = rand(1 , 999999);
		$ret["pass"] = 1;
		$ret["key"] = $_SESSION["key"];
	}
	echo json_encode($ret);
	mysql_close($con);
?>