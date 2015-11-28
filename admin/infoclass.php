<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('infoclass'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>栏目管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">栏目管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" onclick="CheckAll(this.checked);" /></td>
			<td width="3%">ID</td>
			<td width="40%">栏目名称</td>
			<td width="20%" align="center">排序</td>
			<td width="32%" class="endCol">操作</td>
		</tr>
	</table>
	<?php

	//权限验证
	if($cfg_adminlevel != 1)
	{
		//初始化参数
		$catgoryListPriv   = array();
		$catgoryAddPriv    = array();
		$catgoryUpdatePriv = array();
		$catgoryDelPriv    = array();

		$dosql->Execute("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`='category'");
		while($row = $dosql->GetArray())
		{
			//查看权限
			if($row['action'] == 'list')
				$catgoryListPriv[]   = $row['classid'];

			//添加权限
			if($row['action'] == 'add')
				$catgoryAddPriv[]    = $row['classid'];

			//修改权限
			if($row['action'] == 'update')
				$catgoryUpdatePriv[] = $row['classid'];

			//删除权限
			if($row['action'] == 'del')
				$catgoryDelPriv[]    = $row['classid'];

		}
	}


	//循环栏目函数
	function Show($id=0, $i=0)
	{
		global $dosql,$cfg_siteid,$cfg_adminlevel,
		       $catgoryListPriv,$catgoryAddPriv,
			   $catgoryUpdatePriv,$catgoryDelPriv;

		$i++;

		$dosql->Execute("SELECT * FROM `#@__infoclass` WHERE `siteid`='$cfg_siteid' AND `parentid`=$id ORDER BY `orderid` ASC", $id);
		while($row = $dosql->GetArray($id))
		{

			switch($row['infotype'])
			{
				case 0:
					$addurl   = 'info_update.php?id='.$row['id'];
					$infotype = ' <i title="栏目属于[单页]类型">[单页]</i>';
					break;  
				case 1:
					$addurl   = 'infolist_add.php?cid='.$row['id'];
					$infotype = ' <i title="栏目属于[列表]类型">[列表]</i>';
					break;
				case 2:
					$addurl   = 'infoimg_add.php?cid='.$row['id'];
					$infotype = ' <i title="栏目属于[图片]类型">[图片]</i>';
					break;
				case 3:
					$addurl   = 'soft_add.php?cid='.$row['id'];
					$infotype = ' <i title="栏目属于[下载]类型">[下载]</i>';
					break;
				case 4:
					$addurl   = 'goods_add.php?cid='.$row['id'];
					$infotype = ' <i title="栏目属于[商品]类型">[商品]</i>';
					break;
				default:
					$r = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `id`=".$row['infotype']);
					if(isset($r) && is_array($r))
					{
						$addurl   = 'modeldata_add.php?m='.$r['modelname'].'&cid='.$row['id'];
						$infotype = ' <i title="栏目属于['.$r['modeltitle'].']类型">['.$r['modeltitle'].']</i>';
					}
					else
					{
						$addurl   = 'javascript:;';
						$infotype = ' 没有获取到类型';
					}
					
			}


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


			//添加权限
			if($cfg_adminlevel != 1)
			{
				if(in_array($row['id'], $catgoryAddPriv))
				{
					$classname .= '<a href="'.$addurl.'" title="点击添加内容">'.$row['classname'].'</a></span>';
					$addStr = '<a href="infoclass_add.php?infotype='.$row['infotype'].'&id='.$row['id'].'">添加子栏目</a>';
				}
				else
				{
					$classname .= '<span title="暂无添加权限哦~">'.$row['classname'].'</span></span>';
					$addStr = '添加子栏目';
				}
			}
			else
			{
				$classname .= '<a href="'.$addurl.'" title="点击添加内容">'.$row['classname'].'</a></span>';
				$addStr = '<a href="infoclass_add.php?infotype='.$row['infotype'].'&id='.$row['id'].'">添加子栏目</a>';
			}
			
			
			//信息类型
			$classname .= '<span class="infoTypeTxt">'.$infotype.'</span>';
			
			
			//修改权限
			if($cfg_adminlevel != 1)
			{
				if(in_array($row['id'], $catgoryUpdatePriv))
					$updateStr = '<a href="infoclass_update.php?id='.$row['id'].'">修改</a>';
				else
					$updateStr = '修改';
			}
			else
			{
				$updateStr = '<a href="infoclass_update.php?id='.$row['id'].'">修改</a>';
			}


			//删除权限
			if($cfg_adminlevel != 1)
			{
				if(in_array($row['id'], $catgoryDelPriv))
					$delStr = '<a href="infoclass_save.php?action=delclass&id='.$row['id'].'" onclick="return ConfDel(2);">删除</a>';
				else
					$delStr = '删除';
			}
			else
			{
				$delStr = '<a href="infoclass_save.php?action=delclass&id='.$row['id'].'" onclick="return ConfDel(2);">删除</a>';
			}


			//审核状态
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


			//审核权限
			if($cfg_adminlevel != 1)
			{
				if(in_array($row['id'], $catgoryUpdatePriv))
					$checkStr = '<a href="infoclass_save.php?action=check&id='.$row['id'].'&checkinfo='.$row['checkinfo'].'" title="点击进行显示与隐藏操作">'.$checkinfo.'</a>';
				else
					$checkStr = $checkinfo;
			}
			else
			{
				$checkStr = '<a href="infoclass_save.php?action=check&id='.$row['id'].'&checkinfo='.$row['checkinfo'].'" title="点击进行显示与隐藏操作">'.$checkinfo.'</a>';
			}

	?>
	<div rel="rowpid_<?php echo GetTopID($row['parentstr']); ?>">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
			<tr align="left" class="dataTr">
				<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
				<td width="3%"><?php echo $row['id']; ?>
					<input type="hidden" name="id[]" id="id[]" value="<?php echo $row['id']; ?>" /></td>
				<td width="40%"><?php echo $classname; ?></td>
				<td width="20%" align="center"><a href="infoclass_save.php?action=up&id=<?php echo $row['id']; ?>&parentid=<?php echo $row['parentid']; ?>&orderid=<?php echo $row['orderid']; ?>" class="leftArrow" title="提升排序"></a>
					<input type="text" name="orderid[]" id="orderid[]" class="inputls" value="<?php echo $row['orderid']; ?>" />
					<a href="infoclass_save.php?action=down&id=<?php echo $row['id']; ?>&parentid=<?php echo $row['parentid']; ?>&orderid=<?php echo $row['orderid']; ?>" class="rightArrow" title="下降排序"></a></td>
				<td width="32%" class="action endCol"><span><?php echo $addStr; ?></span> | <span><?php echo $checkStr; ?></span> | <span><?php echo $updateStr; ?></span> | <span class="nb"><?php echo $delStr; ?></span></td>
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
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('infoclass_save.php?action=delallclass');" onclick="return ConfDelAll(1);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('infoclass_save.php');">排序</a> - <a href="javascript:ShowAllRows();">展开</a> - <a href="javascript:HideAllRows();">隐藏</a></span> <a href="infoclass_add.php" class="dataBtn">添加网站栏目</a> </div>
<div class="page">
	<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__infoclass',$cfg_siteid); ?></span>条记录</div>
</div>

<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('infoclass_save.php?action=delallclass');" onclick="return ConfDelAll(1);">删除</a>　<span>操作：</span><a href="javascript:UpOrderID('infoclass_save.php');">排序</a> - <a href="javascript:ShowAllRows();">展开</a> - <a href="javascript:HideAllRows();">隐藏</a></span> <a href="infoclass_add.php" class="dataBtn">添加网站栏目</a><span class="pageSmall">
			<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__infoclass',$cfg_siteid); ?></span>条记录</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>