<?php
	function toTimeBlock($timeStamp)
	{
		date_default_timezone_set("Asia/Hong_Kong");
		$blockNames = array( " before dawn", " in the morning" , " in the afternoon" , " at night" );
		$unix_time_stamp = strtotime($timeStamp);
		$date = date( "d" , $unix_time_stamp);
		$date0 = date("d",time());
		$hour = intval(date( "H" , $unix_time_stamp));
		if($date == $date0)
			return $blockNames[intval($hour/6)]." today";
		else
			return $blockNames[intval($hour/6)]." yesterday";
	}
	require_once('dbconfig.php');

	session_start();
	$SL = $_POST['SL'];

	$con=mysql_connect($db_host,$db_user,$db_pass) OR die('cannot connect!'.mysql_error());
	if(!$con)
	{
		die('Could not connect: '.mysql_error());
	}
	//select db
	mysql_select_db($db_name,$con);

	if(isset($_SESSION["phone_num"])){
		$phone_num = $_SESSION["phone_num"];
	}

	//根据手机号找到当前用户的Id
	$sql = "SELECT * FROM user_info WHERE phone_num = '$phone_num'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$user_id = $row['user_id'];
		switch($SL)
		{
			case 1://在M1中随机选一种。
				//$sql = "SELECT * FROM activity_info WHERE app_type = 'location' AND user_id='$user_id' AND activity_content !='' && UNIX_TIMESTAMP(time) >= ".(time()-360000*10);
				//这里便于测试把时间限制去掉了。
				$sql = "SELECT A.time, A.activity_id , A.activity_name , R.question_id FROM activity_info A,q_a_relation R WHERE app_type = 'location' AND user_id='$user_id' AND activity_content !='' AND A.activity_id = R.activity_id";
				$result = mysql_query($sql);
				$length = mysql_num_rows($result);
				if($length>0){
				for($i=0;$i<$length;$i++)
				{				
					$row = mysql_fetch_array($result,MYSQL_ASSOC);
					
					$question_array[$i]['time'] = $row['time'];//date("Y-m-d H:i:s",$row['time']);
					$question_array[$i]['activity_id'] =  $row['activity_id'];
					$question_array[$i]['activity_name'] =  $row['activity_name'];
					$question_id = $row['question_id'];
					//取出问题的内容
					$sql = "SELECT * FROM question_info WHERE question_id = '$question_id'";
					$result2 = mysql_query($sql);
					$row = mysql_fetch_array($result2,MYSQL_ASSOC);
					$question_array[$i]['question_content'] = $row['question_content'];
				}
					//从这些问题中随机选择一个
					$random = rand(0,$length-1);
					$question = $question_array[$random]['question_content']. " in " . $question_array[$random]['activity_name'] .toTimeBlock($question_array[$random]['time'])."?";
					$question_json['question']=$question;
					//$question_json['activity_id']=$question_array[$random]['activity_id'];
					//set the session variable
					$_SESSION['activity_id']=$question_array[$random]['activity_id'];
					$question_json['isQuestion']="1";//表示有M1的问题
					//var_dump($question_json);
					echo json_encode($question_json);
				}
				else{//没有M1的问题
					$question_json['question']='';
					$question_json['activity_id']='';
					$question_json['isQuestion']="0";//表示没有M1的问题				
					echo json_encode($question_json);			
				}
				break;
			case 2://在M2中选一种。     ？？？？？？？？？？？？？你的笔记我看不大清楚了，这边很好改。
				//$sql = "SELECT * FROM activity_info WHERE app_type != 'location' AND user_id='$user_id' AND activity_content !='' && UNIX_TIMESTAMP(time) >= ".(time()-3600*10);
				$sql = "SELECT A.time, A.activity_id , A.activity_name, R.question_id FROM activity_info A,q_a_relation R WHERE app_type != 'location' AND user_id='$user_id' AND activity_content !='' AND A.activity_id = R.activity_id"; //这里便于测试，把时间限制去了。
				$result = mysql_query($sql);
				$length = mysql_num_rows($result);
				if($length>0){
				for($i=0;$i<$length;$i++)
				{				
					$row = mysql_fetch_array($result,MYSQL_ASSOC);
					
					$question_array[$i]['time'] = $row['time'];//date("Y-m-d H:i:s",$row['time']);
					$question_array[$i]['activity_id'] =  $row['activity_id'];
					$question_array[$i]['activity_name'] = $row['activity_name'];
					$question_id = $row['question_id'];
					//取出问题的内容
					$sql = "SELECT * FROM question_info WHERE question_id = '$question_id'";
				
					$result2 = mysql_query($sql);
					$row = mysql_fetch_array($result2,MYSQL_ASSOC);
					$question_array[$i]['question_content'] = $row['question_content'];
				}
					//从这些问题中随机选择一个
					$random = rand(0,$length-1);
					$question = $question_array[$random]['question_content'] . " in " . $question_array[$random]['activity_name'] . toTimeBlock($question_array[$random]['time']) ."?";
					$question_json['question']=$question;
					//set the session variable
					$_SESSION['activity_id']=$question_array[$random]['activity_id'];
					//$question_json['activity_id']=$question_array[$random]['activity_id'];
					$question_json['isQuestion']="1";//表示有M2的问题
					echo json_encode($question_json);
				}
				else{//没有M2的问题
					$question_json['question']='';
					//$question_json['activity_id']='';
					$question_json['isQuestion']="0";//表示没有M2的问题				
					echo json_encode($question_json);			
				}
				break;
			case 3://在M1与M2中各选一种。
		
				break;
			case 4://在M1与M2中各选两项。
			
				break;
			case 5://在M1与M2与M3中各选一项。
			
				break;
			default: //case1;
				break;
		}
	}else{
		echo "-1";//表示使用该号码的用户不存在
	}
	mysql_close($con);
?>