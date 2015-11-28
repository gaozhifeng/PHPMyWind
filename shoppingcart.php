<?php	require_once(dirname(__FILE__).'/include/config.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2013-1-14 13:23:24
person: Feng
**************************
*/


//初始化参数
$a = isset($a) ? $a : '';


//添加购物车
if($a == 'addshopingcart')
{

	 //构成选中属性
	if(isset($typeid))
	{
		//参数过滤
		$typeid = intval($typeid);

		//获取商品属性
		$dosql->Execute("SELECT * FROM `#@__goodsattr` WHERE `goodsid`=$typeid");
		if($dosql->GetTotalRow() > 0)
		{
			//构成属性字符串
			$goodsattr = array();
			while($row = $dosql->GetArray())
			{
				//选中的属性构成字符串
				if(isset($_POST['attrid_'.$row['id']]))
				{
					$goodsattr[$row['id']] = $_POST['attrid_'.$row['id']];
				}
			}
		}
		else
		{
			$goodsattr[$row['id']] = '';
		}
	}


	//初始化购物车字符串
	if(!empty($_COOKIE['shoppingcart']))
		$shoppingcart = unserialize(AuthCode($_COOKIE['shoppingcart']));
	else
		$shoppingcart = array();


	//选中信息存入数组
	if(isset($goodsid) &&
	   isset($buynum) &&
	   isset($goodsattr))
	{
		//过滤参数
		$goodsid = intval($goodsid);
		$buynum  = intval($buynum);

		$shoppingcart[] = array($goodsid, $buynum, $goodsattr);
	}


	//存入COOKIE
	setcookie('shoppingcart', AuthCode(serialize($shoppingcart),'ENCODE'));
	echo TRUE;
	exit();
}


//移除一条购物记录
else if($a == 'delrow')
{
	if(!empty($_COOKIE['shoppingcart']))
	{
		//获取COOKIE值
		$shoppingcart = unserialize(AuthCode($_COOKIE['shoppingcart']));

		//去除数组中特定元素
		unset($shoppingcart[$key]);

		//更新后的COOKIE
		if(empty($shoppingcart))
			setcookie('shoppingcart', '', time()-3600);
		else
			setcookie('shoppingcart', AuthCode(serialize($shoppingcart),'ENCODE'));

		header('location:shoppingcart.php');
		exit();
	}
}


//清空购物车
else if($a == 'empty')
{
	setcookie('shoppingcart', '', time()-3600);
	header('location:shoppingcart.php');
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo GetHeader(0,0,'购物车'); ?>
<link href="templates/default/style/webstyle.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/default/js/top.js"></script>
</head>
<body>
<!-- header-->
<?php require_once('header.php'); ?>
<!-- /header-->
<!-- banner-->
<div class="subBanner"> <img src="templates/default/images/banner-ir.png" /> </div>
<!-- /banner-->
<!-- notice-->
<div class="subnotice"><strong>网站公告：</strong> <?php echo Info(1); ?> </div>
<!-- /notice-->
<!-- mainbody-->
<div class="subBody">
	<div class="subTitle" style="margin:0;"> <span class="catname shopcart">商品购物车</span> <a href="shoppingcart.php?a=empty"><strong>清空购物车</strong></a>
		<div class="cl"></div>
	</div>
	<?php

	if(!empty($_COOKIE['shoppingcart']))
	{
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="shoppingcart">
		<tr class="thead">
			<td width="65%" height="30">&nbsp;&nbsp;&nbsp;商品名称</td>
			<td width="15%">购买数量</td>
			<td width="15%">价格</td>
			<td width="5%">操作</td>
		</tr>
		<tr>
			<td height="10" colspan="4"></td>
		</tr>
		<?php

		//初始化参数
		$totalprice = '';
		$shoppingcart = unserialize(AuthCode($_COOKIE['shoppingcart']));

		//显示订单列表
		foreach($shoppingcart as $k=>$goods)
		{
		?>
		<tr>
			<td height="30">
			<?php

			//获取数据库中商品信息
			$r = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE `id`=".intval($goods[0]));

			//计算订单总价
			$totalprice += $r['salesprice'] * $goods[1];

			//输出商品名称
			echo '<a href="goodsshow.php?cid='.$r['classid'].'&tid='.$r['typeid'].'&id='.$r['id'].'" class="title" target="_blank">'.$r['title'].'</a>';

			//输出选中属性
			foreach($goods[2] as $v)
			{
				echo '<span class="attr">'.$v.'</span>';
			}
			?>
			</td>
			<td><?php echo $goods[1]; ?></td>
			<td><?php echo $r['salesprice']*$goods[1]; ?></td>
			<td><a href="shoppingcart.php?a=delrow&key=<?php echo $k; ?>">取消</a></td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td height="60" colspan="4" align="right" valign="bottom"><strong class="total">总计：</strong><span class="totalprice"><?php echo $totalprice; ?></span><a href="order.php" class="next">下一步</a></td>
		</tr>
	</table>
	<?php
	}
	else
	{
		echo '<div class="shoppingcartempty">您的购物车目前没有商品！</div>';
	}
	?>
</div>
<!-- /mainbody-->
<!-- footer-->
<?php require_once('footer.php'); ?>
<!-- /footer-->
</body>
</html>
