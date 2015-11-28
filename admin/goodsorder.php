<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('goodsorder'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>商品订单管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">商品订单管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<div class="toolbarTab">
	<ul>
		<?php
		
		//初始化参数
		$flag       = isset($flag)    ? $flag    : 'all';
		$keyword    = isset($keyword) ? $keyword : '';


		$flagArr = array('all'=>'全部',
						 'core'=>'星标',
						 'empty'=>'未审',
						 'confirm'=>'确认',
						 'payment'=>'付款',
						 'postgoods'=>'发货',
						 'getgoods'=>'收货',
						 'applyreturn'=>'申退',
						 'agreedreturn'=>'退货',
						 'goodsback'=>'返货',
						 'moneyback'=>'退款',
						 'overorder'=>'归档');


		$flagArrNum = count($flagArr);
		$i = 1;
		foreach($flagArr as $k => $v)
		{
			if($flag == $k)
				$flagOn = 'on';
			else
				$flagOn = '';

			echo '<li class="'.$flagOn.'"><a href="?flag='.$k.'">'.$v.'</a></li>';
			
			if($i < $flagArrNum)
				echo '<li class="line">-</li>';
			
			$i++;
		}
		?>
	</ul>
	<div class="search">
		<form name="forms" id="forms" method="get" action="">
			<span class="s">
			<input name="keyword" id="keyword" type="text" title="输入用户名进行搜索" value="<?php echo $keyword; ?>" />
			</span> <span class="b"><a href="javascript:;" onclick="forms.submit();"></a></span>
		</form>
	</div>
</div>
<form name="form" id="form" method="post" action="">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="5%">ID</td>
			<td width="13%">用户名</td>
			<td width="15%">订单编号</td>
			<td width="15%">联系人/电话</td>
			<td width="10%">金额</td>
			<td width="15%">更新时间</td>
			<td width="10%">订单状态</td>
			<td width="12%" class="endCol">操作</td>
		</tr>
		<?php
		$sql = "SELECT * FROM `#@__goodsorder` WHERE `delstate`=''";	
	
		if($flag == 'core')
			$sql .= " AND `core`='true'";	

		if($flag == 'empty')
			$sql .= " AND `checkinfo`=''";	

		if($flag != 'all' && $flag != 'core' && $flag != 'empty')
			$sql .= " AND `checkinfo` LIKE '%$flag%'";

		if($keyword != '')
			$sql .= " AND `username` LIKE '%$keyword%'";


		$dopage->GetPage($sql);
		while($row = $dosql->GetArray())
		{						
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['username']; ?></td>
			<td><?php echo $row['ordernum']; ?></td>
			<td><?php echo $row['truename'].'/'.$row['telephone']; ?></td>
			<td><?php echo $row['amount']; ?></td>
			<td class="number"><?php echo GetDateTime($row['posttime']); ?></td>
			<td class="blue"><?php
			$checkinfo = explode(',',$row['checkinfo']);
			
			if($row['paymode'] == 1)
			{
				if(!in_array('applyreturn',  $checkinfo) &&
				   !in_array('agreedreturn', $checkinfo) &&
				   !in_array('goodsback',    $checkinfo) &&
				   !in_array('moneyback',    $checkinfo) &&
				   !in_array('overorder',    $checkinfo))
				{
					if($row['checkinfo'] == '' or
					   !in_array('confirm',        $checkinfo))
						echo '等待确认';
	
					else if(!in_array('payment',   $checkinfo))
						echo '等待付款';
	
					else if(!in_array('postgoods', $checkinfo))
						echo '等待发货';
	
					else if(!in_array('getgoods',  $checkinfo))
						echo '等待收货';
	
					else if(!in_array('overorder', $checkinfo))
						echo '等待归档';
	
					else
						echo '参数错误，没有获取到订单状态';
				}
				else
				{
					if(in_array('overorder',         $checkinfo))
						echo '已归档';
					
					else if(in_array('moneyback',    $checkinfo))
						echo '等待归档';
	
					else if(in_array('goodsback',    $checkinfo))
						echo '等待退款';
	
					else if(in_array('agreedreturn', $checkinfo))
						echo '等待返货';
	
					else if(in_array('applyreturn',  $checkinfo))
						echo '申请退货';
	
					else
						echo '参数错误，没有获取到订单状态';
				}
			}

			else if($row['paymode'] == 2)
			{
				if(!in_array('applyreturn',  $checkinfo) &&
				   !in_array('agreedreturn', $checkinfo) &&
				   !in_array('goodsback',    $checkinfo) &&
				   !in_array('moneyback',    $checkinfo) &&
				   !in_array('overorder',    $checkinfo))
				{
					if($row['checkinfo'] == '' or
					   !in_array('confirm', $checkinfo))
						echo '等待确认';

					else if(!in_array('postgoods', $checkinfo))
						echo '等待发货';
	
					else if(!in_array('getgoods',  $checkinfo))
						echo '等待收货';
					
					else if(!in_array('payment',   $checkinfo))
						echo '等待付款';
	
					else if(!in_array('overorder', $checkinfo))
						echo '等待归档';
	
					else
						echo '参数错误，没有获取到订单状态';
				}
				else
				{
					if(in_array('overorder',         $checkinfo))
						echo '已归档';
					
					else if(in_array('moneyback',    $checkinfo))
						echo '等待归档';
	
					else if(in_array('goodsback',    $checkinfo))
						echo '等待退款';
	
					else if(in_array('agreedreturn', $checkinfo))
						echo '等待返货';
	
					else if(in_array('applyreturn', $checkinfo))
						echo '申请退货';
	
					else
						echo '参数错误，没有获取到订单状态';
				}
			}
			?></td>
			<td class="action endCol"><span><a href="goodsorder_update.php?id=<?php echo $row['id']; ?>">修改</a></span> | <span class="nb"><a href="goodsorder_save.php?action=del2&id=<?php echo $row['id']; ?>" onclick="return ConfDel(0)">删除</a></span></td>
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
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('goodsorder_save.php');" onclick="return ConfDelAll(0);">删除</a></span> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('goodsorder_save.php');" onclick="return ConfDelAll(0);">删除</a></span> <span class="pageSmall">
			<div class="pageText"><?php echo $dopage->GetList(); ?></div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>