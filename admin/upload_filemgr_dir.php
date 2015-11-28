<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('upload_filemgr_sql'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传文件管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<?php

if(empty($dirname) or $dirname=='uploads/')
{
	$dirname = 'uploads/';
	$dirhigh = 'javascript:;';
	$dirtext = '当前是根目录';
}
else
{
	$dirname = str_replace(array('..\\', '../', './', '.\\'), '', trim($dirname));
	$dirname = htmlspecialchars($dirname);
	$dirhigh = '?dirname=';
	$dirtext = '返回上一层';

	$dirarr = explode('/', $dirname);
	$curnum = count($dirarr)-2;
	for($i=0; $i<$curnum; $i++)
	{
		$dirhigh .= $dirarr[$i].'/';
	}
}
?>
<div class="topToolbar"> <span class="title">上传文件管理</span> <span class="text">[当前目录：<strong>/<?php echo $dirname; ?></strong><span>|</span><a href="<?php echo $dirhigh; ?>" class="topFolder"><?php echo $dirtext; ?></a><span>|</span><a href="upload_filemgr_clear.php" class="clearFile">清理未使用文件</a>]</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<div class="toolbarTab">
	<ul>
		<li><a href="upload_filemgr_sql.php">数据模式</a></li>
		<li class="line">-</li>
		<li class="on"><a href="upload_filemgr_dir.php">目录模式</a></li>
	</ul>
</div>
<form name="form" id="form" method="post" action="upload_filemgr_save.php">
	<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);" /></td>
			<td width="30%">文件名称</td>
			<td width="25%">上传日期</td>
			<td width="25%">文件大小</td>
			<td width="15%" class="endCol">操作</td>
		</tr>
		<?php

		//设置读取目录
		$dir = '../'.$dirname;

		//避免中文文件无法读取，强制转换
		$dir = iconv('utf-8', 'gb2312', $dir);

		//打开文件夹
		$handler = opendir($dir);

		$i = 0;
		while(($filename = readdir($handler)) !== false)
		{

			if($filename != '.' && $filename != '..'
			&& $filename != ($dirname=='uploads/' ? 'index.htm' : ''))
			{

				//用于显示中文目录
				$gbfilename = mb_convert_encoding($filename, 'utf-8', 'gb2312');

				if(is_dir($dir.$filename))
				{
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $gbfilename; ?>" disabled="disabled" /></td>
			<td><span class="isdir"><?php echo $gbfilename; ?></span></td>
			<td class="number"><span><?php echo date("Y-m-d H:i:s", filemtime($dir.$filename)); ?></span></td>
			<td><?php echo GetRealSize(GetDirSize($dir.$filename)); ?></td>
			<td class="action endCol"><span><a href="upload_filemgr_dir.php?dirname=<?php echo urlencode($dirname.$gbfilename.'/'); ?>">进入</a></span> | <span class="nb"><?php if($dirname == 'uploads/'){echo '删除';} else{ ?><a href="upload_filemgr_save.php?mode=dir&action=deldir&dirname=<?php echo urlencode($dirname); ?>&filename=<?php echo urlencode($filename.'/'); ?>" onclick="return ConfDel(0);">删除</a><?php } ?></span></td>
		</tr>
		<?php
				}
				else
				{
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $gbfilename; ?>" /></td>
			<td><?php echo $gbfilename; ?></td>
			<td class="number"><span><?php echo date("Y-m-d H:i:s", filemtime($dir.$filename)); ?></span></td>
			<td><?php echo GetRealSize(filesize($dir.$filename)); ?></td>
			<td class="action endCol"><span><a href="../<?php echo $dirname.$gbfilename; ?>" target="_blank">预览</a></span> | <span class="nb"><a href="upload_filemgr_save.php?mode=dir&action=delfile&dirname=<?php echo urlencode($dirname); ?>&filename=<?php echo urlencode($filename); ?>" onclick="return ConfDel(0);">删除</a></span></td>
		</tr>
		<?php
				}
			$i++;
			}
		}
		closedir($handler);
		?>
	</table>
	<input type="hidden" name="dirname" id="dirname" value="<?php echo $dirname; ?>" />
</form>
<?php

//无记录样式
if($i == 0)
{
	echo '<div class="dataEmpty">暂时没有上传的文件</div>';
}
?>
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAll('upload_filemgr_save.php','&mode=dir');" onclick="return ConfDelAll(0);">删除</a></span> </div>
<div class="page"> <div class="pageText">共<span><?php echo $i; ?></span>条记录</div> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAll('upload_filemgr_save.php','&mode=dir');" onclick="return ConfDelAll(0);">删除</a></span> <span class="pageSmall">
			<div class="pageText">共有<span><?php echo $i; ?></span>条记录</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>