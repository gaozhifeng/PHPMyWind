<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('diyfield'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>自定义字段</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">自定义字段</span> <span class="alltype">
	<?php
	$modelAry = array('单页', '列表', '图片', '下载', '商品');
	if(isset($mid))
	{
		if($mid < 9)
		{
			$cname = $modelAry[$mid];
		}
		if(empty($cname))
		{
			$r = $dosql->GetOne("SELECT `modeltitle` FROM `#@__diymodel` WHERE `id`=$mid");
			$cname = $r['modeltitle']; 
		}
	}
	else
	{
		$cname = '查看全部';
	}
	?>
	<a href="?" class="btn"><?php echo $cname; ?></a> <span class="drop">
    <?php
    foreach($modelAry as $k=>$v)
	{
		echo '<a href="?mid='.$k.'">'.$v.'</a>';
	}

	$sql = "SELECT * FROM `#@__diymodel` ORDER BY `orderid` ASC";
	$dosql->Execute($sql);

	while($row = $dosql->GetArray())
	{
		echo '<a href="?mid='.$row['id'].'">' . $row['modeltitle'] . '</a>';
	}
    ?>
	</span> </span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="diyfield_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol">ID</td>
			<td width="25%">字段名称</td>
			<td width="20%">标识</td>
			<td width="20%">类型</td>
			<td width="15%">所属模型</td>
			<td width="15%" class="endCol">操作</td>
		</tr>
		<?php

		if(isset($mid))
			$sql = "SELECT * FROM `#@__diyfield` WHERE `infotype`=$mid";
		else
			$sql = "SELECT * FROM `#@__diyfield`";

		$dopage->GetPage($sql);
		while($row = $dosql->GetArray())
		{
			switch($row['infotype'])
			{
				case 0:
					$infotype = '单页';
					break;  
				case 1:
					$infotype = '列表';
					break;
				case 2:
					$infotype = '图片';
					break;
				case 3:
					$infotype = '下载';
					break;
				case 4:
					$infotype = '商品';
					break;
				default:
					$r = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `id`=".$row['infotype']);
					if(isset($r) && is_array($r))
						$infotype = $r['modeltitle'];
					else
						$infotype = '没有获取到类型';
			}


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
			<td><?php echo $row['fieldname']; ?></td>
			<td><?php echo $row['fieldtitle']; ?></td>
			<td><?php echo $row['fieldtype']; ?></td>
			<td class="blue"><?php echo $infotype; ?></td>
			<td class="action endCol"><span><a href="diyfield_save.php?action=check&id=<?php echo $row['id']; ?>&checkinfo=<?php echo $row['checkinfo']; ?>" title="点击进行审核与未审操作"><?php echo $checkinfo; ?></a></span> | <span><a href="diyfield_update.php?id=<?php echo $row['id']; ?>">修改</a></span> | <span class="nb"><a href="diyfield_save.php?action=del&infotype=<?php echo $row['infotype']; ?>&id=<?php echo $row['id']; ?>&fieldname=<?php echo $row['fieldname']; ?>" onclick="return ConfDel(0)">删除</a></span></td>
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
<div class="bottomToolbar"> <a href="diyfield_add.php" class="dataBtn">添加新字段</a> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <a href="diyfield_add.php" class="dataBtn">添加新字段</a> <span class="pageSmall"><?php echo $dopage->GetListSmall(); ?></span> </div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>