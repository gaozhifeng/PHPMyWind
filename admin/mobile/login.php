<?php	if(!defined('IN_MOBILE')) exit('Request Error!'); ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<title>PHPMyWind 管理中心</title>
<link href="mobile/templates/style/mobile.css" rel="stylesheet" type="text/css" />
<script src="mobile/templates/js/jquery.min.js"></script>
<script>
function CheckForm()
{
	if($("#username").val() == "")
	{
		alert("请输入用户名！");
		$("#username").focus();
		return false;
	}
	if($("#password").val() == "")
	{
		alert("请输入密码！");
		$("#password").focus();
		return false;
	}
	if($("#question").val() != 0 && $("#answer").val() == "")
	{
        alert("请输入问题回答！");
        $("#answer").focus();
        return false;
    }

	$("form").submit();
}

$(function(){
	$(".loginForm input").keydown(function(){
		$(this).prev().hide();
	}).blur(function(){
		if($(this).val() == ""){
			$(this).prev().show();
		}
	});

	$(".loginBtn").click(function(){
		CheckForm();
	});

	$("#username").focus();
})
</script>
</head>

<body>
<div class="loginWarp">
	<div class="loginLogo">登录管理中心</div>
	<div class="loginForm">
		<form name="form" id="form" action="login.php" method="post">
			<div class="txtLine">
				<label>用户名</label>
				<input type="text" name="username" id="username" class="uname input" maxlength="20" />
			</div>
			<div class="txtLine">
				<label>密码</label>
				<input type="password" name="password" id="password" class="pwd input" maxlength="16" />
			</div>
			<div class="quesArea">
				<select name="question" id="question" class="question">
					<option value="0">无安全提问</option>
					<option value="1">母亲的名字</option>
					<option value="2">爷爷的名字</option>
					<option value="3">父亲出生的城市</option>
					<option value="4">你其中一位老师的名字</option>
					<option value="5">你个人计算机的型号</option>
					<option value="6">你最喜欢的餐馆名称</option>
					<option value="7">驾驶执照最后四位数字</option>
				</select>
				<div class="asLine">
					<label>回答</label>
					<input type="text" name="answer" id="answer" class="answer" />
				</div>
			</div>
			<div class="loginBtn">登 陆</div>
			<input type="hidden" name="dopost" value="login" />
		</form>
	</div>
	<div class="loginCopyright">© 2015 phpMyWind.com</div>
</div>
</body>
</html>
