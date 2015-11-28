<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('admanage'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改广告</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="templates/js/getuploadify.js"></script>
<script type="text/javascript" src="plugin/calendar/calendar.js"></script>
<script type="text/javascript">
function GetMode(m)
{
	if(m == "html")
	{
		$("#adarea").hide();
		$("#adtext").show();
	}
	else
	{
		$("#adarea").show();
		$("#adtext").hide();
	}
}
</script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__admanage` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改广告</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="admanage_save.php" onsubmit="return cfm_admanager();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">投放范围：</td>
			<td width="75%"><select name="classid" id="classid">
					<option value="-1">请选择投放范围</option>
					<?php GetAllType('#@__adtype','#@__admanage','classid'); ?>
				</select>
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="40" align="right">广告位标识：</td>
			<td><input type="text" name="title" id="title" class="input" value="<?php echo $row['title'];?>" />
				<span class="maroon">*</span><span class="cnote">例如：某某推广第三期</span></td>
		</tr>
		<tr>
			<td align="right">展示内容：</td>
			<td><div class="hr_10"></div>
				<input type="radio" name="admode" value="image" onclick="GetMode('image');" <?php if($row['admode'] == 'image') echo 'checked="checked"'; ?> />
				图片 &nbsp;
				<input type="radio" name="admode" value="flash" onclick="GetMode('flash');" <?php if($row['admode'] == 'flash') echo 'checked="checked"'; ?> />
				Flash &nbsp;
				<input type="radio" name="admode" value="video" onclick="GetMode('video');" <?php if($row['admode'] == 'video') echo 'checked="checked"'; ?> />
				视频 &nbsp;
				<input type="radio" name="admode" value="html" onclick="GetMode('html');" <?php if($row['admode'] == 'html') echo 'checked="checked"'; ?> />
				HTML代码
				<div class="hr_10"></div>
				<div id="adarea" style="<?php if($row['admode'] == 'html') echo 'display:none;';?>">
					<input type="text" name="picurl" class="input" id="picurl" value="<?php echo $row['picurl'];?>" />
					<span class="maroon">&nbsp;</span><span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','广告内容上传','*.jpg;*.png;*.gif;*.swf;*.flv;*.wmv','文件格式:(*.jpg;*.png;*.gif;*.bmp;*.swf;*.flv;*.wmv)',1,<?php echo $cfg_max_file_size; ?>,'picurl')">上 传</span></span> </div>
				<div id="adtext" class="undis" style="<?php if($row['admode'] == 'html') echo 'display:block;';?>">
					<textarea name="adtext" id="adtext" class="textarea"><?php echo $row['adtext'];?></textarea>
				</div>
				<div class="hr_10"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">跳转链接：</td>
			<td><input type="text" name="linkurl" id="linkurl" class="input" value="<?php echo $row['linkurl'];?>" />
				<span class="maroon">&nbsp;</span><span class="cnote">网址请填写 http:// 开头</span></td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputos" value="<?php echo $row['orderid'];?>" /></td>
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
		<tr class="nb">
			<td height="40" align="right">发　布：</td>
			<td><input type="radio" name="checkinfo" id="checkinfo" value="true" <?php if($row['checkinfo'] == 'true') echo 'checked="checked"'; ?> />
				是 &nbsp;
				<input type="radio" name="checkinfo" id="checkinfo" value="false" <?php if($row['checkinfo'] == 'false') echo 'checked="checked"'; ?> />
				否<span class="cnote">选择“否”则该广告不会显示在前台。</span></td>
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