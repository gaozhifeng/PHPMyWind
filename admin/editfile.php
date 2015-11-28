<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('editfile'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>默认模板管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">默认模板管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr align="left" class="head">
		<td width="50%" height="36" class="firstCol">文件名称</td>
		<td width="25%">创建日期</td>
		<td width="10%">文件大小</td>
		<td width="15%" class="endCol">操作</td>
	</tr>
	<?php
		
	//设置读取目录
	$dir = PHPMYWIND_ROOT.'/';

	//打开文件夹
	$handler = opendir($dir);

	$i = 0;
	while(($filename = readdir($handler)) !== false)
	{
		if($filename != '.' && $filename != '..' && !is_dir($dir.$filename))
		{
			$gbfilename = mb_convert_encoding($filename, 'utf-8', 'gb2312');
			
			if($cfg_editfile == 'Y')
				$editstr = '<a href="editfile_update.php?filename='.urlencode($gbfilename).'">修改</a>';
			else
				$editstr = '<i style="font-style:normal;" title="不允许直接编辑PHP文件">修改</i>';
	?>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol"><?php echo $gbfilename; ?></td>
		<td class="number"><?php echo GetDateTime(filemtime($dir.$filename)); ?></td>
		<td><?php echo GetRealSize(filesize($dir.$filename)); ?></td>
		<td class="action endCol"><?php echo $editstr; ?></td>
	</tr>
	<?php
		$i++;
		}
	}

	closedir($handler);
	?>
</table>
<ul class="tipsList">
	<li>由于允许直接通过后台编辑PHP脚本文件对系统造成安全隐患，所以系统默认关闭修改，若想通过后台编辑PHP文件，在 '/admin/inc/config.inc.php' 中将 '$cfg_editfile' 值设为 'Y' 即可</li>
</ul>
<div class="page">
	<div class="pageText">共有<span><?php echo $i; ?></span>条记录</div>
</div>
</body>
</html>