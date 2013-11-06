<?php
	function getPhone_num($domain , $user_name)
	{
		$sql = "SELECT U.phone_num FROM website_relation W ,user_info U WHERE W.domain = '$domain' AND W.user_name = '$user_name' AND W.user_id = U.user_id";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)>0){
			$row = mysql_fetch_row($result);
			return $row[0];
		}
		else return 0;
	}
?>