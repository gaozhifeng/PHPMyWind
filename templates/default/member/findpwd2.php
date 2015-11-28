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
		<div class="txt">若您没有设置安全问题，请通过E-mail方式找回。如有问题，请联系管理员<a href="?c=reg"></a></div>
	</div>
	<form method="post" action="?c=findpwd3" onsubmit="return CheckFindQues();">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td width="80" height="50"><h3>验证找回</h3></td>
					<td><span class="note">通过您填写的安全问题与回答找回</span></td>
				</tr>
				<tr>
					<td width="80" height="50">安全问题：</td>
					<td><select name="question" id="question" style="width:302px;">
						<option value="-1">选择问题</option>
						<?php
						$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='question' ORDER BY orderid ASC, datavalue ASC");
						while($row = $dosql->GetArray())
						{
							echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
						}
						?>
					</select></td>
				</tr>
				<tr>
					<td height="50">问题答案：</td>
					<td><input type="text" name="answer" id="answer" class="input" /></td>
				</tr>
				<tr>
					<td height="70"> </td>
					<td><input type="submit" value="找回密码" class="sub" /></td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="uname" value="<?php echo $username; ?>">
		<input type="hidden" name="a" value="quesfind">
	</form>
	<!--<form method="post" action="?c=findpwd3" onsubmit="return CheckFindMail();">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td width="80" height="50"><h3>邮箱找回</h3></td>
					<td><span class="note">填写您资料中的电子邮箱，系统会发送连接至您的邮箱</span></td>
				</tr>
				<tr>
					<td height="50">E-mail：</td>
					<td><input type="text" name="email" id="email" class="input"  /></td>
				</tr>
				<tr>
					<td height="70"> </td>
					<td><input type="submit" value="找回密码" class="sub" /></td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="uname" value="<?php echo $username; ?>">
		<input type="hidden" name="a" value="mailfind">
	</form>-->
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
