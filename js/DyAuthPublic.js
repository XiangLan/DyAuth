var auth={
	work:function (user_name) {
		auth.check(getDomain(),user_name);
	},
	popCheckWindow:function(){
	var url = "DyAuthPop.php";	
	var windowHeight = window.screen.height;
	var windowWidth = window.screen.width;
	var features = "location=no,fullscreen=no,channelmode=no,toolbar=no,width=400,height=400,top=windowHeight/2-200,left=windowWidth/2-200";
	window.open(url,'DyAuth verification',features);
	},
	check:function (domain , user_name) {
		if(user_name=="") return ;
		$.post('checkUser.php', { domain: domain , user_name: user_name }, function(data, textStatus, xhr) {
			if(data==1) auth.popCheckWindow();
		});
	}
}
function getDomain(){
	return "amazon.com";//===========
}
$(document).ready(function(){
	$("#inputUsername").blur(function(){
		inputUsername=$("#inputUsername").val();
		auth.work(inputUsername);
	});
});