<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('job');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:09:57
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__job';
$gourl  = 'job.php';
$action = isset($action) ? $action : '';


//引入处理类
require_once(ADMIN_INC.'/action.class.php');


//添加招聘信息
if($action == 'add')
{
	$posttime = GetMkTime($posttime);

	$sql = "INSERT INTO `$tbname` (siteid, title, jobplace, jobdescription, employ, jobsex, treatment, usefullife, experience, education, joblang, workdesc, content, orderid, posttime, checkinfo) VALUES ('$cfg_siteid', '$title', '$jobplace', '$jobdescription', '$employ', '$jobsex', '$treatment', '$usefullife', '$experience', '$education', '$joblang', '$workdesc', '$content', '$orderid', '$posttime', '$checkinfo');";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改招聘信息
if($action == 'update')
{
	$posttime = GetMkTime($posttime);

	$sql = "UPDATE `$tbname` SET siteid='$cfg_siteid', title='$title', jobplace='$jobplace', jobdescription='$jobdescription', employ='$employ', jobsex='$jobsex', treatment='$treatment', usefullife='$usefullife', experience='$experience', education='$education', joblang='$joblang', workdesc='$workdesc', content='$content', orderid='$orderid', posttime='$posttime', checkinfo='$checkinfo' WHERE id=$id";
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
