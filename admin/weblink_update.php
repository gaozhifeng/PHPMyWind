<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('weblink'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改友情链接</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="plugin/layer/layer.js"></script><!--弹窗js---->
<script type="text/javascript" src="plugin/uploader.js?v1"></script><!--上传js 必须在弹窗下面---->
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="plugin/calendar/calendar.js"></script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__weblink` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改友情链接</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="weblink_save.php" onsubmit="return cfm_weblink();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">链接类型：</td>
			<td width="75%"><select name="classid" id="classid">
					<option value="-1">请选择链接类型</option>
					<?php GetAllType('#@__weblinktype','#@__weblink','classid'); ?>
				</select>
				<span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="40" align="right">网站名称：</td>
			<td><input type="text" name="webname" id="webname" class="input" value="<?php echo $row['webname']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">跳转链接：</td>
			<td><input type="text" name="linkurl" id="linkurl" class="input" value="<?php echo $row['linkurl']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="118" align="right">网站描述：</td>
			<td><textarea name="webnote" id="webnote" class="textarea"><?php echo $row['webnote']; ?></textarea></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">缩略图片：</td>
			<td><input type="text" name="picurl" id="picurl" class="input" value="<?php echo $row['picurl']; ?>" />
				<span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','缩略图上传','image','image',1,<?php echo $cfg_max_file_size; ?>,'picurl')">上 传</span></span></td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputos" value="<?php echo $row['orderid']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">更新时间：</td>
			<td><input type="text" name="posttime" id="posttime" class="inputms" value="<?php echo GetDateTime($row['posttime']); ?>" readonly="readonly" />
				<script type="text/javascript">
				Calendar.setup({
					inputField     :    "posttime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">审　核：</td>
			<td><input type="radio" name="checkinfo" value="true" <?php if($row['checkinfo'] == 'true'){echo 'checked="checked"';} ?> />
				是 &nbsp;
				<input type="radio" name="checkinfo" value="false" <?php if($row['checkinfo'] == 'false'){echo 'checked="checked"';} ?> />
				否<span class="cnote">选择“否”则该信息暂时不显示在前台</span></td>
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