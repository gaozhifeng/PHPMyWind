<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('nav');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2011-5-5 20:06:22
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__nav';
$gourl  = 'nav.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加导航菜单
if($action == 'add')
{
	$parentstr = $doaction->GetParentStr();


	$sql = "INSERT INTO `$tbname` (siteid, parentid, parentstr, classname, linkurl, relinkurl, picurl, target, orderid, checkinfo) VALUES ('$cfg_siteid', '$parentid', '$parentstr', '$classname', '$linkurl', '$relinkurl', '$picurl', '$target', '$orderid', '$checkinfo')";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改导航菜单
else if($action == 'update')
{
	$parentstr = $doaction->GetParentStr();


	//更新所有关联parentstr
	if($parentid != $repid)
	{
		//更新下级parentstr
		$doaction->UpParentStr($id);
	}


	$sql = "UPDATE `$tbname` SET siteid='$cfg_siteid', parentid='$parentid', parentstr='$parentstr', classname='$classname', linkurl='$linkurl', relinkurl='$relinkurl', picurl='$picurl', target='$target', orderid='$orderid', checkinfo='$checkinfo' WHERE id=$id";
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
