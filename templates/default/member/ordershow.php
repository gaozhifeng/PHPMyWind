<?php if(!defined('IN_MEMBER')) exit('Request Error!');

//初始化参数检测正确性
$id  = empty($id) ? 0 : intval($id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 订单详情</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
</head>

<body>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<div class="leftarea">
		<?php require_once(dirname(__FILE__).'/lefter.php'); ?>
	</div>
	<div class="rightarea">
		<h3 class="subtitle">我的订单</h3>
		<?php
		$row = $dosql->GetOne("SELECT * FROM `#@__goodsorder` WHERE `username`='$c_uname' and `id`=$id");
		if(isset($row) && is_array($row))
		{
		?>
		<form name="form" id="form" method="post" action="goodsorder_save.php" onsubmit="return cfm_goodsorder();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form_table">
				<tr>
					<td height="120" colspan="2">
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="orderlist orderlist2">
							<tr class="thead" align="center">
								<td width="12%">商品编号</td>
								<td width="68%" height="30" align="left">商品名称</td>
								<td width="10%" align="center">数量</td>
								<td width="10%" align="center">价格</td>
							</tr>
							<?php
					
							//初始化参数
							$totalprice = '';
							$shoppingcart = unserialize($row['attrstr']);
					
							//显示订单列表
							foreach($shoppingcart as $k=>$goods)
							{
								//获取数据库中商品信息
								$r = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE `id`=".$goods[0]." AND delstate=''");
								if(isset($r) && is_array($r))
								{
							?>
							<tr align="center">
								<td><?php echo $r['goodsid']; ?></td>
								<td height="30" align="left">
								<?php
					
								//计算订单总价
								$totalprice += $r['salesprice']*$goods[1];
					
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
							</tr>
							<?php
								}
								else
								{
									echo '<tr><td>&nbsp;</td><td height="30" colspan="3">订单中的此商品已不存在！</td></tr>';
								}
							}
							?>
						</table>
					</td>
				</tr>
				<tr>
					<td width="13%" height="35" align="right">订单状态：</td>
					<td width="87%" class="blue">
					<?php

					$checkinfo = explode(',',$row['checkinfo']);
					
					if($row['paymode'] == 1)
					{
						if(!in_array('applyreturn',  $checkinfo) &&
						   !in_array('agreedreturn',  $checkinfo) &&
						   !in_array('goodsback',   $checkinfo) &&
						   !in_array('moneyback', $checkinfo) &&
						   !in_array('overorder',    $checkinfo))
						{
							if($row['checkinfo'] == '' or
							   !in_array('confirm', $checkinfo))
								echo '等待商家确认订单';
			
							else if(!in_array('payment', $checkinfo))
								echo '已确认，等待付款';
			
							else if(!in_array('postgoods', $checkinfo))
								echo '已付款，等待发货';
			
							else if(!in_array('getgoods', $checkinfo))
								echo '已发货，等待收货';
			
							else if(!in_array('overorder', $checkinfo))
								echo '已收货，等待订单归档';
			
							else
								echo '参数错误，没有获取到订单状态';
						}
						else
						{
							if(in_array('overorder', $checkinfo))
								echo '订单已归档';
							
							else if(in_array('moneyback', $checkinfo))
								echo '已退款，等待归档';
			
							else if(in_array('goodsback', $checkinfo))
								echo '已收到返货，等待退款';
			
							else if(in_array('agreedreturn', $checkinfo))
								echo '同意退货，等待收返货';
			
							else if(in_array('applyreturn', $checkinfo))
								echo '申请退货，等待退货';
			
							else
								echo '参数错误，没有获取到订单状态';
						}
					}
					else if($row['paymode'] == 2)
					{
						if(!in_array('applyreturn',  $checkinfo) &&
						   !in_array('agreedreturn',  $checkinfo) &&
						   !in_array('goodsback',   $checkinfo) &&
						   !in_array('moneyback', $checkinfo) &&
						   !in_array('overorder',    $checkinfo))
						{
							if($row['checkinfo'] == '' or
							   !in_array('confirm', $checkinfo))
								echo '等待商家确认订单';
			
							else if(!in_array('postgoods', $checkinfo))
								echo '已付款，等待发货';
			
							else if(!in_array('getgoods', $checkinfo))
								echo '已发货，等待收货';
							
							else if(!in_array('payment', $checkinfo))
								echo '已收货，等待付款';
			
							else if(!in_array('overorder', $checkinfo))
								echo '已付款，等待订单归档';
			
							else
								echo '参数错误，没有获取到订单状态';
						}
						else
						{
							if(in_array('overorder', $checkinfo))
								echo '订单已归档';
							
							else if(in_array('moneyback', $checkinfo))
								echo '已退款，等待归档';
			
							else if(in_array('goodsback', $checkinfo))
								echo '已收到返货，等待退款';
			
							else if(in_array('agreedreturn', $checkinfo))
								echo '同意退货，等待收返货';
			
							else if(in_array('applyreturn', $checkinfo))
								echo '申请退货，等待退货';
			
							else
								echo '参数错误，没有获取到订单状态';
						}
					}
					?></td>
				</tr>
				<tr>
					<td height="35" align="right">订单操作：</td>
					<td class="orderact">
						<?php

						if(in_array('confirm', $checkinfo) &&
						   !in_array('payment', $checkinfo) &&
						   !in_array('postgoods', $checkinfo) &&
						   !in_array('getgoods', $checkinfo) &&
						   !in_array('overorder', $checkinfo) &&
						   !in_array('moneyback', $checkinfo) &&
						   !in_array('goodsback', $checkinfo) &&
						   !in_array('agreedreturn', $checkinfo) &&
						   !in_array('applyreturn', $checkinfo))
							echo '<a href="orderpay.php?id='.$row['id'].'">付款</a>';

						else if(in_array('postgoods', $checkinfo) &&
						   !in_array('getgoods', $checkinfo))
							echo '<a href="?a=getgoods&id='.$row['id'].'">确认收货</a>';

						else if(in_array('getgoods', $checkinfo) &&
						        !in_array('applyreturn', $checkinfo))
							echo '<a href="?a=applyreturn&id='.$row['id'].'">申请退货</a>';

						else
							echo '暂无操作';

						?>
						</td>
				</tr>
				<tr>
					<td colspan="2" height="26">&nbsp;</td>
				</tr>
				<tr>
					<td height="35" align="right">收货人姓名： </td>
					<td><?php echo $row['truename']; ?></td>
				</tr>
				<tr>
					<td height="35" align="right">电　话：</td>
					<td><?php echo $row['telephone']; ?></td>
				</tr>
				<tr>
					<td height="35" align="right">邮　编： </td>
					<td><?php echo $row['zipcode']; ?></td>
				</tr>
				<tr>
					<td height="35" align="right">地　址：</td>
					<td>
						<?php

						$r = $dosql->GetOne("SELECT `dataname` FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 AND `datavalue`=".$row['postarea_prov']);
						echo $r['dataname'];
						
						if($row['postarea_city'] != '-1')
						{
							$r = $dosql->GetOne("SELECT `dataname` FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND `datavalue`=".$row['postarea_city']);
							echo ' - '.$r['dataname'];
						}
						
						if($row['postarea_country'] != '-1')
						{
							$r = $dosql->GetOne("SELECT `dataname` FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=2 AND `datavalue`=".$row['postarea_country']);
							echo ' - '.$r['dataname'];
						}
						?>
						</td>
				</tr>
				<tr>
					<td height="35" align="right">&nbsp;</td>
					<td><?php echo $row['address']; ?></td>
				</tr>
				<tr>
					<td height="35" align="right">证件号码：</td>
					<td><?php echo $row['idcard']; ?></td>
				</tr>
				<tr>
					<td colspan="2" height="26">&nbsp;</td>
				</tr>
				<tr>
					<td height="35" align="right">订单编号：</td>
					<td><?php echo $row['ordernum']; ?></td>
				</tr>
				<tr>
					<td height="35" align="right">运单号：</td>
					<td><?php if($row['postid'] == ''){echo '暂未生成运单号';}else{echo $row['postid'];} ?></td>
				</tr>
				<tr>
					<td height="35" align="right">配送方式：</td>
					<td>
					<?php
					$r = $dosql->GetOne("SELECT `classname` FROM `#@__postmode` WHERE `id`=".$row['postmode']);
					echo $r['classname'];
					?>
					</td>
				</tr>
				<tr>
					<td height="35" align="right">支付方式：</td>
					<td>
					<?php
					$r = $dosql->GetOne("SELECT `classname` FROM `#@__paymode` WHERE `id`=".$row['paymode']);
					echo $r['classname'];
					?>
					</td>
				</tr>
				<tr>
					<td height="35" align="right">货到方式：</td>
					<td>
					<?php
					$r = $dosql->GetOne("SELECT `classname` FROM `#@__getmode` WHERE `id`=".$row['getmode']);
					echo $r['classname'];
					?></td>
				</tr>
				<tr>
					<td height="35" align="right">订单重量：</td>
					<td><?php echo $row['weight']; ?>kg</td>
				</tr>
				<tr>
					<td height="35" align="right">订单金额：</td>
					<td><?php echo $row['amount']+$row['cost']; ?>元(包含运费：<?php echo $row['cost']; ?>元)</td>
				</tr>
				<tr>
					<td height="35" align="right">赠送积分：</td>
					<td><?php echo $row['integral']; ?></td>
				</tr>
				<tr>
					<td height="35" align="right">购物备注：</td>
					<td><?php if($row['buyremark'] == ''){echo '无备注';}else{echo $row['buyremark'];} ?></td>
				</tr>
				<tr>
					<td height="35" align="right">发货方备注：</td>
					<td><?php if($row['sendremark'] == ''){echo '无备注';}else{echo $row['sendremark'];} ?></td>
				</tr>
				<tr>
					<td height="35" align="right">订单时间：</td>
					<td><?php echo GetDateTime($row['posttime']); ?></td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="button" class="btn" value="返 回" onclick="history.go(-1)" />
			</div>
		</form>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
