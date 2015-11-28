<?php	require_once(dirname(__FILE__).'/../include/common.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2013-11-27 11:27:45
person: Feng
**************************
*/


//开启SESSION
if(!isset($_SESSION)) session_start();


//初始化参数
$action = isset($action) ? $action : '';


//锁屏操作
if($action == 'lock')
{
	if(!isset($_SESSION['admin'])) exit('Request Error!');

	$_SESSION['lockname'] = $_SESSION['admin'];
	unset($_SESSION['admin']);
	exit();
}


//锁屏密码
else if($action == 'check')
{
	if(!isset($_SESSION['lockname'])) exit('Request Error!');

	$row = $dosql->GetOne("SELECT `password` FROM `#@__admin` WHERE username='".$_SESSION['lockname']."'");

	if($row['password'] == md5(md5($password)))
	{
		$_SESSION['admin'] = $_SESSION['lockname'];
		unset($_SESSION['lockname']);

		echo TRUE;
		exit();
	}
	else
	{
		echo FALSE;
		exit();
	}
}


//无条件返回
else
{
	exit('Request Error!');
}
?>
