<?php
	session_start();
	require_once('dbconfig.php');	
	$con=mysql_connect($db_host,$db_user,$db_pass) OR die('cannot connect!'.mysql_error());
	if(!$con)
	{
		die('Could not connect: '.mysql_error());
	}

	mysql_select_db($db_name,$con);

	function bindQandA( $q_id , $a_id )
	{
		$qry="DELETE FROM q_a_relation WHERE activity_id='$a_id'";
		$result = mysql_query($qry) or die(mysql_error());
		$qry="INSERT INTO q_a_relation ( question_id , activity_id ) VALUES ( '$q_id' , '$a_id' )";
		$result = mysql_query($qry) or die(mysql_error());
	}

	if(isset($_POST["user_id"]) && isset($_POST["answer"]) && isset($_SESSION["mechanism_type"]))
	{
		if(	$_SESSION["mechanism_type"] == 1 && isset($_SESSION["question_id"]))
		{
			$qry="INSERT INTO activity_info (user_id, app_type , activity_name , activity_content) VALUES ('".$_POST["user_id"]."', 'location' , 'location check' , '".$_POST["answer"]."')";
			//echo $qry;
			$result = mysql_query($qry) or die(mysql_error());
			bindQandA( $_SESSION["question_id"] , mysql_insert_id() );
			print json_encode(array('ok' => 1));
			unset($_SESSION["question_id"]);
		}
		else if ($_SESSION["mechanism_type"] == 2 && isset($_SESSION["activity_id"]) && isset($_SESSION["question_id"]))
		{
			$qry="UPDATE activity_info SET activity_content='".$_POST["answer"]."' WHERE activity_id=".$_SESSION["activity_id"];
			$result = mysql_query($qry) or die(mysql_error());			

			bindQandA( $_SESSION["question_id"] , $_SESSION["activity_id"] );

			print json_encode(array('ok' => 1));
			unset($_SESSION["activity_id"]);
			unset($_SESSION["question_id"]);
		}
		else
			print "mechanism_type is not legal";
	}
	else
		print "info is not complete";
	unset($_SESSION["mechanism_type"]);

?>
