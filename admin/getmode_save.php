<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('getmode');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 14:00:14
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__getmode';
$gourl  = 'getmode.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//保存货到方式
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
			$dosql->ExecNoneQuery("UPDATE `$tbname` SET orderid='$orderid[$i]', classname='$classname[$i]' WHERE `id`=$id[$i]");
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
