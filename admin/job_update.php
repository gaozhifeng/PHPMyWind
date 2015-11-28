<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('job'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改招聘信息</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="editor/kindeditor-min.js"></script>
<script type="text/javascript" src="editor/lang/zh_CN.js"></script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__job` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改招聘信息</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="job_save.php" onsubmit="return cfm_job();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">岗位名称：</td>
			<td width="75%"><input type="text" name="title" id="title" class="input" value="<?php echo $row['title']; ?>" />
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span> <span id="usernote"></span></td>
		</tr>
		<tr>
			<td height="40" align="right">工作地点：</td>
			<td><input type="text" name="jobplace" id="jobplace" class="input" value="<?php echo $row['jobplace']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">工作性质：</td>
			<td><input type="text" name="jobdescription" id="jobdescription" class="input" value="<?php echo $row['jobdescription']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">招聘人数：</td>
			<td><input type="text" name="employ" id="employ" class="input" value="<?php echo $row['employ']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">性别要求：</td>
			<td><input type="radio" name="jobsex" value="0" <?php if($row['jobsex'] == '0') echo 'checked="checked"'; ?> />
				不限&nbsp;
				<input type="radio" name="jobsex" value="1" <?php if($row['jobsex'] == '1') echo 'checked="checked"'; ?> />
				男&nbsp;
				<input type="radio" name="jobsex" value="2" <?php if($row['jobsex'] == '2') echo 'checked="checked"'; ?> />
				女</td>
		</tr>
		<tr>
			<td height="40" align="right">工资待遇：</td>
			<td><input type="text" name="treatment" id="treatment" class="input" value="<?php echo $row['treatment']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">有效期限：</td>
			<td><input type="text" name="usefullife" id="usefullife" class="input" value="<?php echo $row['usefullife']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">工作经验：</td>
			<td><input type="text" name="experience" id="experience" class="input" value="<?php echo $row['experience']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">学历要求：</td>
			<td><input type="text" name="education" id="education" class="input" value="<?php echo $row['education']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">语言能力：</td>
			<td><input type="text" name="joblang" id="joblang" class="input" value="<?php echo $row['joblang']; ?>" /></td>
		</tr>
		<tr>
			<td height="304" align="right">职位描述：</td>
			<td><textarea name="workdesc" id="workdesc"><?php echo $row['workdesc']; ?></textarea>
				<script>
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="workdesc"]', {
						allowFileManager : true,
						width:'667px',
						height:'280px',
						extraFileUploadParams : {
							sessionid :  '<?php echo session_id(); ?>'
						}
					});
				});
				</script></td>
		</tr>
		<tr>
			<td height="304" align="right">职位要求：</td>
			<td><textarea name="content" id="content"><?php echo $row['content']; ?></textarea>
				<script>
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="content"]', {
						allowFileManager : true,
						width:'667px',
						height:'280px',
						extraFileUploadParams : {
							sessionid :  '<?php echo session_id(); ?>'
						}
					});
				});
				</script></td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputos" value="<?php echo $row['orderid']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">更新时间：</td>
			<td><input type="text" name="posttime" id="posttime" class="inputms" value="<?php echo GetDateTime($row['posttime']); ?>" readonly="readonly" />
				<script type="text/javascript" src="plugin/calendar/calendar.js"></script> 
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
			<td height="40" align="right">发　布：</td>
			<td><input type="radio" name="checkinfo" value="true" <?php if($row['checkinfo'] == 'true') echo 'checked="checked"'; ?> />
				是 &nbsp;
				<input type="radio" name="checkinfo" value="false" <?php if($row['checkinfo'] == 'false') echo 'checked="checked"'; ?> />
				否<span class="cnote">选择“否”则该信息不会显示在前台。</span></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input name="action" type="hidden" id="action" value="update" />
		<input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
	</div>
</form>
</body>
</html>