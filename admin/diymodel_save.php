<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('diymodel');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 13:41:11
person: Feng
**************************
*/


//初始化参数
$tbname   = '#@__diymodel';
$gourl    = 'diymodel.php';
$action   = isset($action)   ? $action   : '';
$infotype = isset($infotype) ? $infotype : '';



//添加自定义模型
if($action == 'add')
{
	//初始化参数
	if(is_array($defaultfield)) $defaultfield = implode(',',$defaultfield);


	//构成表前缀
	$modeltbname = $db_tablepre.$modeltbname;


	$r = $dosql->GetOne("SELECT id FROM `$tbname` WHERE `modeltbname`='$modeltbname'");
	if($dosql->IsTable($modeltbname) or !empty($r['id']))
	{
		ShowMsg('模型表名已存在！',$gourl);
		exit();
	}

	$r = $dosql->GetOne("SELECT id FROM `$tbname` WHERE `modelname`='$modelname'");
	if(!empty($r['id']))
	{
		ShowMsg('模型标识已存在！',$gourl);
		exit();
	}


	$sql = "CREATE TABLE `$modeltbname` (
			  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '信息id',
			  `siteid` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '站点id',
			  `classid` smallint(5) unsigned NOT NULL COMMENT '信息类别id',
			  `parentid` smallint(5) unsigned NOT NULL COMMENT '信息类别父id',
			  `parentstr` varchar(80) NOT NULL COMMENT '信息类别父id字符串',
			  `title` varchar(80) NOT NULL COMMENT '标题',
			  `flag` varchar(30) NOT NULL COMMENT '属性',
			  `picurl` varchar(100) NOT NULL COMMENT '缩略图片',
			  `orderid` smallint(5) unsigned NOT NULL COMMENT '排列排序',
			  `posttime` int(10) unsigned NOT NULL COMMENT '提交时间',
			  `checkinfo` enum('true','false') NOT NULL COMMENT '审核状态',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


	//添加模型
	if($dosql->ExecNoneQuery($sql))
	{
		$sql = "INSERT INTO `$tbname` (modelname, modeltitle, modeltbname, defaultfield, orderid, checkinfo) VALUES ('$modelname', '$modeltitle', '$modeltbname', '$defaultfield', '$orderid', '$checkinfo')";
		$dosql->ExecNoneQuery($sql);
		header("location:$gourl");
		exit();
	}
	else
	{
		ShowMsg("模型添加失败！请检查设置的内容是否合法！",'-1');
		exit();
	}
}


//修改自定义模型
else if($action == 'update')
{
	//初始化参数
	if(is_array($defaultfield)) $defaultfield = implode(',',$defaultfield);


	$sql = "UPDATE `$tbname` SET modeltitle='$modeltitle', defaultfield='$defaultfield', orderid='$orderid', checkinfo='$checkinfo' WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
		header("location:$gourl");
	else
		ShowMsg("模型添加失败！请检查设置的内容是否合法！",'-1');

	exit();
}


//自定义模型状态
else if($action == 'check')
{
	if($checkinfo == 'true')
		$sql = "UPDATE `$tbname` SET checkinfo='false' WHERE id=$id";

	else if($checkinfo == 'false')
		$sql = "UPDATE `$tbname` SET checkinfo='true' WHERE id=$id";

	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//删除自定义模型
else if($action == 'del')
{
	//先确认该模型是否还存在自定义字段
	$r = $dosql->GetOne("SELECT `id` FROM `#@__diyfield` WHERE `infotype`=$id");
	if(isset($r) && is_array($r))
	{
		ShowMsg('请先在 [自定义字段] 模块中删除该模型下自定义字段！','-1');
		exit();
	}
	$r = $dosql->GetOne("SELECT `id` FROM `#@__infoclass` WHERE `infotype`=$id");
	if(isset($r) && is_array($r))
	{
		ShowMsg('请先在 [栏目管理] 模块中删除该模型下栏目！','-1');
		exit();
	}


	$r = $dosql->GetOne("SELECT `modeltbname` FROM `#@__diymodel` WHERE `id`=$id");
	if(isset($r) && is_array($r))
	{
		$dosql->ExecuteSafeQuery("DROP TABLE `".$r['modeltbname']."`");
	}

	$dosql->ExecNoneQuery("DELETE FROM `$tbname` WHERE `id`=$id");
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
