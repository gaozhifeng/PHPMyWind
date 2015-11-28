<?php

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-31 21:53:04
person: Feng
**************************
*/



//初始化开始
define('ADMIN_INC',  preg_replace("/[\/\\\\]{1,}/", '/', dirname(__FILE__)));
define('ADMIN_ROOT', preg_replace("/[\/\\\\]{1,}/", '/', substr(ADMIN_INC, 0, -3)));
define('ADMIN_TEMP', ADMIN_ROOT.'/templates');
require_once(ADMIN_ROOT.'/../include/common.inc.php');
require_once(ADMIN_INC.'/page.class.php');


//Flash Session传递
if(isset($sessionid)) session_id($sessionid);


//开启Session
if(!isset($_SESSION)) session_start();



//检测是否登录
if(!isset($_SESSION['admin']) || !isset($_SESSION['adminlevel']) || !isset($_SESSION['logintime']))
{
	$_SESSION = array();
	session_destroy();

	if(strstr(GetCurUrl(), '/plugin/') or
	   strstr(GetCurUrl(), '/editor/'))
	{
		echo '<script type="text/javascript">window.top.location.href="../../login.php";</script>';
	}
	else if(strstr(GetCurUrl(), 'inc/config.inc.php'))
	{
		echo '<script type="text/javascript">window.top.location.href="../login.php";</script>';
	}
	else
	{
		echo '<script type="text/javascript">window.top.location.href="login.php";</script>';
	}

	exit();
}


//是否允许在后台编辑PHP
$cfg_editfile = 'N';


//设置当前站点
if(!empty($_SESSION['siteid']) &&
   !empty($_SESSION['sitekey']))
{
	$cfg_siteid  = $_SESSION['siteid'];
	$cfg_sitekey = $_SESSION['sitekey'];
}
else
{
	$cfg_siteid  = 1;
	$cfg_sitekey = '';
}


//设置当前切换模式
if(!empty($_SESSION['t_adminlevel']))
	$cfg_adminlevel = $_SESSION['t_adminlevel'];
else
	$cfg_adminlevel = $_SESSION['adminlevel'];


//判断权限后引入管理员函数
//因为下面文件需要引用$cfg_adminlevel
require_once(ADMIN_INC.'/admin.func.php');

?>
