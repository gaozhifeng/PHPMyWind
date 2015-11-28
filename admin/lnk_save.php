<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:11:51
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__lnk';
$gourl  = 'lnk.php';
$action = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//保存操作
if($action == 'save')
{
	if($lnknameadd != '')
	{
		if($lnkicoadd == '')
			$lnkicoadd = 'templates/images/lnkBg02.png';

		$dosql->ExecNoneQuery("INSERT INTO `$tbname` (lnkname, lnklink, lnkico, orderid) VALUES ('$lnknameadd', '$lnklinkadd', '$lnkicoadd', '$orderidadd')");
	}

	if(isset($id))
	{
		$ids = count($id);
		for($i=0; $i<$ids; $i++)
		{
			if($lnkico[$i] == '')
				$lnkico[$i] = 'templates/images/lnkBg02.png';

			$dosql->ExecNoneQuery("UPDATE `$tbname` SET lnkname='$lnkname[$i]', lnklink='$lnklink[$i]', lnkico='$lnkico[$i]',  orderid='$orderid[$i]' WHERE id=$id[$i]");
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
