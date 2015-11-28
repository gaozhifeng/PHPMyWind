<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('goodsorder');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 16:33:57
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__goodsorder';
$gourl  = 'goodsorder.php';
$action = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//修改订单信息
if($action == 'update')
{

	//是否星标
	if(!isset($core)) $core = '';

	//时间戳格式
	$posttime = GetMkTime($posttime);

	//订单状态
	if(isset($checkinfo))
		$checkinfo = implode(',', $checkinfo);
	else
		$checkinfo = '';


	$sql = "UPDATE `$tbname` SET truename='$truename', telephone='$telephone', idcard='$idcard', zipcode='$zipcode', postarea_prov='$postarea_prov', postarea_city='$postarea_city', postarea_country='$postarea_country', address='$address', postmode='$postmode', paymode='$paymode', getmode='$getmode', ordernum='$ordernum', postid='$postid', weight='$weight', cost='$cost', amount='$amount', integral='$integral', buyremark='$buyremark', sendremark='$sendremark', posttime='$posttime', orderid='$orderid', checkinfo='$checkinfo', core='$core' WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
    	header("location:$gourl");
		exit();
	}
}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
