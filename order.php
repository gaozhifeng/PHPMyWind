<?php	require_once(dirname(__FILE__).'/include/config.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2013-1-14 13:23:24
person: Feng
**************************
*/


//初始化购物车字符串
if(empty($_COOKIE['shoppingcart']))
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
$action       = isset($action)    ? $action : '';
$username     = AuthCode($_COOKIE['username']);
$shoppingcart = unserialize(AuthCode($_COOKIE['shoppingcart']));
$totalprice   = '';
$totalweight  = '';
$datagroup    = isset($datagroup) ? $datagroup : '';
$level        = isset($level)     ? intval($level) : '';
$v            = isset($areaval)   ? $areaval : '0';


//获取级联
if($action == 'getarea')
{
	$str = '<option value="-1">--</option>';
	$sql = "SELECT * FROM `#@__cascadedata` WHERE level=$level And ";

	if($v == 0)
		$sql .= "datagroup='$datagroup'";
	else if($v % 500 == 0)
		$sql .= "datagroup='$datagroup' AND datavalue>'$v' AND datavalue<'".($v + 500)."'";
	else
		$sql .= "datavalue LIKE '$v.%%%' AND datagroup='$datagroup'";

	$sql .= " ORDER BY orderid ASC, datavalue ASC";


	$dosql->Execute($sql);
	while($row = $dosql->GetArray())
	{
		$str .= '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
	}

	if($str == '') $str .= '<option value="-1">--</option>';
	echo $str;
	exit();
}


//保存订单
if($action == 'save')
{

	//检测数据完整性
	if($username         == '' or
	   $truename         == '' or
	   $telephone        == '' or
	   $zipcode          == '' or
	   $postarea_prov    == '-1' or
	   $address          == '' or
	   $idcard           == '' or
	   $postmode         == '-1' or
	   $paymode          == '-1' or
	   $getmode          == '-1'
	  )
	{
		header('location:order.php');
		exit();
	}


	//HTML转义变量
	$username  = htmlspecialchars($username);
	$truename  = htmlspecialchars($truename);
	$idcard    = htmlspecialchars($idcard);
	$telephone = htmlspecialchars($telephone);
	$zipcode   = htmlspecialchars($zipcode);
	$address   = htmlspecialchars($address);
	$buyremark = htmlspecialchars($buyremark);
	$posttime  = time();

	$postarea_prov    = empty($postarea_prov)    ? '-1' : $postarea_prov;
	$postarea_city    = empty($postarea_city)    ? '-1' : $postarea_city;
	$postarea_country = empty($postarea_country) ? '-1' : $postarea_country;

	$orderinfo = array('truename'  => $truename,
					   'telephone' => $telephone,
					   'zipcode'   => $zipcode,
					   'postarea_prov' => $postarea_prov,
					   'postarea_city' => $postarea_city,
					   'postarea_country' => $postarea_country,
					   'address'   => $address,
					   'idcard'    => $idcard,
					   'postmode'  => $postmode,
					   'paymode'   => $paymode,
					   'getmode'   => $getmode,
					   'buyremark' => $buyremark,
					   'posttime'  => $posttime);

	//存入COOKIE
	setcookie('orderinfo', AuthCode(serialize($orderinfo),'ENCODE'));
	header('location:orderenter.php');
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo GetHeader(0,0,'商品订单'); ?>
<link href="templates/default/style/webstyle.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/default/js/getarea.js"></script>
<script type="text/javascript" src="templates/default/js/top.js"></script>
<script type="text/javascript">
$(function(){
	$("input").focus(function(){
		$(this).attr("class", "class_input_on");
	}).blur(function(){
		$(this).attr("class", "class_input");
	});

	$("textarea").focus(function(){
		$(this).attr("class", "class_areatext_on");
	}).blur(function(){
		$(this).attr("class", "class_areatext");
	});

	$("#truename").focus();
})


/*验证订单表单*/
function CheckOrder()
{
	if($("#truename").val() == "")
	{
		alert("请填写收货人姓名！");
		$("#truename").focus();
		return false;
	}
	if($("#telephone").val() == "")
	{
		alert("请填写电话！");
		$("#telephone").focus();
		return false;
	}
	if($("#zipcode").val() == "")
	{
		alert("请填写邮编！");
		$("#zipcode").focus();
		return false;
	}
	if($("#postarea_prov").val() == "-1")
	{
		alert("请选择所在省份！");
		$("#postarea_prov").focus();
		return false;
	}
	if($("#postarea_city").val() == "-1")
	{
		alert("请选择所在城市！");
		$("#postarea_city").focus();
		return false;
	}
	if($("#address").val() == "")
	{
		alert("请填写详细地址！");
		$("#address").focus();
		return false;
	}
	if($("#idcard").val() == "")
	{
		alert("请填写身份证号码！");
		$("#idcard").focus();
		return false;
	}

	if($("#postmode").val() == "-1")
	{
		alert("请选择配送方式！");
		$("#postmode").focus();
		return false;
	}
	if($("#paymode").val() == "-1")
	{
		alert("请选择支付方式！");
		$("#paymode").focus();
		return false;
	}
	if($("#getmode").val() == "-1")
	{
		alert("请选择货到方式！");
		$("#getmode").focus();
		return false;
	}

	$("#form").submit();
	return false;
}
</script>
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
	<div class="subTitle" style="margin:0;"> <span class="catname shopcart">商品订单</span>
		<div class="cl"></div>
	</div>
	<form name="form" id="form" method="post" action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="shoppingcart">
		<tr>
			<td colspan="2" height="30" class="thead">&nbsp;&nbsp;&nbsp;收货人信息</td>
		</tr>
		<tr>
			<td colspan="2" height="15"></td>
		</tr>
		<tr>
			<td width="80" height="40" align="right">收货人姓名： </td>
			<td><input name="truename" type="text" class="class_input" id="truename" /></td>
		</tr>
		<tr>
			<td height="40" align="right">电　话：</td>
			<td><input name="telephone" type="text" class="class_input" id="telephone" /></td>
		</tr>
		<tr>
			<td height="40" align="right">邮　编： </td>
			<td><input name="zipcode" type="text" class="class_input" id="zipcode" /></td>
		</tr>
		<tr>
			<td height="40" align="right">地　址：</td>
			<td><select name="postarea_prov" id="postarea_prov" onchange="SelProv(this.value,'postarea');">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row = $dosql->GetArray())
					{
						echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
					}
					?>
				</select>
				<select name="postarea_city" id="postarea_city" onchange="SelCity(this.value,'postarea');">
					<option value="-1">--</option>
				</select>
				<select name="postarea_country" id="postarea_country">
					<option value="-1">--</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">&nbsp;</td>
			<td><input name="address" type="text" class="class_input" id="address" /></td>
		</tr>
		<tr>
			<td height="40" align="right">身份证号：</td>
			<td><input type="text" name="idcard" id="idcard" class="class_input" /></td>
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
			<td height="40" align="right">配送方式：</td>
			<td><select name="postmode" id="postmode">
					<option value="-1">请选择配送方式</option>
					<?php GetTopType('#@__postmode','#@__goodsorder','postmode'); ?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">支付方式：</td>
			<td><select name="paymode" id="paymode">
					<option value="-1">请选择支付方式</option>
					<?php GetTopType('#@__paymode','#@__goodsorder','paymode'); ?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">货到方式：</td>
			<td><select name="getmode" id="getmode">
					<option value="-1">请选择货到方式</option>
					<?php GetTopType('#@__getmode','#@__goodsorder','getmode'); ?>
				</select></td>
		</tr>
		<tr>
			<td height="120" align="right">购物备注：</td>
			<td><textarea name="buyremark" class="class_areatext" id="buyremark"></textarea></td>
		</tr>
		<tr>
			<td height="60" colspan="2" align="right" valign="bottom">
			<?php

			//显示订单列表
			foreach($shoppingcart as $k=>$goods)
			{
				//获取数据库中商品信息
				$r = $dosql->GetOne("SELECT * FROM `#@__goods` WHERE `id`=".intval($goods[0]));

				//计算订单总价
				$totalprice += $r['salesprice']*$goods[1];

				//计算订单总重
				$totalweight += $r['weight']*$goods[1];
			}
			?>
			<strong class="total">总计：</strong><span class="totalprice"><?php echo $totalprice; ?></span><a href="shoppingcart.php" class="next">上一步</a><a href="javascript:;" onclick="CheckOrder();return false;" class="next">提　交</a></td>
		</tr>
	</table>
	<input type="hidden" name="username" id="username" value="<?php echo $username; ?>" />
	<input type="hidden" name="amount" id="amount" value="<?php echo $totalprice; ?>" />
	<input type="hidden" name="weight" id="weight" value="<?php echo $totalweight; ?>" />
	<input type="hidden" name="action" id="action" value="save" />

</form>
</div>
<?php
/*
 * 展示自定义类别(无下级)
 *
 * @access  public
 * @param   $tbname   string  显示分类的表名称
 * @param   $tbname2  string  使用分类的表名称
 * @param   $colname  string  使用分类的表字段
 * @param   $id       int     区别记录集的ID
 * @param   $i        int     option缩进位数
 * @return  string            输出<select>
*/
function GetTopType($tbname='', $tbname2='', $colname='', $id=0, $i=0)
{
	global $dosql;

	if(isset($_GET['id']))
	{
		$r = $dosql->GetOne("SELECT `$colname` FROM `$tbname2` WHERE `id`=".intval($_GET['id']));
	}

	$dosql->Execute("SELECT * FROM `$tbname` ORDER BY `orderid` ASC",$id);
	$i++;

	while($row = $dosql->GetArray($id))
	{
		$selected = '';
		if(isset($r) && is_array($r))
		{
			if($row['id'] == $r["$colname"])
				$selected = 'selected="selected"';
		}

		echo '<option value="'.$row['id'].'"'.$selected.'>'.$row["classname"].'</option>';
	}
}


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
