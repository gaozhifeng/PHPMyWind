<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('info'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>单页信息管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">单页信息管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr align="left" class="head">
		<td width="5%" height="36" class="firstCol">ID</td>
		<td width="40%">单页名称</td>
		<td width="40%">更新时间</td>
		<td width="15%" class="endCol">操作</td>
	</tr>
	<?php
	
	//权限验证
	if($cfg_adminlevel != 1)
	{
		//初始化参数
		$catgoryListPriv   = array();
		$catgoryUpdatePriv = array();

		$dosql->Execute("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`='category' AND `action`<>'add' AND `action`<>'del'");
		while($row = $dosql->GetArray())
		{
			//查看权限
			if($row['action'] == 'list')
				$catgoryListPriv[]   = $row['classid'];
			
			//修改权限
			if($row['action'] == 'update')
				$catgoryUpdatePriv[] = $row['classid'];
		}
	}


	//循环单页
	$dopage->GetPage("SELECT * FROM `#@__infoclass` WHERE `siteid`='$cfg_siteid' AND `infotype`=0",$cfg_pagenum,'ASC');
	while($row = $dosql->GetArray())
	{
		//如果超级管理员直接显示
		if($cfg_adminlevel != 1)
		{
			if(in_array($row['id'],$catgoryListPriv))
			{
	?>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol"><?php echo $row['id']; ?></td>
		<td><?php echo $row['classname']; ?></td>
		<td class="number"><?php
		$r = $dosql->GetOne("SELECT `content`,`posttime` FROM `#@__info` WHERE `classid`=".$row['id']);
		if(isset($r['content']))
			echo GetDateTime($r['posttime']);
		else
			echo '暂无内容';
		?></td>
		<td class="action endCol"><?php

		//是否有修改权限
		if(in_array($row['id'],$catgoryUpdatePriv))
			echo '[<a href="info_update.php?id='.$row['id'].'">修改</a>]';
		else
			echo '[修改]';
		?></td>
	</tr>
	<?php
			}
		}
		else
		{
	?>
	<tr align="left" class="dataTr">
		<td height="36" class="firstCol"><?php echo $row['id']; ?></td>
		<td><?php echo $row['classname']; ?></td>
		<td class="number"><?php
		$r = $dosql->GetOne("SELECT `content`,`posttime` FROM `#@__info` WHERE `classid`=".$row['id']);
		if(isset($r['content']))
			echo GetDateTime($r['posttime']);
		else
			echo '暂无内容';
		?></td>
		<td class="action endCol"><a href="info_update.php?id=<?php echo $row['id']; ?>">修改</a></td>
	</tr>
	<?php
		}
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
<div class="bottomToolbar"> <a href="infoclass.php" class="dataBtn">返回栏目管理</a> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><a href="infoclass.php" class="dataBtn">返回栏目管理</a> <span class="pageSmall"><?php echo $dopage->GetListSmall(); ?></span> </div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>