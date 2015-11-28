<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('mobile'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>手机网站设置</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$(".tabMobile #h").click(function(){
		$(".tabMobile a").removeClass();
		$(this).addClass("on");
		$("#preMobile").attr("class","preMobile");
	});

	$(".tabMobile #s").click(function(){
		$(".tabMobile a").removeClass();
		$(this).addClass("on");
		$("#preMobile").attr("class","preMobile2");
	});
});
</script>
</head>
<body>
<div class="topToolbar"> <span class="title">手机网站设置</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="300" valign="top"><div class="preMobile" id="preMobile">
				<iframe frameborder="0" src="../4g.php" scrolling="yes"></iframe>
			</div></td>
		<td valign="top"><div class="txtMobile">
				<ul class="tipsList">
					<li>当系统判断为移动设备访问时，会主动跳转到4g.php页面，您可以在【网站信息配置】->【性能设置】中关闭默认跳转</li>
					<li>手机站的栏目是根据栏目中的栏目自动循环出来的</li>
					<li>目前自动生成的栏目内容包括单页、列表、图片</li>
				</ul>
				<ul class="tabMobile">
					<li><a href="javascript:;" class="on" id="h">竖屏</a></li>
					<li><a href="javascript:;" id="s">横屏</a></li>
				</ul>
				<br />
				<div class="cl"></div>
			</div></td>
	</tr>
</table>
</body>
</html>