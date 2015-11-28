<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('diymodel'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>自定义模型</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">自定义模型</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="diymodel_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol">ID</td>
			<td width="30%" align="left" class="title">模型名称</td>
			<td width="25%" align="left">模型标识</td>
			<td width="20%" align="left">模型表名</td>
			<td width="20%" class="endCol">操作</td>
		</tr>
		<?php

		$sql = "SELECT * FROM `#@__diymodel`";

		$dopage->GetPage($sql);
		while($row = $dosql->GetArray())
		{
			switch($row['checkinfo'])
			{
				case 'true':
					$checkinfo = '启用';
					break;  
				case 'false':
					$checkinfo = '禁用';
					break;
				default:
					$checkinfo = '没有获取到参数';
			}
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><?php echo $row['id']; ?></td>
			<td><?php echo $row['modeltitle']; ?> <a href="modeldata.php?m=<?php echo $row['modelname']; ?>" title="管理<?php echo $row['modeltitle']; ?>信息">[进入信息管理]</a></td>
			<td><?php echo $row['modelname']; ?></td>
			<td><?php echo $row['modeltbname']; ?></td>
			<td class="action endCol"><span><a href="diymodel_save.php?id=<?php echo $row['id']; ?>&action=check&checkinfo=<?php echo $row['checkinfo']; ?>" title="点击进行审核与未审操作"><?php echo $checkinfo; ?></a></span> | <span><a href="diyfield.php?mid=<?php echo $row['id']; ?>">字段</a></span> | <span><a href="diymodel_update.php?id=<?php echo $row['id']; ?>">修改</a></span> | <span class="nb"><a href="diymodel_save.php?action=del&id=<?php echo $row['id']; ?>" onclick="return ConfDel(0)">删除</a></span></td>
		</tr>
		<?php
		}
		?>
	</table>
</form>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<div class="bottomToolbar"> <a href="diymodel_add.php" class="dataBtn">添加新模型</a> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <a href="diymodel_add.php" class="dataBtn">添加新模型</a> <span class="pageSmall"><?php echo $dopage->GetListSmall(); ?></span> </div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>