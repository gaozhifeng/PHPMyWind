<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('diymodel'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改自定义模型</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE id=$id");
?>
<div class="formHeader"> <span class="title">修改自定义模型</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="diymodel_save.php" onsubmit="return cfm_diymodel();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">模型标识：</td>
			<td width="75%"><strong><?php echo $row['modelname']; ?></strong>
			<span class="maroon">*</span><span class="cnote">模型标识与表名一旦设置不允许更改</span></td>
		</tr>
		<tr>
			<td height="40" align="right">模型表名：</td>
			<td><strong><?php echo $row['modeltbname']; ?></strong>
				<span class="maroon">*</span>
				</td>
		</tr>
		<tr>
			<td height="40" align="right">模型名称：</td>
			<td><input type="text" name="modeltitle" id="modeltitle" class="input" value="<?php echo $row['modeltitle']; ?>" />
				<span class="maroon">*</span><span class="cnote">用于模型名称的显示</span></td>
		</tr>
		<tr>
			<td height="62" align="right">预设字段：</td>
			<td><?php
			if(is_array(explode(',', $row['defaultfield'])))
				$defaultfield = explode(',', $row['defaultfield']);
			else
				$defaultfield = array();
			?><div class="purviewListS"> <span>
					<input type="checkbox" name="defaultfield[]" value="title" <?php if(in_array('title', $defaultfield)) echo 'checked="checked"'; ?> />
					标　题</span> <span>
					<input type="checkbox" name="defaultfield[]" value="flag" <?php if(in_array('flag', $defaultfield)) echo 'checked="checked"'; ?> />
					属　性</span> <span>
					<input type="checkbox" name="defaultfield[]" value="picurl" <?php if(in_array('picurl', $defaultfield)) echo 'checked="checked"'; ?> />
					缩略图片</span> <span>
					<input type="checkbox" name="defaultfield[]" value="orderid" <?php if(in_array('orderid', $defaultfield)) echo 'checked="checked"'; ?> />
					排列排序</span> <span>
					<input type="checkbox" name="defaultfield[]" value="posttime" <?php if(in_array('posttime', $defaultfield)) echo 'checked="checked"'; ?> />
					更新时间</span></div>
					<div class="purviewTitle colorBf">启用预设字段后，该模型将自动创建勾选的预设字段，以便不需手动创建，不需要时可在模型修改中取消勾选</div></td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputs" value="<?php echo $row['orderid']; ?>" /></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">审　核：</td>
			<td><input type="radio" name="checkinfo" value="true" <?php if($row['checkinfo'] == 'true') echo 'checked="checked"'; ?> />
				启用&nbsp;
				<input type="radio" name="checkinfo" value="false" <?php if($row['checkinfo'] == 'false') echo 'checked="checked"'; ?> />
				禁用</td>
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