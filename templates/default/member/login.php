<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员登录</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
</head>

<body>
<div class="header">
	<div class="area">
		<div class="logo"><a href="<?php echo $cfg_webpath; ?>/"></a></div>
		<div class="retxt"><a href="<?php echo $cfg_webpath; ?>/">网站首页</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?c=reg">注册</a></div>
	</div>
</div>
<div class="mainbody" style="position:relative;">
	<div class="top">
		<h2>登录账户</h2>
		<div class="txt">欢迎您登录<?php echo $cfg_webname; ?>会员，如果您还没有注册，则可在此 <a href="?c=reg">注册</a></div>
	</div>
	<?php
	if($d == md5('reg'))
	{
		echo '<div class="infor">恭喜您，注册成功，请登录！</div>';
	}
	else if($d == md5('newpwd'))
	{
		echo '<div class="infor">重设新密码成功！</div>';
	}
	?>
	<form id="form" method="post" action="?a=login" onsubmit="return CheckLog();">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td width="100" height="50">用户名：</td>
				<td colspan="2"><input type="text" name="username" id="username" class="input" /></td>
				</tr>
				<tr>
					<td height="50">密　码：</td>
					<td colspan="2"><input type="password" name="password" id="password" class="input" /></td>
				</tr>
				<tr>
					<td height="50">验证码：</td>
					<td colspan="2"><input type="text" name="validate" id="validate" class="input" maxlength="4" />
						<span><img id="ckstr" src="data/captcha/ckstr.php" title="看不清？点击更换" align="absmiddle" style="cursor:pointer;" onClick="this.src=this.src+'?'" /> <a href="javascript:;" onClick="var v=document.getElementById('ckstr');v.src=v.src+'?';return false;">看不清?</a></span><br /></td>
				</tr>
				<tr>
					<td height="70"> </td>
					<td width="110"><input type="submit" value="立即登录" class="sub" /></td>
					<td width="1138"><a href="?c=findpwd" class="findpwd">忘记密码？</a></td>
				</tr>
				<tr>
					<td height="22">&nbsp;</td>
					<td colspan="2"><input type="checkbox" title="两周内自动登录" name="autologin" id="autologin" value="1" />
					<label for="autologin" title="为了您的信息安全，请不要在网吧或公用电脑上使用此功能！"> 两周内自动登录</label></td>
				</tr>
				<?php
				if($cfg_oauth == 'Y')
				{
				?>
				<tr>
					<td height="40"> </td>
					<td colspan="2" valign="bottom">合作账号：<a href="data/api/oauth/connect.php?method=qq_token" class="oqq">QQ登录</a> <a href="data/api/oauth/connect.php?method=weibo_token" class="osina">微博登录</a></td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</form>
	<div class="regbtn"><a href="?c=reg">注册一个账号</a></div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
