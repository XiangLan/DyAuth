<?php
	session_start();
	if(!isset($_SESSION["phone_num"])){
		echo "-1";
		die();
	}
	require_once('dbconfig.php');

	$activity_id = $_SESSION['activity_id'];
	$phone_num = $_SESSION["phone_num"];
	$domain = $_SESSION["domain"];
	$answer=$_POST['answer'];
	$correctness = false;
	//connect
	$con=mysql_connect($db_host,$db_user,$db_pass) OR die('cannot connect!'.mysql_error());
	if(!$con)
	{
		die('Could not connect: '.mysql_error());
	}
	//select db
	mysql_select_db($db_name,$con);
	//根据手机号找到当前用户的Id
	$sql = "SELECT * FROM user_info WHERE phone_num = '$phone_num'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$user_id = $row['user_id'];
		$sql = "SELECT R.question_id, A.activity_content FROM activity_info A, q_a_relation R WHERE A.activity_id = '$activity_id' AND R.activity_id = '$activity_id'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$correct_answer = $row['activity_content'];
		$question_id = $row['question_id'];
		if($correct_answer == $answer)
		{
			$correctness = true;
			$json_answer['correctness']=true;
			echo json_encode($json_answer);
		}
		else{
			$correctness = false;
			$json_answer['correctness']=false;
			echo json_encode($json_answer);
		}	
		$sql="INSERT INTO answer_info (third_party_name,question_id,user_id,answer_content,correct_answer,correctness) VALUES ('$domain','$question_id','$user_id','$answer','$correct_answer','$correctness')";
		mysql_query($sql);
	}else{
		echo '-1';
	}
	mysql_close($con);
?>