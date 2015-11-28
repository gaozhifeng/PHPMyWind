<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 找回密码</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
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
		<div class="txt">请牢记您注册时填写的账户信息，以便方便您找回密码<a href="?c=reg"></a></div>
	</div>
	<form id="form" method="post" action="?c=findpwd2" onsubmit="return CheckFind();">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td width="80" height="50">用户名：</td>
					<td><input type="text" name="username" id="username" class="input" /></td>
				</tr>
				<tr>
					<td height="50">验证码：</td>
					<td><input type="text" name="validate" id="validate" class="input" maxlength="4" />
						<span><img id="ckstr" src="data/captcha/ckstr.php" title="看不清？点击更换" align="absmiddle" style="cursor:pointer;" onClick="this.src=this.src+'?'" /> <a href="javascript:;" onClick="var v=document.getElementById('ckstr');v.src=v.src+'?';return false;">看不清?</a></span><br /></td>
				</tr>
				<tr>
					<td height="70"> </td>
					<td><input type="submit" value="下一步" class="sub" /></td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="a" value="findpwd2" />
	</form>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
