<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('goodstype'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>商品属性管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<?php

//初始化商品类型
$gid = !empty($gid) ? intval($gid) : 0;

$r = $dosql->GetOne("SELECT `classname` FROM `#@__goodstype` WHERE `id`=$gid");
if(isset($r) && is_array($r))
	$classname = $r['classname'];
else
	$classname = '';

?>
<div class="topToolbar"> <span class="title">商品属性管理</span> <span class="text">当前分类：<a href="goodstype.php" title="点击返回商品分类"><?php echo $classname; ?></a></span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="goodsattr_save.php?action=save">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="5%">ID</td>
			<td width="40%">属性名称</td>
			<td width="30%" align="center">排序</td>
			<td width="20%" class="endCol">操作</td>
		</tr>
		<?php

		if($gid != '')
		{
			$dosql->Execute("SELECT * FROM `#@__goodsattr` WHERE `goodsid`=$gid ORDER BY `orderid` ASC");
			if($dosql->GetTotalRow() > 0)
			{
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
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php echo $row['id']; ?>
				<input name="id[]" type="hidden" id="id[]" value="<?php echo $row['id']; ?>" /></td>
			<td><input name="attrname[]" type="text" id="attrname[]" class="inputd" value="<?php echo $row['attrname']; ?>" /></td>
			<td align="center"><a href="goodsattr_save.php?gid=<?php echo $gid; ?>&id=<?php echo $row['id']; ?>&orderid=<?php echo $row['orderid']; ?>&action=up" class="leftArrow" title="提升排序"></a>
				<input name="orderid[]" type="text" id="orderid[]" class="inputls" value="<?php echo $row['orderid']; ?>" />
				<a href="goodsattr_save.php?gid=<?php echo $gid; ?>&id=<?php echo $row['id']; ?>&orderid=<?php echo $row['orderid']; ?>&action=down" class="rightArrow" title="下降排序"></a></td>
			<td class="action endCol"><span><a href="goodsattr_save.php?action=check&gid=<?php echo $gid; ?>&id=<?php echo $row['id']; ?>&checkinfo=<?php echo $row['checkinfo']; ?>" title="点击进行显示与隐藏操作"><?php echo $checkinfo; ?></a></span> | <span class="nb"><a href="goodsattr_save.php?action=del2&gid=<?php echo $gid; ?>&id=<?php echo $row['id'] ?>" onclick="return ConfDel(0);">删除</a></span></td>
		</tr>
		<?php
				}
			}
			else
			{
		?>
		<tr align="center">
			<td colspan="6" class="dataEmpty">暂时没有相关的记录</td>
		</tr>
		<?php
			}
		}
		else
		{
		?>
		<tr align="center">
			<td colspan="6" class="dataEmpty">商品类型获取出错，请返回 <a href="goodstype.php">商品类型管理</a></td>
		</tr>
		<?php
		}
		?>
		<tr align="center">
			<td height="36" colspan="5"><strong>新增一个属性</strong></td>
		</tr>
		<tr align="left" class="dataTrOn">
			<td height="36"><input name="gid" type="hidden" id="gid" value="<?php echo $gid; ?>" /></td>
			<td>&nbsp;</td>
			<td><input type="text" name="attrnameadd" id="attrnameadd" class="input" /></td>
			<td align="center"><input type="text" name="orderidadd" id="orderidadd" class="inputls" value="<?php echo GetOrderID('#@__goodsattr'); ?>" /></td>
			<td class="endCol"><input type="radio" name="checkinfoadd" value="true" checked="checked"  />
				显示&nbsp;
				<input type="radio" name="checkinfoadd" value="false" />
				隐藏</td>
		</tr>
	</table>
</form>
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('goodsattr_save.php');" onclick="return ConfDelAll(0);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('goodsattr_save.php');">更新排序</a></span> <a href="#" onclick="form.submit();" class="dataBtn">更新全部</a> </div>
<div class="page">
	<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__goodsattr'); ?></span>条记录</div>
</div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('goodsattr_save.php');" onclick="return ConfDelAll(0);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('goodsattr_save.php');">更新排序</a></span> <a href="#" onclick="form.submit();" class="dataBtn">更新全部</a><span class="pageSmall">
			<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__goodsattr'); ?></span>条记录</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>