<?php	require_once(dirname(__FILE__).'/../include/common.inc.php');require_once(dirname(__FILE__).'/inc/manageui.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-6-1 12:20:58
person: Feng
**************************
*/


//初始化参数
$dopost = isset($dopost) ? $dopost : '';


//判断访问设备
if(IsMobile() && $dopost != 'login')
{
	header('location:default_mb.php?c=login');
	exit();
}


//判断登录请求
if($dopost == 'login')
{

	//初始化参数
	$username = empty($username) ? '' : $username;
	$password = empty($password) ? '' : md5(md5($password));
	$question = empty($question) ? 0  : $question;
	$answer   = empty($answer)   ? '' : $answer;


	//验证输入数据
	if($username == '' or
	   $password == '')
	{
		header('location:login.php');
		exit();
	}


	//删除已过时记录
	$dosql->ExecNoneQuery("DELETE FROM `#@__failedlogin` WHERE (UNIX_TIMESTAMP(NOW())-time)/60>15");


	//判断是否被暂时禁止登录
	$r = $dosql->GetOne("SELECT * FROM `#@__failedlogin` WHERE `username`='$username'");
	if(is_array($r))
	{
		$min = round((time()-$r['time']))/60;
		if($r['num']==0 and $min<=15)
		{
			ShowMsg('您的密码已连续错误6次，请15分钟后再进行登录！','login.php');
			exit();
		}
	}


	//获取用户信息
	$row = $dosql->GetOne("SELECT * FROM `#@__admin` WHERE `username`='$username'");


	//获取管理组信息
	if(isset($row) && is_array($row))
		$row2 = $dosql->GetOne("SELECT `groupsite`,`checkinfo` FROM `#@__admingroup` WHERE `id`=".$row['levelname']);


	//密码错误
	if(!is_array($row) or $password!=$row['password'])
	{
		$logintime = time();
		$loginip   = GetIP();

		$r = $dosql->GetOne("SELECT * FROM `#@__failedlogin` WHERE `username`='$username'");
		if(is_array($r))
		{
			$num = $r['num']-1;

			if($num == 0)
			{
				$dosql->ExecNoneQuery("UPDATE `#@__failedlogin` SET time=$logintime, num=$num WHERE username='$username'");
				ShowMsg('您的密码已连续错误6次，请15分钟后再进行登录！','login.php');
				exit();
			}
			else if($r['num']<=5 and $r['num']>0)
			{
				$dosql->ExecNoneQuery("UPDATE `#@__failedlogin` SET time=$logintime, num=$num WHERE username='$username'");
				ShowMsg('用户名或密码不正确！您还有'.$num.'次尝试的机会！','login.php');
				exit();
			}
		}
		else
		{
			$dosql->ExecNoneQuery("INSERT INTO `#@__failedlogin` (username, ip, time, num, isadmin) VALUES ('$username', '$loginip', '$logintime', 5, 1)");
			ShowMsg('用户名或密码不正确！您还有5次尝试的机会！','login.php');
			exit();
		}
	}


	//密码正确，查看登录问题是否正确
	else if($row['question'] != 0 && ($row['question'] != $question || $row['answer'] != $answer))
	{
		ShowMsg('登录提问或回答不正确！','login.php');
		exit();
	}


	//密码正确，查看是否被禁止登录
	else if($row['checkadmin'] == 'false')
	{
		ShowMsg('抱歉，您的账号被禁止登录！','login.php');
		exit();
	}


	//密码正确，查看管理组是否被禁用
	else if($row2['checkinfo'] == 'false')
	{
		ShowMsg('抱歉，您的所在的管理组被禁用！','login.php');
		exit();
	}


	//用户名密码正确
	else
	{
		$logintime = time();
		$loginip = GetIP();


		//删除禁止登录
		if(is_array($r))
		{
			$dosql->ExecNoneQuery("DELETE FROM `#@__failedlogin` WHERE `username`='$username'");
		}

		if(!isset($_SESSION)) session_start();

		//设置登录站点
		$r = $dosql->GetOne("SELECT `id`,`sitekey` FROM `#@__site` WHERE `id`=".$row2['groupsite']);
		if(isset($r['id']) &&
		   isset($r['sitekey']))
		{
			$_SESSION['siteid']  = $r['id'];
			$_SESSION['sitekey'] = $r['sitekey'];
		}
		else
		{
			$_SESSION['siteid']  = '';
			$_SESSION['sitekey'] = '';
		}

		//提取当前用户账号
		$_SESSION['admin']         = $row['username'];

		//提取当前用户权限
		$_SESSION['adminlevel']    = $row['levelname'];

		//提取上次登录时间
		$_SESSION['lastlogintime'] = $row['logintime'];

		//提取上次登录IP
		$_SESSION['lastloginip']   = $row['loginip'];

		//记录本次登录时间
		$_SESSION['logintime']     = $logintime;

		//更新登录数据
		$dosql->ExecNoneQuery("UPDATE `#@__admin` SET loginip='$loginip',logintime='$logintime' WHERE `username`='$username'");

		//更新操作日志
		SetSysEvent('login');

		//判断访问设备
		if(IsMobile())
		{
			$_SESSION['siteeq'] = 'mobile';
			header('location:default_mb.php?c=index');
			exit();
		}
		else
		{
			$_SESSION['siteeq'] = 'pc';
			header('location:default.php');
			exit();
		}
	}
}

//获取登录背景
function GetLoginBg()
{
	global $cfg_loginbgcolor,$cfg_loginbgimg,
	       $cfg_loginbgrepeat,$cfg_loginbgpos;

	//背景重复
	if($cfg_loginbgrepeat == 0)
		$loginbgrepeat = 'no-repeat';
	else if($cfg_loginbgrepeat == 1)
		$loginbgrepeat = 'repeat-x';
	else if($cfg_loginbgrepeat == 2)
		$loginbgrepeat = 'repeat-y';
	else
		$loginbgrepeat = 'no-repeat';

	//背景对齐
	if($cfg_loginbgpos == 0)
		$loginbgpos = 'left 0';
	else if($cfg_loginbgpos == 1)
		$loginbgpos = 'center 0';
	else if($cfg_loginbgpos == 2)
		$loginbgpos = 'right 0';
	else
		$loginbgpos = 'center 0';

	return 'style="background-color:'.$cfg_loginbgcolor.';background-image:url('.$cfg_loginbgimg.');background-repeat:'.$loginbgrepeat.';background-position:'.$loginbgpos.';"';
}


//更新操作日志
function SetSysEvent($m='', $cid='', $a='')
{
	global $dosql;

	$sql = "INSERT INTO `#@__sysevent` (uname, siteid, model, classid, action, posttime, ip) VALUE ('".$_SESSION['admin']."', '".$_SESSION['siteid']."', '$m', '$cid', '$a', '".time()."', '".GetIP()."')";

	//更新操作日志
	//一分钟内连续操作只记录一次
	$r = $dosql->GetOne("SELECT `posttime` FROM `#@__sysevent` WHERE `uname`='".$_SESSION['admin']."' AND `siteid`=".$_SESSION['siteid']." AND `model`='$m' ORDER BY id DESC");
	if(!isset($r['posttime']))
		$dosql->ExecNoneQuery($sql);

	else if(isset($r['posttime']) &&
	       ($r['posttime']<time()-60))
		$dosql->ExecNoneQuery($sql);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>PHPMyWind 管理中心</title>
<link href="templates/style/admin.css" rel="stylesheet" />
<script src="templates/js/jquery.min.js"></script>
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
}

$(function(){
	$(".loginForm input").keydown(function(){
		$(this).prev().hide();
	}).blur(function(){
		if($(this).val() == ""){
			$(this).prev().show();
		}
	});

	$("#username").focus(function(){
		$("#username").attr("class", "uname inputOn");
	}).blur(function(){
		$("#username").attr("class", "uname input");
	});

	$("#password").focus(function(){
		$("#password").attr("class", "pwd inputOn");
	}).blur(function(){
		$("#password").attr("class", "pwd input");
	});

	$("#question").focus(function(){
		$(".quesArea").attr("class", "quesAreaOn");
	}).blur(function(){
		$(".quesAreaOn").attr("class", "quesArea");
	});

	$("#answer").focus(function(){
		$(".quesArea").attr("class", "quesAreaOn");
	}).blur(function(){
		$(".quesAreaOn").attr("class", "quesArea");
	});

	$("#username").focus();
});
</script>
</head>

<body class="loginBody">
<div class="loginTop" <?php echo GetLoginBg(); ?>>
	<div class="logo"><a href="http://phpmywind.com" target="_blank"></a></div>
	<div class="text"><span class="note">
		<?php if(strstr(GetCurUrl(), '/admin/login')) echo '提示：您的后台路径为/<i>admin</i>/，建议更改为更加安全的路径！'; ?>
		</span>
		<?php if($cfg_author != '') echo '<i>Author : '.$cfg_author.'</i><span class="line">|</span>'; ?>
		访问 <i><a href="http://phpmywind.com/" target="_blank" class="link">phpMyWind.com</a></i><span class="line">|</span><a href="http://phpmywind.com/bbs/" target="_blank">帮助</a></div>
</div>
<div class="loginWarp">
	<div class="loginArea">
		<div class="loginHead"> </div>
		<div class="loginTxt">登录管理中心</div>
		<div class="loginForm">
			<form name="login" method="post" action="" onSubmit="return CheckForm()">
				<div class="txtLine">
					<label>用户名</label>
					<input type="text" name="username" id="username" class="uname input" maxlength="20" />
				</div>
				<div class="txtLine mar8">
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
				<div class="hr_1"></div>
				<input type="submit" class="loginBtn" value="登 陆" style="cursor:pointer;" />
				<input type="hidden" name="dopost" value="login" />
			</form>
			<div class="loginThanks">感谢您使用<span>PHPMyWind</span>产品</div>
		</div>
	</div>
</div>
<div class="loginCopyright">© 2015 phpMyWind.com</div>
</body>
</html>
