<?php
	session_start();
	require_once('dbconfig.php');
	$phoneNumber=$_POST['phoneNumber'];	
	$password = $_POST['password'];
	//connect
	$con=mysql_connect($db_host,$db_user,$db_pass) OR die('cannot connect!'.mysql_error());
	if(!$con)
	{
		die('Could not connect: '.mysql_error());
	}
	//select db
	mysql_select_db($db_name,$con);
	
	//根据手机号找到当前用户的Id
	$sql = "SELECT * FROM user_info WHERE phone_num = '$phoneNumber' AND password = '$password'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)>0){
		echo '1';
	}else{
		echo "-1";//表示使用该号码的用户不存在
	}
	mysql_close($con);
?>