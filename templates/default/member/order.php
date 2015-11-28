<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 我的订单</title>
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
		$dopage->GetPage("SELECT * FROM `#@__goodsorder` WHERE `username`='$c_uname' ORDER BY id DESC",10);
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<form name="form" id="form" method="post">
		<ul class="msglist">
			<?php
			while($row = $dosql->GetArray())
			{
				$checkinfo = explode(',', $row['checkinfo']);
			?>
			<li>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="orderlist">
					<tr class="thead" align="center">
						<td width="5%" height="30" align="left"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" <?php if(!in_array('overorder',  $checkinfo)) echo 'disabled="disabled"'; ?> /></td>
						<td width="10%" align="left">编号</td>
						<td width="57%" align="left">商品名称</td>
						<td width="10%">数量</td>
						<td width="10%">价格</td>
						<td width="8%" align="right">操作</td>
					</tr>
					<?php
		
					//初始化参数
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
						<td height="30" align="center"></td>
						<td align="left"><?php echo $r['goodsid']; ?></td>
						<td align="left">
						<?php
			
						//输出商品名称
						echo '<a href="goodsshow.php?cid='.$r['classid'].'&tid='.$r['typeid'].'&id='.$r['id'].'" class="title" target="_blank">'.$r['title'].'</a>'; 
			
						//输出选中属性
						foreach($goods[2] as $v)
						{
							echo '<span class="attr">'.$v.'</span>';
						}
						?>
						</td>
						<td align="center"><?php echo $goods[1]; ?></td>
						<td align="center"><?php echo $r['salesprice']*$goods[1]; ?></td>
						<td align="right" class="action"><a href="?c=ordershow&id=<?php echo $row['id']; ?>">详情</a></td>
					</tr>
					<?php
						}
						else
						{
							echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td height="30" colspan="4">订单中的此商品已不存在！</td></tr>';
						}
					}
					?>
					<tr>
						<td height="35" colspan="6" align="right" valign="bottom"><strong class="total">订单编号：</strong><?php echo $row['ordernum']; ?><strong class="total">时间：</strong><?php echo GetDateTime($row['posttime']); ?><strong class="total">状态：</strong><span class="blue">
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
										echo '等待确认';
					
									else if(!in_array('payment', $checkinfo))
										echo '等待付款';
					
									else if(!in_array('postgoods', $checkinfo))
										echo '等待发货';
					
									else if(!in_array('getgoods', $checkinfo))
										echo '等待收货';
					
									else if(!in_array('overorder', $checkinfo))
										echo '等待归档';
					
									else
										echo '参数错误，没有获取到订单状态';
								}
								else
								{
									if(in_array('overorder', $checkinfo))
										echo '已归档';
									
									else if(in_array('moneyback', $checkinfo))
										echo '等待归档';
					
									else if(in_array('goodsback', $checkinfo))
										echo '等待退款';
					
									else if(in_array('agreedreturn', $checkinfo))
										echo '等待返货';
					
									else if(in_array('applyreturn', $checkinfo))
										echo '申请退货';
					
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
										echo '等待确认';
				
									else if(!in_array('postgoods', $checkinfo))
										echo '等待发货';
					
									else if(!in_array('getgoods', $checkinfo))
										echo '等待收货';
									
									else if(!in_array('payment', $checkinfo))
										echo '等待付款';
					
									else if(!in_array('overorder', $checkinfo))
										echo '等待归档';
					
									else
										echo '参数错误，没有获取到订单状态';
								}
								else
								{
									if(in_array('overorder', $checkinfo))
										echo '已归档';
									
									else if(in_array('moneyback', $checkinfo))
										echo '等待归档';
					
									else if(in_array('goodsback', $checkinfo))
										echo '等待退款';
					
									else if(in_array('agreedreturn', $checkinfo))
										echo '等待返货';
					
									else if(in_array('applyreturn', $checkinfo))
										echo '申请退货';
					
									else
										echo '参数错误，没有获取到订单状态';
								}
							}
							?>
							</span><strong class="total">总计：</strong><span class="totalprice"><?php echo $row['amount']+$row['cost']; ?>元(包含运费：<?php echo $row['cost']; ?>元)</span></td>
					</tr>
			</table>
			</li>
			<?php
			}
			?>
		</ul>
		</form>
		<div class="options_b">选择： <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('?a=delorder');" onclick="return ConfDelAll(0);">删除</a></div>
		<?php echo $dopage->GetList(); ?>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">您还没有订单哦！</div>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
