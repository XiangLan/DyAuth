<?php
	session_start();
	require_once('dbconfig.php');	
	$con=mysql_connect($db_host,$db_user,$db_pass) OR die('cannot connect!'.mysql_error());
	if(!$con)
	{
		die('Could not connect: '.mysql_error());
	}
	//select db
	mysql_select_db($db_name,$con);
	
	function generateQuestion($user_id)
	{
		$choice = rand(0,1);
		if($choice == 0)//location
		{
			return locationQuestion($user_id);
		}
		else //act
		{
			return ActQuestion($user_id);
		}
	}
	function locationQuestion($user_id)
	{
		$qry = "SELECT question_id, question_content FROM question_info WHERE question_type='location'";
		$result = mysql_query($qry) or die(mysql_error());

		$length = mysql_num_rows($result);
		$questionList = array();
		for ($i=0; $i < $length; $i++) { 
			$questionList[$i] = mysql_fetch_assoc($result);
		}

		$choice = rand(0,$length-1);
		$questionList[$choice]["isQuestion"] = 1;
		$questionList[$choice]["question_content"] .= " now ?";
		$_SESSION["question_id"] = $questionList[$choice]["question_id"];
		$_SESSION["mechanism_type"] = 1;
		return $questionList[$choice];
	}
	function ActQuestion($user_id)
	{
		$qry = "SELECT * FROM activity_info WHERE user_id='$user_id' && activity_content is NULL && app_type != 'location' && UNIX_TIMESTAMP( time ) >= ".(time()-360000);
		
		$result = mysql_query($qry) or die(mysql_error());

		$length = mysql_num_rows($result);
		if($length==0) return locationQuestion($user_id);

		$activityList = array();
		for ($i=0; $i < $length; $i++) { 
			$activityList[$i] = mysql_fetch_assoc($result);
		}

		$choice = rand(0,$length-1);
		$selectedActivity = $activityList[$choice];

		$qry = "SELECT * FROM question_info WHERE mechanism_type=2 && question_type='".$selectedActivity["app_type"]."'";
		
		$result = mysql_query($qry) or die(mysql_error());

		$length = mysql_num_rows($result);
		$questionList = array();
		for ($i=0; $i < $length; $i++) { 
			$questionList[$i] = mysql_fetch_assoc($result);
		}

		$choice = rand(0,$length-1);

		$questionList[$choice]["isQuestion"] = 1;
		$questionList[$choice]["question_content"] .= " in ".$selectedActivity["activity_name"]." just now ?";
		$_SESSION["mechanism_type"] = 2;
		$_SESSION["activity_id"] = $selectedActivity["activity_id"];
		$_SESSION["question_id"] = $questionList[$choice]["question_id"];
		return $questionList[$choice];
	}
	//real code begins here.

	if(isset($_POST["user_id"]) && $_POST["user_id"]!="")
	{
		print json_encode(generateQuestion($_POST["user_id"]));
	}
	else
		print "user_id cannot be null";

?>