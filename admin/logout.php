<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-4-8 21:16:15
person: Feng
**************************
*/


//更新操作日志
SetSysEvent('logout');

$_SESSION = array();
session_destroy();

header('location:login.php');
exit();

?>
