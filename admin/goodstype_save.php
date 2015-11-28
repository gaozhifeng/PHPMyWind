<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('goodstype');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 16:45:58
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__goodstype';
$gourl  = 'goodstype.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加商品分类
if($action == 'add')
{
	$parentstr = $doaction->GetParentStr();

	$sql = "INSERT INTO `$tbname` (parentid, parentstr, classname, picurl, linkurl, orderid, checkinfo) VALUES ('$parentid', '$parentstr', '$classname', '$picurl', '$linkurl', '$orderid', '$checkinfo')";
	if($dosql->ExecNoneQuery($sql))
	{
    	header("location:$gourl");
		exit();
	}
}


//修改商品分类
else if($action == 'update')
{
	$parentstr = $doaction->GetParentStr();

	//更新所有关联parentstr
	if($parentid != $repid)
	{
		$childtbname = '#@__goods';

		//更新本类parentstr
		$dosql->ExecNoneQuery("UPDATE `$childtbname` SET typepid='".$parentid."', typepstr='".$parentstr."' WHERE typeid=".$id);

		//更新下级parentstr
		$doaction->UpParentStr($id, $childtbname, 'typepstr', 'typeid');
	}


	$sql = "UPDATE `$tbname` SET parentid='$parentid', parentstr='$parentstr', classname='$classname', picurl='$picurl', linkurl='$linkurl', orderid='$orderid', checkinfo='$checkinfo' WHERE id=$id";
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
