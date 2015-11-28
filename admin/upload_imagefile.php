<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>图片文件上传</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body style="margin:0;padding:0;">
<div style="float:left;margin-right:12px;">
		<form name="form" enctype="multipart/form-data" method="post" action="?">
				<input type="file" name="upfile" style="height:21px;width:195px;">
				<input type="submit" value="上传" style="height:21px;width:45px;" onclick="UploadPrompt(0)">
				<input type="hidden" name="fn" value="<?php echo ($fn = isset($fn) ? $fn : ''); ?>">
		</form>
</div>
<div class="uploading" style="line-height:21px;"><span style="color:#999;">仅支持 gif / jpg / png 格式</span></div>
</body>
</html>
<?php

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(!is_uploaded_file($_FILES['upfile']['tmp_name'])) //是否存在文件
	{
		echo '<script>UploadPrompt("请选择要上传的图片文件！");</script>';
		exit();
	}

	$file = $_FILES['upfile'];
	$file_name = $file['name'];
	$file_type_name = substr(strrchr($file_name, '.'), 1);
	$file_size = $file['size'];
	$file_type = $file['type'];
	$file_tmp_name = $file['tmp_name'];
	
	if(!in_array($file_type_name, array('gif','jpg','png'))) //检查文件类型
	{
		echo "<script>UploadPrompt('您上传的文件类型为：[$file_type_name]，该文件类型不允许上传！');</script>";
		exit();
	}
	
	if(!is_uploaded_file($file_tmp_name)) //是否存在临时文件
	{
		echo "<script>UploadPrompt('文件创建失败，可能上传文件容量超过了ini文件定义的\n最大文件限制[$ini_upload_max_size]或最大文件传递限制[$ini_post_max_size]');</script>";
		exit();
	}
	
	if($file_size > $cfg_max_file_size) //检查文件大小
	{
		echo"<script>UploadPrompt('您上传的文件超过系统设定最大文件上传限制[".GetRealSize($cfg_max_file_size)."]');</script>";
		exit(); 
	}
	
	//构建上传服务器后文件地址
	if(isset($fn))
	{
		if($fn == 'watermarket')
		{
			$file_upload_name = PHPMYWIND_DATA.'/watermark/watermarket.'.$file_type_name;
		}
		else if($fn == 'manageloginbg')
		{
			$manageloginbg = time()+rand(1,9999).'.'.$file_type_name;
			$file_upload_name = ADMIN_TEMP.'/images/loginbg/'.$manageloginbg;
		}
		else
		{
			$file_upload_name = PHPMYWIND_DATA.'/watermark/watermarket.'.$file_type_name;
		}
	}

	if(move_uploaded_file($file_tmp_name, $file_upload_name))
	{
		if($fn == 'watermarket')
		{
			echo "<script>UploadPrompt('<span class=\"upload_file_ok\">上传成功！</span> 文件大小为：".GetRealSize($file_size)."');parent.document.getElementById(\"cfg_markpicurl\").value='data/watermark/watermarket.".$file_type_name."';parent.document.getElementById(\"watermark_prew\").src='../data/watermark/watermarket.".$file_type_name."';</script>";
		}
		else if($fn == 'manageloginbg')
		{
			echo "<script>UploadPrompt('<span class=\"upload_file_ok\">上传成功！</span> 文件大小为：".GetRealSize($file_size)."');parent.document.getElementById(\"cfg_loginbgimg\").value='templates/images/loginbg/".$manageloginbg."';parent.document.getElementById(\"loginbgimg_prew\").src='templates/images/loginbg/".$manageloginbg."';</script>";
		}
		else
		{
			echo "<script>UploadPrompt('<span class=\"upload_file_ok\">上传成功！</span> 文件大小为：".GetRealSize($file_size)."');parent.document.getElementById(\"cfg_markpicurl\").value='data/watermark/watermarket.".$file_type_name."';parent.document.getElementById(\"watermark_prew\").src='../data/watermark/watermarket.".$file_type_name."';</script>";
		}
		
		exit();
	}
	else
	{
		echo "<script>UploadPrompt('<span class=\"upload_file_nok\">上传失败，发生未知错误！</span>');</script>";
		exit();
	}
}
?>