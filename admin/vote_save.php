<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('vote');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-8-2 14:38:42
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__vote';
$gourl  = 'vote.php';
$action = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加投票信息
if($action == 'add')
{

	if($starttime != '')
	{
		$starttime = GetMkTime($starttime);
	}

	if($endtime != '')
	{
		$endtime = GetMkTime($endtime);
	}


	$posttime = GetMkTime($posttime);


	$sql = "INSERT INTO `$tbname` (siteid, title, content, starttime, endtime, isguest, isview, intval, isradio, orderid, posttime, checkinfo) VALUES ('$cfg_siteid', '$title', '$content', '$starttime', '$endtime', '$isguest', '$isview', '$intval', '$isradio', '$orderid', '$posttime', '$checkinfo')";
	if($dosql->ExecNoneQuery($sql))
	{
		$voteid = $dosql->GetLastID();
		foreach($options as $v)
		{
			if($v != '')
			{
				$dosql->ExecNoneQuery("INSERT INTO `#@__voteoption` (voteid, options) VALUES ($voteid, '$v')");
			}
		}
		header("location:$gourl");
		exit();
	}
}


//修改投票信息
if($action == 'update')
{
	$ids = count($option_id);
	for($i=0; $i<$ids; $i++)
	{
		if($options[$i] != '')
		{
			$dosql->ExecNoneQuery("UPDATE `#@__voteoption` SET options='".$options[$i]."' WHERE id=".$option_id[$i]);
		}
		else
		{
			$dosql->ExecNoneQuery("DELETE FROM `#@__votedata` WHERE optionid=".$option_id[$i]);
			$dosql->ExecNoneQuery("DELETE FROM `#@__voteoption` WHERE id=".$option_id[$i]);
		}
	}


	if(isset($options_new))
	{
		foreach($options_new as $v)
		{
			if($v != '')
			{
				$dosql->ExecNoneQuery("INSERT INTO `#@__voteoption` (voteid, options) VALUES ($id, '$v')");
			}
		}
	}


	if($starttime != '')
	{
		$starttime = GetMkTime($starttime);
	}


	if($endtime != '')
	{
		$endtime = GetMkTime($endtime);
	}


	$posttime  = GetMkTime($posttime);


	$sql = "UPDATE `$tbname` SET siteid='$cfg_siteid', title='$title', content='$content', starttime='$starttime', endtime='$endtime', isguest='$isguest', isview='$isview', intval='$intval', isradio='$isradio', orderid='$orderid', posttime='$posttime', checkinfo='$checkinfo' WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//删除投票
if($action == 'delvote')
{
	$dosql->ExecNoneQuery("DELETE FROM `#@__votedata` WHERE voteid=$id");
	$dosql->ExecNoneQuery("DELETE FROM `#@__voteoption` WHERE voteid=$id");
	$dosql->ExecNoneQuery("DELETE FROM `#@__vote` WHERE id=$id");

	header("location:$gourl");
	exit();
}


//删除选项
if($action == 'delopt')
{
	$dosql->ExecNoneQuery("DELETE FROM `#@__votedata` WHERE optionid=".$option_id);
	$dosql->ExecNoneQuery("DELETE FROM `#@__voteoption` WHERE id=".$option_id);

	header("location:vote_update.php?id=$id");
	exit();
}
?>
