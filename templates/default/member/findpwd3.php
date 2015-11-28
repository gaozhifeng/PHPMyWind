<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 找回密码</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
</head>

<body>
<div class="header">
	<div class="area">
		<div class="logo"><a href="<?php echo $cfg_webpath; ?>/"></a></div>
		<div class="retxt"><a href="<?php echo $cfg_webpath; ?>/">网站首页</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?c=login">登录</a></div>
	</div>
</div>
<div class="mainbody">
	<div class="top">
		<h2>找回密码</h2>
	</div>
	<?php
	if($a == 'quesfind')
	{
	?>
	<form method="post" action="?c=login" onsubmit="return CheckNewPwd();">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td width="80" height="50"><h3>设置密码</h3></td>
					<td><span class="note">设置一个新密码</span></td>
				</tr>
				<tr>
					<td height="50">设置密码：</td>
					<td><input type="password" name="password" id="password" class="input" />
						<span class="note">密码长度为6~16位字符，可以为“数字/字母/中划线/下划线”组成</span></td>
				</tr>
				<tr>
					<td height="50">确认密码：</td>
					<td><input type="password" name="repassword" id="repassword" class="input" /></td>
				</tr>
				<tr>
					<td height="70"> </td>
					<td><input type="submit" value="完成" class="sub" /></td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="uname" value="<?php echo $uname; ?>">
		<input type="hidden" name="a" value="setnewpwd">
	</form>
	<?php
	}
	if($a == 'mailfind')
	{
	?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td width="80" height="50"><h3>邮箱找回</h3></td>
				<td><span class="note">登录邮箱设置一个新密码</span></td>
			</tr>
			<tr>
				<td height="50">&nbsp;</td>
				<td style="font-size:14px;">恭喜您，重设连接我们已经发送到您的邮箱，请登录邮箱设置新密码！</td>
			</tr>
			<tr>
				<td height="70"> </td>
				<td><input type="button" value="返回" class="sub" onclick="location.href='?c=login';return false;" /></td>
			</tr>
		</tbody>
	</table>
	<?php
	}
	?>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
