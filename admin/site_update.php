<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('site'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改站点</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__site` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改站点</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="site_save.php" onsubmit="return cfm_site();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr class="nb">
			<td width="25%" height="40" align="right">站点名称：</td>
			<td width="75%"><input type="text" name="site_name" id="site_name" class="input" value="<?php echo $row['sitename']; ?>" />
				<span class="maroon">*</span><span class="cnote">站点切换名称，例如：英文站</span></td>
		</tr>
        <tr class="nb">
			<td width="25%" height="40" align="right">语言包名：</td>
			<td width="75%"><input type="text" name="site_lang" id="site_lang" class="input" value="<?php echo $row['sitelang']; ?>" />
				<span class="maroon">*</span><span class="cnote">语言包默认与标识相同，包文件存放在 /data/lang/ 目录下</span></td>
		</tr>
		<tr class="nb">
			<td height="35" align="right">&nbsp;</td>
			<td>更多站点基本信息可通过网站信息配置进行设置</td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
		<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	</div>
</form>
</body>
</html>