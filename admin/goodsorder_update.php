<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('goodsorder'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>编辑订单</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="templates/js/getarea.js"></script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__goodsorder` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">编辑订单</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="goodsorder_save.php" onsubmit="return cfm_goodsorder();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">会员用户名：</td>
			<td width="75%"><strong class="maroon2"><?php echo $row['username']; ?></strong><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="120" align="right">商品列表：</td>
			<td><div class="orderList">
					<table width="99%" border="0" align="right" cellpadding="0" cellspacing="0">
						<tr class="head">
							<td width="40" height="25">ID</td>
							<td>商品名称</td>
							<td width="80">数量</td>
							<td width="80">价格</td>
							<td width="80">商品编号</td>
						</tr>
						<?php

						//初始化参数
						$totalprice = '';
						$shoppingcart = unserialize($row['attrstr']);
				
						//显示订单列表
						foreach($shoppingcart as $k=>$goods)
						{
						?>
						<tr class="listItem nb">
							<td height="30"><?php echo $goods[0]; ?></td>
							<td><?php
				
							//获取数据库中商品信息
							$r = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE `id`=".$goods[0]);
				
							//计算订单总价
							$totalprice += $r['salesprice']*$goods[1];
				
							//输出商品名称
							echo '<a href="../goodsshow.php?cid='.$r['classid'].'&tid='.$r['typeid'].'&id='.$r['id'].'" target="_blank">'.$r['title'].'</a>'; 
				
							//输出选中属性
							foreach($goods[2] as $v)
							{
								echo '<span class="attrStr">'.$v.'</span>';
							}
							?></td>
							<td><?php echo $goods[1]; ?></td>
							<td><?php echo $r['salesprice']*$goods[1]; ?></td>
							<td><?php echo $r['goodsid']; ?></td>
						</tr>
						<?php
						}
						?>
					</table>
				</div></td>
		</tr>
		<tr>
			<td height="40" align="right">订单状态：</td>
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
		</tr>
		<tr class="nb">
			<td height="80" align="right">订单操作：</td>
			<td style="line-height:22px;"><?php $checkinfo = explode(',',$row['checkinfo']); ?>
				<input name="checkinfo[]" type="checkbox" id="checkinfo[]" value="confirm" <?php if(in_array('confirm', $checkinfo)) echo 'checked="checked"'; ?> />
				确认订单&nbsp;
				<input name="checkinfo[]" type="checkbox" id="checkinfo[]" value="payment" <?php if(in_array('payment', $checkinfo)) echo 'checked="checked"'; ?> />
				确认付款&nbsp;
				<input name="checkinfo[]" type="checkbox" id="checkinfo[]" value="postgoods" <?php if(in_array('postgoods', $checkinfo)) echo 'checked="checked"'; ?> />
				商品发货&nbsp;
				<input name="checkinfo[]" type="checkbox" id="checkinfo[]" value="getgoods" <?php if(in_array('getgoods', $checkinfo)) echo 'checked="checked"'; ?> />
				已收货 <br />
				<input name="checkinfo[]" type="checkbox" id="checkinfo[]" value="applyreturn" <?php if(in_array('applyreturn', $checkinfo)) echo 'checked="checked"'; ?> />
				申请退货&nbsp;
				<input name="checkinfo[]" type="checkbox" id="checkinfo[]" value="agreedreturn" <?php if(in_array('agreedreturn', $checkinfo)) echo 'checked="checked"'; ?> />
				同意退货&nbsp;
				<input name="checkinfo[]" type="checkbox" id="checkinfo[]" value="goodsback" <?php if(in_array('goodsback', $checkinfo)) echo 'checked="checked"'; ?> />
				收到返货&nbsp;
				<input name="checkinfo[]" type="checkbox" id="checkinfo[]" value="moneyback" <?php if(in_array('moneyback', $checkinfo)) echo 'checked="checked"'; ?> />
				已退款 <br />
				<input name="checkinfo[]" type="checkbox" id="checkinfo[]" value="overorder" <?php if(in_array('overorder', $checkinfo)) echo 'checked="checked"'; ?> />
				已归档 <span class="maroon">*</span></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">收货人姓名： </td>
			<td><input name="truename" type="text" class="input" id="truename" value="<?php echo $row['truename']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">电　话：</td>
			<td><input name="telephone" type="text" class="input" id="telephone" value="<?php echo $row['telephone']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">邮　编： </td>
			<td><input name="zipcode" type="text" class="input" id="zipcode" value="<?php echo $row['zipcode']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">地　址：</td>
			<td><select name="postarea_prov" id="postarea_prov" onchange="SelProv(this.value,'postarea');">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['postarea_prov'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';
	
						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select>
				<select name="postarea_city" id="postarea_city" onchange="SelCity(this.value,'postarea');">
					<option value="-1">--</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue>".$row['postarea_prov']." AND datavalue<".($row['postarea_prov'] + 500)." ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['postarea_city'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select>
				<select name="postarea_country" id="postarea_country">
					<option value="-1">--</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=2 AND datavalue LIKE '".$row['postarea_city'].".%%%' ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['postarea_country'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select>
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="32" align="right">&nbsp;</td>
			<td valign="top"><input name="address" type="text" class="input" id="address" value="<?php echo $row['address']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">证件号码：</td>
			<td><input type="text" name="idcard" id="idcard" class="input" value="<?php echo $row['idcard']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">订单号：</td>
			<td><input name="ordernum" type="text" class="input" id="ordernum" value="<?php echo $row['ordernum']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">运单号：</td>
			<td><input name="postid" type="text" class="input" id="postid" value="<?php echo $row['postid']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">配送方式：</td>
			<td><select name="postmode" id="postmode">
					<option value="-1">请选择配送方式</option>
					<?php GetTopType('#@__postmode','#@__goodsorder','postmode'); ?>
				</select>
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">支付方式：</td>
			<td><select name="paymode" id="paymode">
					<option value="-1">请选择支付方式</option>
					<?php GetTopType('#@__paymode','#@__goodsorder','paymode'); ?>
				</select>
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">货到方式：</td>
			<td><select name="getmode" id="getmode">
					<option value="-1">请选择货到方式</option>
					<?php GetTopType('#@__getmode','#@__goodsorder','getmode'); ?>
				</select>
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">订单重量：</td>
			<td><input name="weight" type="text" class="input" id="weight" value="<?php echo $row['weight']; ?>" />
				kg<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">商品运费：</td>
			<td><input name="cost" type="text" class="input" id="cost" value="<?php echo $row['cost']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">订单金额：</td>
			<td><input name="amount" type="text" id="amount" class="input" value="<?php echo $row['amount']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">赠送积分：</td>
			<td><input name="integral" type="text" class="input" id="integral" value="<?php echo $row['integral']; ?>" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="118" align="right">购物备注：</td>
			<td><textarea name="buyremark" id="buyremark" class="textarea"><?php echo $row['buyremark']; ?></textarea></td>
		</tr>
		<tr>
			<td height="118" align="right">发货方备注：</td>
			<td><textarea name="sendremark" id="sendremark" class="textarea"><?php echo $row['sendremark']; ?></textarea></td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input name="orderid" type="text" id="orderid" class="inputos" value="<?php echo $row['orderid']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">订单时间：</td>
			<td><input name="posttime" type="text" id="posttime" class="inputms" value="<?php echo GetDateTime($row['posttime']); ?>" readonly="readonly" />
				<script type="text/javascript" src="plugin/calendar/calendar.js"></script> 
				<script type="text/javascript">
				Calendar.setup({
					inputField     :    "posttime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">加星标注：</td>
			<td><input name="core" type="checkbox" id="core" value="true" <?php if($row['core']=='true') echo 'checked="checked"'; ?> />
				标注</td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
		<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	</div>
</form>
</body>
</html>