<?php	require_once(dirname(__FILE__).'/include/config.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-4-20 16:27:40
person: Feng
**************************
*/


//初始化购物车字符串
if(empty($_COOKIE['shoppingcart']))
{
	header('location:shoppingcart.php');
	exit();
}


//初始化订单字符串
if(empty($_COOKIE['orderinfo']))
{
	header('location:shoppingcart.php');
	exit();
}


//不允许游客下单跳转登录
if(empty($_COOKIE['username']))
{
	header('location:member.php?c=login');
	exit();
}


//初始化参数
$action       = isset($action) ? $action : '';
$shoppingcart = unserialize(AuthCode($_COOKIE['shoppingcart']));
$orderinfo    = unserialize(AuthCode($_COOKIE['orderinfo']));
$totalprice   = '';
$totalweight  = '';


//商品运费
$r = $dosql->GetOne("SELECT `postprice` FROM `#@__postmode` WHERE `id`=".intval($orderinfo['postmode']));
$postprice = $r['postprice'];


//计算订单信息
//显示订单列表
foreach($shoppingcart as $k=>$goods)
{
	//获取数据库中商品信息
	$r = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE `id`=".intval($goods[0]));

	//计算订单总价
	$totalprice += $r['salesprice'] * $goods[1];

	//计算订单总重
	$totalweight += $r['weight'] * $goods[1];
}

//构成总价、总重、运费数组
$priceweight = array('totalprice' => $totalprice,
					 'totalweight' => $totalweight,
					 'cost' => $postprice);

//更新订单信息数组
$orderinfo = array_merge($orderinfo, $priceweight);


//存入COOKIE
setcookie('orderinfo', AuthCode(serialize($orderinfo),'ENCODE'));


//保存订单
if($action == 'save')
{

	//解析COOKIE
	$username = AuthCode($_COOKIE['username']);
	$orderarr = unserialize(AuthCode($_COOKIE['orderinfo']));
	$attrstr  = AuthCode($_COOKIE['shoppingcart']);

	//生成订单序号
	$orderid  = GetOrderID('#@__goodsorder');

	//订单号
	$ordernum = MyDate('Ymd',time()).mt_rand(0,9999);


	$sql = "INSERT INTO `#@__goodsorder` (username, attrstr, truename, idcard, telephone, zipcode, postarea_prov, postarea_city, postarea_country, address, ordernum, postmode, paymode, getmode, weight, cost, amount, buyremark, posttime, orderid, checkinfo) VALUES ('$username', '$attrstr', '".$orderarr['truename']."', '".$orderarr['idcard']."', '".$orderarr['telephone']."', '".$orderarr['zipcode']."', '".$orderarr['postarea_prov']."', '".$orderarr['postarea_city']."', '".$orderarr['postarea_country']."', '".$orderarr['address']."', '$ordernum', '".$orderarr['postmode']."', '".$orderarr['paymode']."', '".$orderarr['getmode']."', '".$orderarr['totalweight']."', '".$orderarr['cost']."', '".$orderarr['totalprice']."', '".$orderarr['buyremark']."', '".$orderarr['posttime']."', '$orderid', 'confirm')";
	if($dosql->ExecNoneQuery($sql))
	{
		setcookie('shoppingcart', '', time()-3600);
		setcookie('orderinfo',    '', time()-3600);

		if($orderarr['paymode'] == 1)
		{
			header('location:orderpay.php?id='.$dosql->GetLastID());
			exit();
		}
    	else
		{
			ShowMsg('订单提交成功！','shoppingcart.php');
			exit();
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo GetHeader(0,0,'订单确认'); ?>
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
	<div class="subTitle" style="margin:0;"> <span class="catname shopcart">订单确认</span>
		<div class="cl"></div>
	</div>
	<form name="form" id="form" method="post" action="">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="shoppingcart">
			<tr class="thead">
				<td width="70%" height="30">&nbsp;&nbsp;&nbsp;商品名称</td>
				<td width="15%">购买数量</td>
				<td width="15%">价格</td>
			</tr>
			<tr>
				<td height="15" colspan="3"></td>
			</tr>
			<?php

			//显示订单列表
			foreach($shoppingcart as $k=>$goods)
			{
			?>
			<tr>
				<td height="30"><?php

				//获取数据库中商品信息
				$r = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE `id`=".intval($goods[0]));

				//计算订单总价
				$totalprice += $r['salesprice'] * $goods[1];

				//计算订单总重
				$totalweight += $r['weight'] * $goods[1];

				//输出商品名称
				echo '<a href="goodsshow.php?cid='.$r['classid'].'&tid='.$r['typeid'].'&id='.$r['id'].'" class="title" target="_blank">'.$r['title'].'</a>';

				//输出选中属性
				foreach($goods[2] as $v)
				{
					echo '<span class="attr">'.$v.'</span>';
				}
				?></td>
				<td><?php echo $goods[1]; ?></td>
				<td><?php echo $r['salesprice'] * $goods[1]; ?></td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td colspan="3" height="30">&nbsp;</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="shoppingcart">
			<tr>
				<td colspan="2" height="30" class="thead">&nbsp;&nbsp;&nbsp;收货人信息</td>
			</tr>
			<tr>
				<td colspan="2" height="15"></td>
			</tr>
			<tr>
				<td width="80" height="30" align="right">收货人姓名： </td>
				<td><?php echo $orderinfo['truename']; ?></td>
			</tr>
			<tr>
				<td height="30" align="right">电　话：</td>
				<td><?php echo $orderinfo['telephone']; ?></td>
			</tr>
			<tr>
				<td height="30" align="right">邮　编： </td>
				<td><?php echo $orderinfo['zipcode']; ?></td>
			</tr>
			<tr>
				<td height="30" align="right">地　址：</td>
				<td><?php

					$r = $dosql->GetOne("SELECT `dataname` FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 AND `datavalue`='".$orderinfo['postarea_prov']."'");
					echo $r['dataname'];

					if($orderinfo['postarea_city'] != '-1')
					{
						$r = $dosql->GetOne("SELECT `dataname` FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND `datavalue`='".$orderinfo['postarea_city']."'");
						echo ' - '.$r['dataname'];
					}

					if($orderinfo['postarea_country'] != '-1')
					{
						$r = $dosql->GetOne("SELECT `dataname` FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=2 AND `datavalue`='".$orderinfo['postarea_country']."'");
						echo ' - '.$r['dataname'];
					}
					?></td>
			</tr>
			<tr>
				<td height="30" align="right">&nbsp;</td>
				<td><?php echo $orderinfo['address']; ?></td>
			</tr>
			<tr>
				<td height="30" align="right">身份证号：</td>
				<td><?php echo $orderinfo['idcard']; ?></td>
			</tr>
			<tr>
				<td colspan="2" height="30">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" height="30" class="thead">&nbsp;&nbsp;&nbsp;订单信息</td>
			</tr>
			<tr>
				<td colspan="2" height="15"></td>
			</tr>
			<tr>
				<td height="30" align="right">配送方式：</td>
				<td><?php
					$r = $dosql->GetOne("SELECT `classname`,`postprice` FROM `#@__postmode` WHERE `id`=".intval($orderinfo['postmode']));
					echo $r['classname'];
					?></td>
			</tr>
			<tr>
				<td height="30" align="right">支付方式：</td>
				<td><?php
					$r = $dosql->GetOne("SELECT `classname` FROM `#@__paymode` WHERE `id`=".intval($orderinfo['paymode']));
					echo $r['classname'];
					?></td>
			</tr>
			<tr>
				<td height="30" align="right">货到方式：</td>
				<td><?php
					$r = $dosql->GetOne("SELECT `classname` FROM `#@__getmode` WHERE `id`=".intval($orderinfo['getmode']));
					echo $r['classname'];
					?></td>
			</tr>
			<tr>
				<td height="30" align="right">购物备注：</td>
				<td><?php echo $orderinfo['buyremark']; ?></td>
			</tr>
			<tr>
				<td height="60" colspan="2" align="right" valign="bottom">
					<strong class="total">总计：</strong><span class="totalprice"><?php echo $orderinfo['totalprice'] + $orderinfo['cost']; ?>元</span>（包含运费：<?php echo $orderinfo['cost']; ?>元）<a href="javascript:history.go(-1);" class="next">上一步</a><a href="javascript:;" onclick="form.submit();" class="next">提　交</a></td>
			</tr>
		</table>
		<input type="hidden" name="action" id="action" value="save" />
	</form>
</div>
<?php

/*
 * 获取排列序号
 *
 * @access  public
 * @param   $tbname   string  获取该表的最大ID
 * @return  $orderid  int     返回当前ID
*/
function GetOrderID($tbname)
{
	global $dosql;

	$r = $dosql->GetOne("SELECT MAX(orderid) AS orderid FROM `$tbname`");
	$orderid = (empty($r['orderid']) ? 1 : ($r['orderid'] + 1));

	return $orderid;
}
?>
<!-- /mainbody-->
<!-- footer-->
<?php require_once('footer.php'); ?>
<!-- /footer-->
</body>
</html>
