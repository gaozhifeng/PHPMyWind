<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('cascade');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 12:16:06
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__cascade';
$gourl  = 'cascade.php';
$action = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//保存操作
if($action == 'save')
{
	if($groupname_add != '')
	{
		$dosql->ExecNoneQuery("INSERT INTO `$tbname` (groupname, groupsign, orderid) VALUES ('$groupname_add', '$groupsign_add', '$orderid_add')");
	}

	if(isset($id))
	{
		$ids = count($id);
		for($i=0; $i<$ids; $i++)
		{
			$dosql->ExecNoneQuery("UPDATE `$tbname` SET groupname='$groupname[$i]', groupsign='$groupsign[$i]', orderid='$orderid[$i]' WHERE id=$id[$i]");
		}
	}

    header("location:$gourl");
	exit();
}


//删除操作
if($action == 'delclass')
{
	$dosql->ExecNoneQuery("DELETE FROM `$tbname` WHERE `id`=$id");
	$dosql->ExecNoneQuery("DELETE FROM `#@__cascadedata` WHERE `datagroup`='$sign'");
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
		$dosql->ExecNoneQuery("DELETE FROM `#@__cascadedata` WHERE `datagroup`='".$arr[1]."'");
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
