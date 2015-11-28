<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('editfile'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>默认模板编辑</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php

//设置文件编码
$action = isset($action) ? $action : '';


//更新编辑文件
if($action == 'update')
{
	if($cfg_editfile == 'Y')
	{
		//设置读取目录
		$dir = PHPMYWIND_ROOT.'/';
	
		//处理写入内容
		$content = stripslashes($content);
		$content = str_replace("##textarea","<textarea",$content);
		$content = str_replace("##/textarea","</textarea",$content);
		$content = str_replace("##form","<form",$content);
		$content = str_replace("##/form","</form",$content);
	
		//内容写入文件
		Writef($dir.$filename, $content, 'w');

		ShowMsg('文件保存成功！','editfile.php');
		exit();
	}
	else
	{
		ShowMsg('后台不允许直接编辑PHP文件！','editfile.php');
		exit();
	}
}


//显示编辑文件
if(!empty($filename))
{
	//设置读取目录
	$dir        = PHPMYWIND_ROOT.'/';
	$filename   = iconv('utf-8', 'gb2312', $filename);
	$gbfilename = mb_convert_encoding($filename, 'utf-8', 'gb2312');

	if(file_exists($dir.$filename))
	{
		$content = '';
		$fp = fopen($dir.$filename, 'r');
		
		if(filesize($dir.$filename) > 0)
		{
			$content = fread($fp, filesize($dir.$filename));
			$content = str_replace("<textarea","##textarea",$content);
			$content = str_replace("</textarea","##/textarea",$content);
			$content = str_replace("<form","##form",$content);
			$content = str_replace("</form","##/form",$content);
		}

		fclose($fp);
	}
	else
	{
		echo '<script type="text/javascript">alert("您所编辑的文件不存在！");location.href="editfile.php"</script>';
	}
}
?>
<div class="formHeader"> <span class="title">默认模板编辑</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="?action=update&filename=<?php echo urlencode($filename); ?>">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr align="center">
			<td height="40">文件名称：<strong><?php echo $gbfilename; ?></strong></td>
		</tr>
		<tr align="center">
			<td valign="middle" class="textAreaBox"><textarea name="content"><?php echo $content; ?></textarea></td>
		</tr>
	</table>
</form>
<div class="bottomToolbar"> <a href="#" onclick="form.submit();" class="dataBtn">保存文件</a> <a href="editfile.php" class="dataBack">返回列表</a> </div>
</body>
</html>