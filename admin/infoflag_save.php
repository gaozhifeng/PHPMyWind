<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('infoflag');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 16:54:51
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__infoflag';
$gourl  = 'infoflag.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//保存操作
if($action == 'save')
{
	if($flagnameadd != '')
	{
		$dosql->ExecNoneQuery("INSERT INTO `$tbname` (flag, flagname, orderid) VALUES ('$flagadd', '$flagnameadd', '$orderidadd')");
	}

	$ids = count($id);
	for($i=0; $i<$ids; $i++)
	{
		$dosql->ExecNoneQuery("UPDATE `$tbname` SET flag='$flag[$i]', flagname='$flagname[$i]',  orderid='$orderid[$i]' WHERE id=$id[$i]");
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
