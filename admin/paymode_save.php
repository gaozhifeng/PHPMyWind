<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('paymode');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:36:06
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__paymode';
$gourl  = 'paymode.php';


//id1为在线支付，不要更改，因已集成支付接口
if($action == 'del2' && $id == 1)
{
	ShowMsg('抱歉，不能删除在线支付方式！','-1');
	exit();
}


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//保存支付方式
if($action == 'save')
{
	if($classnameadd != '')
	{
		$dosql->ExecNoneQuery("INSERT INTO `$tbname` (classname, orderid, checkinfo) VALUES ('$classnameadd', '$orderidadd', '$checkinfoadd')");
	}

	if(isset($id))
	{
		$ids = count($id);
		for($i=0; $i<$ids; $i++)
		{
			$dosql->ExecNoneQuery("UPDATE `$tbname` SET orderid='$orderid[$i]', classname='$classname[$i]' WHERE id=$id[$i]");
		}
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
