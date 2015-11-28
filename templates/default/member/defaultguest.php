<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
</head>

<body>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<?php require_once(dirname(__FILE__).'/lefterguest.php'); ?>
	<div class="rightarea">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="35%">登录时间</td>
				<td>登录IP</td>
			</tr>
			<tr>
				<td><strong class="loginfo"><?php echo MyDate('Y-m-d H:i',$c_logintime); ?></strong></td>
				<td><strong class="loginfo"><?php echo $c_loginip; ?></strong></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>
		<h3 class="dftitle">账号操作</h3>
		您是第一次使用该账号登录，接下来需要 完善账号信息 或 绑定已有账号
		<div class="pbArea">
			<a href="?c=perfect" class="perfect">完善账号信息</a>
			<a href="?c=binding" class="binding">绑定已有账号</a>
		</div>
		<ul class="pbTips">
			<li>完善账号信息：适用于没有注册账号的访客使用</li>
			<li>绑定已有账号：如果您拥有注册账号，可以将第三方账号绑定到注册账号</li>
		</ul>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
