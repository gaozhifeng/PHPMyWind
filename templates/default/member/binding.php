<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 绑定已有账号</title>
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
		<h3 class="dftitle">绑定账号</h3>
		<form name="form" id="form" method="post" action="?a=binding" onsubmit="return cfm_binding();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="20%" height="40" align="right">账　号：</td>
					<td width="80%"><input name="username" type="text" id="username" class="class_input" /></td>
				</tr>
				<tr>
					<td width="20%" height="40" align="right">密　码：</td>
					<td width="80%"><input name="password" type="password" id="password" class="class_input" /></td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="保 存" />
				<input type="button" class="btn" value="取 消" onclick="history.go(-1)"  />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
