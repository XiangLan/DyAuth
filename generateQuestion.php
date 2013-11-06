<?php

function generateQuestion($user_id)
{
	$choice = rand(0,1);
	//$choice = 1;
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
	$qry = "select question_id , question_content from question_info where mechanism_type=1";
	$result = mysql_query($qry) or die(mysql_error());

	$counter = 0;
	$array = array();
	while ( $arrayItem = mysql_fetch_assoc($result) ) {
		$array[$counter] = $arrayItem;
		$counter++;
	}
	$choice = rand(0,$counter-1);
	$array[$choice]["isQuestion"] = 1;
	$array[$choice]["question_content"] .= " now ?";
	$_SESSION["question_id_waiting"] = $array[$choice]["question_id"];
	$_SESSION["mechanism_type_waiting"] = 1;
	return $array[$choice];
}
function ActQuestion($user_id)
{
	$qry = "select * from activity_info where user_id=$user_id && question_id is NULL && UNIX_TIMESTAMP( time ) >= ".(time()-3600);
	$result = mysql_query($qry) or die(mysql_error());

	$counter = 0;
	$array = array();
	while ( $arrayItem = mysql_fetch_assoc($result) ) {
		$array[$counter] = $arrayItem;
		$counter++;
	}

	if($counter==0) return locationQuestion($user_id);

	$choice = rand(0,$counter-1);

	$selectedActivity = $array[$choice];

	$qry = "select question_id , question_content from question_info where mechanism_type=2 && question_type='".$selectedActivity["app_type"]."'";
	$result = mysql_query($qry) or die(mysql_error());

	$counter = 0;
	$array = array();
	while ( $arrayItem = mysql_fetch_assoc($result) ) {
		$array[$counter] = $arrayItem;
		$counter++;
	}
	$choice = rand(0,$counter-1);
	$array[$choice]["isQuestion"] = 1;
	$array[$choice]["question_content"] .= " now ?";
	$_SESSION["question_id_waiting"] = $array[$choice]["question_id"];
	$_SESSION["mechanism_type_waiting"] = 2;
	$_SESSION["activity_id_waiting"] = $selectedActivity["activity_id"];
	return $array[$choice];
}

?>
