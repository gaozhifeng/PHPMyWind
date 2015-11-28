<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('nav'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加导航菜单</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/getuploadify.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
</head>
<body>
<div class="formHeader"> <span class="title">添加导航菜单</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="nav_save.php" onsubmit="return cfm_nav();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td height="40" align="right" width="25%">所属分类：</td>
			<td width="75%"><select name="parentid" id="parentid">
					<option value="0">一级导航分类</option>
					<?php GetAllType('#@__nav','#@__nav','id'); ?>
				</select>
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="40" align="right">导航名称：</td>
			<td><input type="text" name="classname" id="classname" class="input" />
				<span class="maroon">*</span><span class="cnote">导航图片不为空，则以导航图片为优先级展示</span></td>
		</tr>
		<tr>
			<td height="40" align="right">跳转链接：</td>
			<td><input type="text" name="linkurl" id="linkurl" class="input" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">重写地址：</td>
			<td><input type="text" name="relinkurl" id="relinkurl" class="input" />
				<span class="maroon">&nbsp;</span><span class="cnote">若开启URL静态化，系统自动切换至重写地址；请符合URL静态化设置的规则</span></td>
		</tr>
		<tr>
			<td height="40" align="right">导航图片：</td>
			<td><input type="text" name="picurl" id="picurl" class="input" />
				<span class="maroon">&nbsp;</span><span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','缩略图上传','image','image',1,<?php echo $cfg_max_file_size; ?>,'picurl')">上 传</span></span></td>
		</tr>
		<tr>
			<td height="40" align="right">打开方式：</td>
			<td><input type="text" name="target" id="target" class="inputs" value="" />
				<select name="seltarget" id="seltarget" onchange="target.value=this.value;this.value=''">
					<option value="">打开方式</option>
					<option value="_blank">_blank</option>
					<option value="_parent">_parent</option>
					<option value="_self">_self</option>
					<option value="_top">_top</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputs" value="<?php echo GetOrderID('#@__nav'); ?>" /></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">隐藏导航：</td>
			<td><input type="radio" name="checkinfo" value="true" checked="checked"  />
				显示&nbsp;
				<input type="radio" name="checkinfo" value="false" />
				隐藏</td>
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