<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('site'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加新站点</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript">
$(function(){
	$("#site_key").blur(function(){
		var site_key = $(this).val();
		$("#site_lang").val(site_key);
	});
});
</script>
</head>
<body>
<?php
if(!empty($_SERVER['HTTP_HOST']))
	$baseurl = 'http://'.$_SERVER['HTTP_HOST'];
else
	$baseurl = "http://".$_SERVER['SERVER_NAME'];
?>
<div class="formHeader"> <span class="title">添加新站点</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="site_save.php" onsubmit="return cfm_site();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">站点名称：</td>
			<td width="75%"><input type="text" name="site_name" id="site_name" class="input" />
				<span class="maroon">*</span><span class="cnote">站点切换名称，例如：英文站</span></td>
		</tr>
		<tr>
			<td height="40" align="right">站点标识：</td>
			<td><input type="text" name="site_key" id="site_key" class="input" />
				<span class="maroon">*</span><span class="cnote">站点切换参数，例如：en_US</span></td>
		</tr>
        <tr class="nb">
			<td height="40" align="right">语言包名：</td>
			<td><input type="text" name="site_lang" id="site_lang" class="input" />
				<span class="maroon">*</span><span class="cnote">语言包默认与标识相同，包文件存放在 /data/lang/ 目录下</span></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">站点标题：</td>
			<td><input type="text" name="webname" id="webname" class="input" />
				<span class="maroon">*</span><span class="cnote">用于META头部 &lt;title&gt; 显示</span></td>
		</tr>
		<tr>
			<td width="25%" height="40" align="right">站点地址：</td>
			<td width="75%"><input type="text" name="weburl" id="weburl" class="input" value="<?php echo $baseurl; ?>" />
				<span class="maroon">*</span><span class="cnote">站点访问地址</span></td>
		</tr>
		<tr>
			<td width="25%" height="40" align="right">站点目录：</td>
			<td width="75%"><input type="text" name="webpath" id="webpath" class="input" value="<?php echo $cfg_webpath; ?>" />
				<span class="maroon">*</span><span class="cnote">站点文件存放目录，根目录留空</span></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">启用站点：</td>
			<td><input type="radio" name="webswitch" id="webswitch" value="Y" checked="checked" />
				是 &nbsp;
				<input type="radio" name="webswitch" id="webswitch" value="N" />
				否<span class="cnote">选择“否”则该站点不允许访问</span></td>
		</tr>
		<tr class="nb">
			<td height="35" align="right">&nbsp;</td>
			<td>更多站点基本信息可通过网站信息配置进行设置</td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="add" />
	</div>
</form>
</body>
</html>