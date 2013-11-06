<?php
session_start();
if(!isset($_SESSION["phone_num"])){
	die();
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Amazon.com</title>
	<link href="css/bootstrap.css" rel="stylesheet" media="screen" />
	<script src="js/jquery-1.10.2.min.js"></script>
	<script src="js/DyAuth.js"></script>
	<style>
	html,body{height:100%}
	body{
		color: #333333;
		font-size:16px;
	}
	input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input{
		font-size:16px;
	}
	label{
		cursor:default;
		font-size:16px;
	}
	form{
		background-color: white;
		width:500px;
		margin:0 auto;
		padding:20px;
		border-radius:15px;
		position: relative;
		top: -32px;
	}
	h1{
		margin:0;
		padding:30px;
		text-align:center;
		font-size:100px;
		line-height:150px;
		font-family:cursive;
	}
	h2{
		background-color: #006dcc;
		color: white;
		width: 510px;
		margin: 0 auto;
		line-height: 80px;
		padding-left: 30px;
		padding-bottom: 20px;
		border-radius: 15px;
	}
	</style>
</head>
<body>
	<h4>DyAuth</h4>
	<div class="control-group" id="block-q">
		<label class="control-label" for="question">Question:</label>
		<div class="controls" style="padding-top:5px;">
			<b id="question" title="click to change a question..."></b>
		</div>
	</div>
	<div class="control-group" id="block-a">
		<label class="control-label" for="answer">Your answer:</label>
		<div class="controls" id="answer-control">
			<input type="text" id="answer" placeholder="Answer here...">
		</div>
	</div>
	<button class="btn" onclick="requestQuestion(2)">request another question</button>
</body>
</html>