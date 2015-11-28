<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('admin'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">管理员管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr align="left" class="head">
		<td width="5%" height="36" class="firstCol">ID</td>
		<td width="25%">用户名</td>
		<td width="20%">管理组</td>
		<td width="20%">登录时间</td>
		<td width="15%">登录IP</td>
		<td width="15%" class="endCol">操作</td>
	</tr>
	<?php

	$sql = "SELECT * FROM `#@__admin`";


	//如果非超级管理员,不显示超级管理员记录
	if($cfg_adminlevel != 1)
		$sql .= " WHERE `levelname`>1";


	$dopage->GetPage($sql,$cfg_pagenum,'ASC');
	while($row = $dosql->GetArray())
	{
		$r = $dosql->GetOne("SELECT `groupname` FROM `#@__admingroup` WHERE `id`=".$row['levelname']);
		$groupname = isset($r['groupname']) ? $r['groupname'] : '';

		switch($row['checkadmin'])
		{
			case 'true':
				$checkadmin = '已审';
				break;  
			case 'false':
				$checkadmin = '未审';
				break;
			default:
				$checkadmin = '没有获取到参数';
		}

	
		if($row['id'] == 1)
			$checkstr = '已审';
		else
			$checkstr = '<a href="admin_save.php?action=check&id='.$row['id'].'&checkadmin='.$row['checkadmin'].'" title="点击进行审核与未审操">'.$checkadmin.'</a>';


		if($row['id'] == 1)
			$delstr = '删除';
		else
			$delstr = '<a href="admin_save.php?action=del&id='.$row['id'].'" onclick="return ConfDel(0);">删除</a>';
	?>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol"><?php echo $row['id']; ?></td>
		<td><?php echo $row['username']; ?></td>
		<td><?php echo $groupname; ?></td>
		<td class="number"><?php echo GetDateTime($row['logintime']); ?></td>
		<td><?php echo $row['loginip']; ?></td>
		<td class="action endCol"><span><?php echo $checkstr; ?></span> | <span><a href="admin_update.php?id=<?php echo $row['id']; ?>">修改</a></span> | <span class="nb"><?php echo $delstr; ?></span></td>
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
<div class="bottomToolbar"> <a href="admin_add.php" class="dataBtn">添加管理员</a> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <a href="admin_add.php" class="dataBtn">添加管理员</a> <span class="pageSmall">
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