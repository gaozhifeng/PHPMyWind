<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('maintype');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:13:31
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__maintype';
$gourl  = 'maintype.php';
$action = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加二级分类
if($action == 'add')
{
	$parentstr = $doaction->GetParentStr();


	$sql = "INSERT INTO `$tbname` (siteid, parentid, parentstr, classname, orderid, checkinfo) VALUES ('$cfg_siteid', '$parentid', '$parentstr', '$classname', '$orderid', '$checkinfo')";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改二级分类
else if($action == 'update')
{
	$parentstr = $doaction->GetParentStr();


	//更新所有关联parentstr
	if($parentid != $repid)
	{
		$childtbname = array('#@__infolist','#@__infoimg','#@__soft','#@__goods');

		//更新本类parentstr
		foreach($childtbname as $k=>$v)
		{
			$dosql->ExecNoneQuery("UPDATE `$v` SET parentid='".$parentid."', parentstr='".$parentstr."' WHERE classid=".$id);
		}

		//更新下级parentstr
		$doaction->UpParentStr($id, $childtbname, 'parentstr', 'classid');
	}


	$sql = "UPDATE `$tbname` SET siteid='$cfg_siteid', parentid='$parentid', parentstr='$parentstr', classname='$classname', orderid='$orderid', checkinfo='$checkinfo' WHERE id=$id";
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
