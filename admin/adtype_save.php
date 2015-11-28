<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('adtype');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 12:11:00
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__adtype';
$gourl  = 'adtype.php';
$action = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加广告位
if($action == 'add')
{
	$parentstr = $doaction->GetParentStr();

	$sql = "INSERT INTO `$tbname` (siteid, parentid, parentstr, classname, width, height, orderid, checkinfo) VALUES ('$cfg_siteid', '$parentid', '$parentstr', '$classname', '$width', '$height', '$orderid', '$checkinfo')";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改广告位
else if($action == 'update')
{
	$parentstr = $doaction->GetParentStr();


	//更新所有关联parentstr
	if($parentid != $repid)
	{
		$childtbname = '#@__admanage';

		//更新本类parentstr
		$dosql->ExecNoneQuery("UPDATE `$childtbname` SET parentid='".$parentid."', parentstr='".$parentstr."' WHERE `classid`=".$id);

		//更新下级parentstr
		$doaction->UpParentStr($id, $childtbname, 'parentstr', 'classid');
	}


	$sql = "UPDATE `$tbname` SET siteid='$cfg_siteid', parentid='$parentid', parentstr='$parentstr', classname='$classname', width='$width', height='$height', orderid='$orderid', checkinfo='$checkinfo' WHERE id=$id";
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
