<?php	require_once(dirname(__FILE__).'/../include/common.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-6-18 22:27:23
person: Feng
**************************
*/


//定义入口常量
define('IN_MOBILE', 'TRUE');
define('MOBILE_ROOT', preg_replace("/[\/\\\\]{1,}/", '/', dirname(__FILE__)));
define('MOBILE_INC',  preg_replace("/[\/\\\\]{1,}/", '/', MOBILE_ROOT.'/inc'));


//初始化参数
$c = isset($c) ? $c : 'login';
$a = isset($a) ? $a : '';


//Flash Session传递
if(isset($sessionid)) session_id($sessionid);


//开启Session
if(!isset($_SESSION)) session_start();


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


require_once(MOBILE_INC.'/admin.func.php');
require_once(MOBILE_INC.'/page.class.php');




//加载模板页面
if($c == 'index' || $c == 'web_config' || $c == 'infoclass' ||
   $c == 'info'  || $c == 'infolist'   || $c == 'infoimg')
{
	//检测是否登录
	if(!isset($_SESSION['admin']) || !isset($_SESSION['adminlevel']) || !isset($_SESSION['logintime']))
	{
		$_SESSION = array();
		session_destroy();

		if(strstr(GetCurUrl(), '/plugin/') or
		   strstr(GetCurUrl(), '/editor/'))
		{
			echo '<script type="text/javascript">window.top.location.href="../login.php";</script>';
		}
		else if(strstr(GetCurUrl(), 'inc/config.inc.php'))
		{
			echo '<script type="text/javascript">window.top.location.href="login.php";</script>';
		}
		else
		{
			echo '<script type="text/javascript">window.top.location.href="login.php";</script>';
		}

		exit();
	}
}


//登录页面
if($c == 'login')
{
	require_once('mobile/login.php');
	exit();
}


//管理首页
else if($c == 'index')
{
	require_once('mobile/index.php');
	exit();
}


//网站配置
else if($c == 'web_config')
{
	require_once('mobile/web_config.php');
	exit();
}


//栏目管理
else if($c == 'infoclass')
{
	require_once('mobile/infoclass.php');
	exit();
}


//单页管理
else if($c == 'info')
{
	require_once('mobile/info.php');
	exit();
}


//单页管理
else if($c == 'info_update')
{
	require_once('mobile/info_update.php');
	exit();
}


//列表管理
else if($c == 'infolist')
{
	require_once('mobile/infolist.php');
	exit();
}


//图片管理
else if($c == 'infoimg')
{
	require_once('mobile/infoimg.php');
	exit();
}


else
{
	require_once('mobile/index.php');
	exit();
}
?>
