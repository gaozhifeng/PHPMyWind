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
<div class="topToolbar"> <span class="title">上传文件管理</span> <span class="text">[<a href="upload_filemgr_clear.php" class="clearFile">清理未使用文件</a>]</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>

<div class="toolbarTab">
	<ul>
		<li class="on"><a href="upload_filemgr_sql.php">数据模式</a></li>
		<li class="line">-</li>
		<li><a href="upload_filemgr_dir.php">目录模式</a></li>
	</ul>
	<div class="search">
		<form name="search_f" id="search_f" method="get" action="">
			<span class="s">
			<input name="keyword" id="keyword" type="text" title="输入文件名进行搜索" value="<?php echo $keyword = isset($keyword) ? $keyword : ''; ?>" />
			</span> <span class="b"><a href="javascript:;" onclick="search_f.submit();"></a></span>
		</form>
	</div>
</div>
<form name="form" id="form" method="post" action="">
	<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);" /></td>
			<td width="25%">文件名称</td>
			<td width="20%">文件类型</td>
			<td width="20%">上传日期</td>
			<td width="15%">文件大小</td>
			<td width="15%" class="endCol">操作</td>
		</tr>
		<?php

		if(isset($keyword))
			$sql = "SELECT * FROM `#@__uploads` WHERE name LIKE '%$keyword%'";
		else
			$sql = "SELECT * FROM `#@__uploads`";

		$dopage->GetPage($sql, 50);
		while($row = $dosql->GetArray())
		{
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['path']; ?>" /></td>
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['type']; ?></td>
			<td class="number"><span><?php echo GetDateTime($row['posttime']); ?></span></td>
			<td><?php echo GetRealSize($row['size']); ?></td>
			<td class="action endCol"><span><a href="../<?php echo $row['path']; ?>" target="_blank">预览</a></span> | <span class="nb"><a href="upload_filemgr_save.php?mode=sql&action=del&id=<?php echo $row['id']; ?>&path=<?php echo $row['path']; ?>" onclick="return ConfDel(0);">删除</a></span></td>
		</tr>
		<?php
		}
		?>
	</table>
</form>
<?php

//无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有上传的文件</div>';
}
?>
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAll('upload_filemgr_save.php','&mode=sql');" onclick="return ConfDelAll(0);">删除</a></span> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAll('upload_filemgr_save.php','&mode=sql');" onclick="return ConfDelAll(0);">删除</a></span> <span class="pageSmall">
			<?php echo $dopage->GetList(); ?>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>