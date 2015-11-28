<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('message'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改留言</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="editor/kindeditor-min.js"></script>
<script type="text/javascript" src="editor/lang/zh_CN.js"></script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__message` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改留言</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="message_save.php" onsubmit="return cfm_msg();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">用户名：</td>
			<td width="75%"><strong><?php echo $row['nickname'] ?></strong></td>
		</tr>
		<tr>
			<td height="40" align="right">联系方式：</td>
			<td><input type="text" name="contact" id="contact" class="input" value="<?php echo $row['contact'] ?>" /></td>
		</tr>
		<tr>
			<td height="198" align="right">留言内容：</td>
			<td><textarea name="content" id="content"><?php echo $row['content'] ?></textarea>
				<script>
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="content"]', {
						resizeType : 1,
						width:'500px',
						height:'180px',
						extraFileUploadParams : {
							sessionid :  '<?php echo session_id(); ?>'
						},
						allowPreviewEmoticons : false,
						allowImageUpload : false,
						items : [
							'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
							'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
							'insertunorderedlist', '|', 'emoticons', 'image', 'link']
					});
				});
				</script></td>
				</td>
		</tr>
		<tr>
			<td height="198" align="right">回复内容：</td>
			<td><textarea name="recont" id="recont"><?php echo $row['recont'] ?></textarea>
				<script>
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="recont"]', {
						resizeType : 1,
						width:'500px',
						height:'180px',
						extraFileUploadParams : {
							sessionid :  '<?php echo session_id(); ?>'
						},
						allowPreviewEmoticons : false,
						allowImageUpload : false,
						items : [
							'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
							'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
							'insertunorderedlist', '|', 'emoticons', 'image', 'link']
					});
				});
				</script></td>
				</td>
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
		<tr>
			<td height="40" align="right">状　态：</td>
			<td><input type="checkbox" name="htop" id="htop" value="true" <?php if($row['htop']=='true') echo 'checked="checked"'; ?> />
				置顶[h]
				<input type="checkbox" name="rtop" id="rtop" value="true" <?php if($row['rtop']=='true') echo 'checked="checked"'; ?> />
				推荐[r]</td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">审　核：</td>
			<td><input type="radio" name="checkinfo" value="true" <?php if($row['checkinfo']=='true') echo 'checked="checked"'; ?> />
				是
				&nbsp;
				<input type="radio" name="checkinfo" value="false" <?php if($row['checkinfo']=='false') echo 'checked="checked"'; ?> />
				否 <span class="cnote">选择“否”则该信息暂时不显示在前台</span></td>
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