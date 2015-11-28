<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('goodstype'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>商品分类管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">商品分类管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" onclick="CheckAll(this.checked);" /></td>
			<td width="3%">ID</td>
			<td width="40%">类别名称</td>
			<td width="20%" align="center" abbr="center">排序</td>
			<td width="32%" class="endCol">操作</td>
		</tr>
	</table>
	<?php
	function Show($id=0, $i=0)
	{
		global $dosql;

		$i++;

		$dosql->Execute("SELECT * FROM `#@__goodstype` WHERE parentid=$id ORDER BY `orderid` ASC", $id);
		while($row = $dosql->GetArray($id))
		{

			//设置$classname
			$classname = '';


			//设置空格
			for($n = 1; $n < $i; $n++)
				$classname .= '&nbsp;&nbsp;';


			//设置折叠
			if($row['parentid'] == '0')
				$classname .= '<span class="minusSign" id="rowid_'.$row['id'].'" onclick="DisplayRows('.$row['id'].');">';
			else
				$classname .= '<span class="subType">';


			$classname .= $row['classname'].'</span>';


			//显示状态
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
	<div rel="rowpid_<?php echo GetTopID($row['parentstr']); ?>">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
			<tr align="left" class="dataTr">
				<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id'] ?>" /></td>
				<td width="3%"><?php echo $row['id']; ?>
					<input type="hidden" name="id[]" id="id[]" value="<?php echo $row['id']; ?>" /></td>
				<td width="40%"><?php echo $classname; ?></td>
				<td width="20%" align="center"><a href="goodstype_save.php?action=up&id=<?php echo $row['id']; ?>&parentid=<?php echo $row['parentid']; ?>&orderid=<?php echo $row['orderid']; ?>" class="leftArrow" title="提升排序"></a>
					<input type="text" name="orderid[]" id="orderid[]" class="inputls" value="<?php echo $row['orderid']; ?>" />
					<a href="goodstype_save.php?action=down&id=<?php echo $row['id']; ?>&parentid=<?php echo $row['parentid']; ?>&orderid=<?php echo $row['orderid']; ?>" class="rightArrow" title="下降排序"></a></td>
				<td width="32%" class="action endCol"><span><a href="goodstype_add.php?id=<?php echo $row['id']; ?>">添加子类别</a></span> | <span><a href="goodsattr.php?gid=<?php echo $row['id']; ?>">属性管理</a></span> | <span><a href="goodstype_save.php?id=<?php echo $row['id']; ?>&action=check&checkinfo=<?php echo $row['checkinfo']; ?>" title="点击进行显示与隐藏操作"><?php echo $checkinfo; ?></a></span> | <span><a href="goodstype_update.php?id=<?php echo $row['id']; ?>">修改</a></span> | <span class="nb"><a href="goodstype_save.php?action=del&id=<?php echo $row['id'] ?>" onclick="return ConfDel(2);">删除</a></span></td>
			</tr>
		</table>
	</div>
	<?php
			Show($row['id'], $i+2);
		}
	}
	Show();
	

	//判断无记录样式
	if($dosql->GetTotalRow(0) == 0)
	{
		echo '<div class="dataEmpty">暂时没有相关的记录</div>';
	}
	
	
	//判断类别页是否折叠
	if($cfg_typefold == 'Y')
	{
		echo '<script>HideAllRows();</script>';
	}
	?>
</form>
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAll('goodstype_save.php');" onclick="return ConfDelAll(2);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('goodstype_save.php');">排序</a> - <a href="javascript:ShowAllRows();">展开</a> - <a href="javascript:HideAllRows();">隐藏</a></span> <a href="goodstype_add.php" class="dataBtn">添加商品类别</a></div>
<div class="page">
	<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__goodstype'); ?></span>条记录</div>
</div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAll('goodstype_save.php');" onclick="return ConfDelAll(2);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('goodstype_save.php');">排序</a> - <a href="javascript:ShowAllRows();">展开</a> - <a href="javascript:HideAllRows();">隐藏</a></span> <a href="goodstype_add.php" class="dataBtn">添加商品类别</a> <span class="pageSmall">
			<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__goodstype'); ?></span>条记录</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>