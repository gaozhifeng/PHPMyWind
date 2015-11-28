<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('fragment'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>碎片数据管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">碎片数据管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr align="left" class="head">
		<td width="5%" height="36" class="firstCol">ID</td>
		<td width="50%">标识名称</td>
		<td width="30%">更新时间</td>
		<td width="15%" class="endCol">操作</td>
	</tr>
	<?php

	$sql = "SELECT * FROM `#@__fragment`";

	$dopage->GetPage($sql,$cfg_pagenum,'ASC');
	while($row = $dosql->GetArray())
	{
	?>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol"><?php echo $row['id']; ?></td>
		<td><?php echo $row['title']; ?></td>
		<td class="number"><?php echo GetDateTime($row['posttime']); ?></td>
		<td class="action endCol"><span><a href="fragment_update.php?id=<?php echo $row['id']; ?>">修改</a></span> | <span class="nb"><a href="fragment_save.php?action=del2&id=<?php echo $row['id']; ?>">删除</a></span></td>
	</tr>
	<?php
	}
	?>
</table>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<div class="bottomToolbar"> <a href="fragment_add.php" class="dataBtn">添加碎片数据</a> </div>
<ul class="tipsList">
	<li>【碎片数据】一般用于页面中的数据需要后台更新，但不需要建立栏目的模块</li>
</ul>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <a href="fragment_add.php" class="dataBtn">添加碎片数据</a> <span class="pageSmall"><?php echo $dopage->GetListSmall(); ?></span> </div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>