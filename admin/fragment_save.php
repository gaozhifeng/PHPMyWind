<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('fragment');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 13:58:22
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__fragment';
$gourl  = 'fragment.php';
$action = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加碎片数据
if($action == 'add')
{
	$posttime = time();

	$sql = "INSERT INTO `$tbname` (title, picurl, linkurl, content, posttime) VALUES ('$title', '$picurl', '$linkurl', '$content', '$posttime')";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改碎片数据
else if($action == 'update')
{
	$posttime = GetMkTime($posttime);

	$sql = "UPDATE `$tbname` SET title='$title', picurl='$picurl', linkurl='$linkurl', content='$content', posttime='$posttime' WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
