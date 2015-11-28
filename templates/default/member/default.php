<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心</title>
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
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="21%">经验值</td>
				<td width="20%">积分</td>
				<td width="35%">上次登录时间</td>
				<td>上次登录IP</td>
			</tr>
			<tr>
				<td><strong class="loginfo"><?php echo $r_user['expval']; ?></strong></td>
				<td><strong class="loginfo"><?php echo $r_user['integral']; ?></strong></td>
				<td><strong class="loginfo"><?php echo MyDate('Y-m-d H:i',$c_logintime); ?></strong></td>
				<td><strong class="loginfo"><?php echo $c_loginip; ?></strong></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<h3 class="dftitle">我的收藏</h3>
		<?php
		$dosql->Execute("SELECT * FROM `#@__userfavorite` WHERE uname='$c_uname' ORDER BY id DESC LIMIT 0,3");
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<ul class="list">
			<?php
			while($row = $dosql->GetArray())
			{
				if($row['molds'] == 1)
					$tbname = 'infolist';
				else if($row['molds'] == 2)
					$tbname = 'infoimg';
				else if($row['molds'] == 3)
					$tbname = 'soft';
				else if($row['molds'] == 4)
					$tbname = 'goods';
				else
					$tbname = '';

				$r = $dosql->GetOne("SELECT * FROM `#@__$tbname` WHERE id=".$row['aid']." AND delstate=''");
				if(isset($r) && is_array($r))
				{
			?>
			<li><span class="time"><?php echo GetDateTime($row['time']); ?></span>· <a href="<?php echo $row['link']; ?>" target="_blank"><?php echo $r['title']; ?></a></li>
			<?php
				}
				else
				{
					echo '<li>· 此条收藏的信息已不存在！(ID:'.$row['id'].')</li>';
				}
			}
			?>
		</ul>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">您还没有收藏哦！</div>
		<?php
		}
		?>
		<div class="more"><a href="?c=favorite">更多</a></div>
		<h3 class="dftitle">我的评论</h3>
		<?php
		$dosql->Execute("SELECT * FROM `#@__usercomment` WHERE uname='$c_uname' ORDER BY id DESC LIMIT 0,3");
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<ul class="msglist">
			<?php
			while($row = $dosql->GetArray())
			{
				if($row['molds'] == 1)
					$tbname = 'infolist';
				else if($row['molds'] == 2)
					$tbname = 'infoimg';
				else if($row['molds'] == 3)
					$tbname = 'soft';
				else if($row['molds'] == 4)
					$tbname = 'goods';
				else
					$tbname = '';

				$r = $dosql->GetOne("SELECT * FROM `#@__$tbname` WHERE id=".$row['aid']." AND delstate=''");
				if(isset($r) && is_array($r))
				{
			?>
			<li>
				<p><?php echo ClearHtml($row['body']); ?></p>
				<span class="from">评论自 <a href="<?php echo $row['link']; ?>" target="_blank"><?php echo ReStrLen($r['title'],20); ?></a></span><span class="time"><?php echo GetDateTime($row['time']); ?></span>
				<div class="cl"></div>
			</li>
			<?php
				}
				else
				{
					echo '<li>· 此条评论的信息已不存在！(ID:'.$row['id'].')</li>';
				}
			}
			?>
		</ul>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">您还没有评论哦！</div>
		<?php
		}
		?>
		<div class="more"><a href="?c=comment">更多</a></div>
		<h3 class="dftitle">我的订单</h3>
		<?php
		$dosql->Execute("SELECT * FROM `#@__goodsorder` WHERE `username`='$c_uname' ORDER BY id DESC LIMIT 0,3");
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<ul class="msglist">
			<?php
			while($row = $dosql->GetArray())
			{
			?>
			<li>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="orderlist">
					<tr class="thead" align="center">
						<td width="12%" align="left">编号</td>
						<td width="62%" height="30" align="left">商品名称</td>
						<td width="10%" align="center">数量</td>
						<td width="10%" align="center">价格</td>
						<td width="6%" align="right">操作</td>
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
						<td height="30" align="left"><?php echo $r['goodsid']; ?></td>
						<td align="left">
						<?php
			
						//输出商品名称
						echo '<a href="goodsshow.php?cid='.$r['classid'].'&tid='.$r['typeid'].'&id='.$r['id'].'" class="title" target="_blank">'.$r['title'].'</a>'; 
			
						//输出选中属性
						foreach($goods[2] as $v)
						{
							echo '<span class="attr">'.$v.'</span>';
						}
						?></td>
						<td><?php echo $goods[1]; ?></td>
						<td><?php echo $r['salesprice']*$goods[1]; ?></td>
						<td align="right" class="action"><a href="?c=ordershow&id=<?php echo $row['id']; ?>">详情</a></td>
					</tr>
					<?php
						}
						else
						{
							echo '<tr><td height="30" colspan="5">订单中的此商品已不存在！</td></tr>';
						}
					}
					?>
					<tr>
						<td height="35" colspan="5" align="right" valign="bottom"><strong class="total">订单编号：</strong><?php echo $row['ordernum']; ?><strong class="total">时间：</strong><?php echo GetDateTime($row['posttime']); ?><strong class="total">状态：</strong><span class="blue">
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
		<?php
		}
		else
		{
		?>
		<div class="nonelist">您还没有订单哦！</div>
		<?php
		}
		?>
		<div class="more"><a href="?c=order">更多</a></div>
		<h3 class="dftitle">我的留言</h3>
		<?php
		$dosql->Execute("SELECT * FROM `#@__message` WHERE nickname='$c_uname' ORDER BY id DESC LIMIT 0,3");
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<ul class="msglist">
			<?php
			while($row = $dosql->GetArray())
			{
			?>
			<li>
				<p><?php echo ClearHtml($row['content']); ?></p>
				<span class="from">来自网站留言</span><span class="time"><?php echo GetDateTime($row['posttime']); ?></span>
				<div class="cl"></div>
				<?php
			if($row['recont'] != '') echo '【回复】'.ClearHtml($row['recont']);
			?>
			</li>
			<?php
			}
			?>
		</ul>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">您还没有留言哦！</div>
		<?php
		}
		?>
		<div class="more"><a href="?c=msg">更多</a></div>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
