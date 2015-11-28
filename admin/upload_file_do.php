<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('upload_file'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传区域</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="newupload">
	<div class="newupload_area">
		<form name="from" id="from" enctype="multipart/form-data" method="post" onsubmit="return CheckIsUpload();">
			<label>请选择上传文件：</label>
			<input type="file" name="upfile" id="upfile" class="upload_newfile_file">
			<input type="submit" class="upload_newfile_btn" onclick="UploadPrompt(0)" value="上传" />
		</form>
	</div>
	<div class="uploading"></div>
	<div class="cl"></div>
</div>
<?php
if(!empty($_FILES))
{
	//上传类在页面底端引用，以便显示提示信息
	require_once(PHPMYWIND_DATA.'/httpfile/upload.class.php');

	$upload_info = UploadFile('upfile');

	if(!is_array($upload_info))
	{
		echo '<script>UploadPrompt(\'<span class="upload_file_nok">'.$upload_info.'</span>\')</script>';
	}
	else
	{
		echo '<script>UploadPrompt(\'<span class="upload_file_ok">上传成功！</span>上传后路径为：<span class="upload_file_name">'.$upload_info[2].'</span>，大小为：<span class="upload_file_name">'.GetRealSize($upload_info[1]).'</span>\');</script>';
	}
}
?>
</body>
</html>