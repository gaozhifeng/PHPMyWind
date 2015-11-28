<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('diymenu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>自定义菜单管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">自定义菜单管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="diymenu_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="30%">菜单项名称</td>
			<td width="20%">跳转链接</td>
			<td width="25%" align="center">排序</td>
			<td width="20%" class="endCol">操作</td>
		</tr>
		<?php
		$dosql->Execute("SELECT * FROM `#@__diymenu` WHERE `siteid`='$cfg_siteid' AND `parentid`=0 ORDER BY `orderid` ASC");
		while($row = $dosql->GetArray())
		{
			switch($row['checkinfo'])
			{
				case 'true':
					$checkinfo = '显示';
					break;  
				case 'false':
					$checkinfo = '隐藏';
					break;
				default:
					$checkinfo = '没有获取到参数';
			}
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id'] ?>" /></td>
			<td><?php echo $row['classname']; ?>
				<input type="hidden" name="id[]" id="id[]" value="<?php echo $row['id']; ?>" /></td>
			<td>&nbsp;</td>
			<td align="center"><a href="diymenu_save.php?action=up&id=<?php echo $row['id']; ?>&parentid=<?php echo $row['parentid']; ?>&orderid=<?php echo $row['orderid']; ?>" class="leftArrow" title="提升排序"></a>
				<input type="text" name="orderid[]" id="orderid[]" class="inputls" value="<?php echo $row['orderid']; ?>" />
				<a href="diymenu_save.php?action=down&id=<?php echo $row['id']; ?>&parentid=<?php echo $row['parentid']; ?>&orderid=<?php echo $row['orderid']; ?>" class="rightArrow" title="下降排序"></a></td>
			<td class="action endCol"><span><a href="diymenu_save.php?action=check&id=<?php echo $row['id']; ?>&checkinfo=<?php echo $row['checkinfo']; ?>" title="点击进行显示与隐藏操作"><?php echo $checkinfo; ?></a></span> | <span><a href="diymenu_update.php?id=<?php echo $row['id']; ?>">修改</a></span> | <span class="nb"><a href="diymenu_save.php?action=deldiymenu&id=<?php echo $row['id'] ?>" onclick="return ConfDel(0);">删除</a></span></td>
		</tr>
		<?php
		$dosql->Execute("SELECT * FROM `#@__diymenu` WHERE `parentid`=".$row['id']." ORDER BY `orderid` ASC", $row['id']);
		while($row2 = $dosql->GetArray($row['id']))
		{
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row2['id'] ?>" /></td>
			<td><span class="subType"><?php echo $row2['classname']; ?>
				<input type="hidden" name="id[]" id="id[]" value="<?php echo $row2['id']; ?>" />
				</span></td>
			<td><?php echo $row2['linkurl']; ?></td>
			<td align="center"><a href="diymenu_save.php?action=up&id=<?php echo $row2['id']; ?>&parentid=<?php echo $row2['parentid']; ?>&orderid=<?php echo $row2['orderid']; ?>" class="leftArrow" title="提升排序"></a>
				<input type="text" name="orderid[]" id="orderid[]" class="inputls" value="<?php echo $row2['orderid']; ?>" />
				<a href="diymenu_save.php?action=down&id=<?php echo $row2['id']; ?>&parentid=<?php echo $row2['parentid']; ?>&orderid=<?php echo $row2['orderid']; ?>" class="rightArrow" title="下降排序"></a></td>
			<td>&nbsp;</td>
		</tr>
		<?php
		}
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
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('diymenu_save.php?action=delalldiymenu');" onclick="return ConfDelAll(0);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('diymenu_save.php');">更新排序</a></span> <a href="diymenu_add.php" class="dataBtn">添加菜单项</a> </div>
<ul class="tipsList">
	<li>用于非超级管理员登录后台菜单的显示，添加自定义菜单名称以及连接，保存后则会出现在菜单列表中。可以自定义需要的菜单项，使后台菜单更加灵活</li>
</ul>
<div class="page">
	<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__diymenu',$cfg_siteid); ?></span>条记录</div>
</div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea">  <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('diymenu_save.php?action=delalldiymenu');" onclick="return ConfDelAll(0);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('diymenu_save.php');">更新排序</a></span> <a href="diymenu_add.php" class="dataBtn">添加菜单项</a> <span class="pageSmall">
			<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__diymenu',$cfg_siteid); ?></span>条记录</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>