<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('message'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加留言</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="editor/kindeditor-min.js"></script>
<script type="text/javascript" src="editor/lang/zh_CN.js"></script>
</head>
<body>
<div class="formHeader"> <span class="title">添加留言</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="message_save.php" onsubmit="return cfm_msg();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">用户名：</td>
			<td width="75%"><input type="text" name="nickname" id="nickname" class="input" />
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项, 可以填“游客”</span></td>
		</tr>
		<tr>
			<td height="40" align="right">联系方式：</td>
			<td><input type="text" name="contact" id="contact" class="input" /></td>
		</tr>
		<tr>
			<td height="198" align="right">留言内容：</td>
			<td><textarea name="content" id="content"></textarea>
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
		</tr>
		<tr>
			<td height="198" align="right">回复内容：</td>
			<td><textarea name="recont" id="recont"></textarea>
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
			<td><input type="text" name="orderid" id="orderid" class="inputos" value="<?php echo GetOrderID('#@__message'); ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">更新时间：</td>
			<td><input type="text" name="posttime" id="posttime" class="inputms" value="<?php echo GetDateTime(time()); ?>" readonly="readonly" />
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
			<td><input id="htop" value="true" type="checkbox" name="htop" />
				置顶[h]
				<input id="rtop" value="true" type="checkbox" name="rtop" />
				推荐[r]</td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">审　核：</td>
			<td><input name="checkinfo" type="radio" value="true" checked="checked"  />
				是 &nbsp;
				<input type="radio" name="checkinfo" value="false" />
				否<span class="cnote">选择“否”则该信息暂时不显示在前台</span></td>
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