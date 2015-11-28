<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('cascade');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 12:17:29
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__cascadedata';
$gourl  = isset($sign) ? 'cascadedata.php?sign='.$sign : 'cascadedata.php';
$action = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//保存操作
if($action == 'save')
{
	if($datavalue_add != '')
	{
		$dosql->ExecNoneQuery("INSERT INTO `$tbname` (dataname, datavalue, datagroup, orderid, level) VALUES ('$dataname_add', '$datavalue_add', '$datagroup_add', '$orderid_add', '$level_add')");
	}
	if(isset($id))
	{
		$ids = count($id);
		for($i=0; $i<$ids; $i++)
		{
			$dosql->ExecNoneQuery("UPDATE `$tbname` SET dataname='$dataname[$i]', orderid='$orderid[$i]' WHERE `id`=$id[$i]");
		}
	}

    header("location:$gourl");
	exit();
}


//删除操作
if($action == 'delclass')
{
	$dosql->ExecNoneQuery("DELETE FROM `$tbname` WHERE `id`=$id");
	header("location:$gourl");
	exit();
}


//全选删除
if($action == 'delallclass')
{

	//删除栏目的单页信息
	foreach($checkid as $v)
	{
		$arr = explode(',|,', $v);
		$dosql->ExecNoneQuery("DELETE FROM `$tbname` WHERE `id`=".$arr[0]);
	}

	header("location:$gourl");
	exit();
}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
