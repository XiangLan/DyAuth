<?php
if(!isset($_GET["domain"])||!isset($_GET["user_name"])){
	echo "-2";
	die();
}

require_once('dbconfig.php');
require_once('getPhoneNumber.php');

session_start();
$user_name=$_GET['user_name'];	
$domain=$_GET['domain'];
//connect
$con=mysql_connect($db_host,$db_user,$db_pass) OR die('cannot connect!'.mysql_error());
if(!$con)
{
	die('Could not connect: '.mysql_error());
}
//select db
mysql_select_db($db_name,$con);

$phone_num = getPhone_num($domain,$user_name);

if(0==$phone_num) {
	echo "-1";
	mysql_close($con);
	die();
}

$sql = "SELECT A.answer_id FROM answer_info A ,user_info U WHERE U.phone_num = '$phone_num' AND A.user_id = U.user_id AND A.login_flag = 0 AND A.correctness = 1 AND UNIX_TIMESTAMP(A.time) >= ".(time()-60);
$result = mysql_query($sql);
if(mysql_num_rows($result)>0) {
	$answer_id = mysql_fetch_row($result)[0];
	$sql = "UPDATE answer_info SET login_flag = 1 WHERE answer_id = '$answer_id'";
	$result = mysql_query($sql);
	echo "1";
}
else echo "0";

mysql_close($con);
?>