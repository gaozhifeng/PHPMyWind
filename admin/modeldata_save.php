<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('diymodel');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-1-30 13:50:21
person: Feng
**************************
*/


//初始化参数
$r = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `modelname`='$m'");
$modelid = $r['id'];
$tbname  = $r['modeltbname'];
$gourl   = 'modeldata.php?m='.$r['modelname'];
$action  = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加模型信息
if($action == 'add')
{
	//初始化信息
	if(!isset($title))     $title     = '';
	if(!isset($flag))      $flag      = '';
	if(!isset($picurl))    $picurl    = '';
	if(!isset($rempic))    $rempic    = '';
	if(!isset($orderid))   $orderid   = '';
	if(!isset($posttime))  $posttime  = '';
	if(!isset($checkinfo)) $checkinfo = 'true';
	if(is_array($flag)) $flag = implode(',',$flag);


	//栏目权限验证
	IsCategoryPriv($classid,'add');


	//获取parentstr
	$row = $dosql->GetOne("SELECT `parentid` FROM `#@__infoclass` WHERE `id`=$classid");
	$parentid = $row['parentid'];

	if($parentid == 0)
	{
		$parentstr = '0,';
	}
	else
	{
		$r = $dosql->GetOne("SELECT `parentstr` FROM `#@__infoclass` WHERE `id`=$parentid");
		$parentstr = $r['parentstr'].$parentid.',';
	}


	//保存远程缩略图
	if($rempic=='true' &&
	   preg_match("#^http:\/\/#i", $picurl))
	{
		$picurl = GetRemPic($picurl);
	}


	//自动缩略图处理
	$r = $dosql->GetOne("SELECT `picwidth`,`picheight` FROM `#@__infoclass` WHERE `id`=$classid");
	if(!empty($r['picwidth']) &&
	   !empty($r['picheight']))
	{
		ImageResize(PHPMYWIND_ROOT.'/'.$picurl, $r['picwidth'], $r['picheight']);
	}


	$posttime = GetMkTime($posttime);


	//自定义字段处理
	$fieldname  = '';
	$fieldvalue = '';
	$fieldstr   = '';

	$ids = GetDiyFieldCatePriv($modelid,$classid);
	if(!empty($ids))
	{
		$dosql->Execute("SELECT * FROM `#@__diyfield` WHERE infotype=$modelid AND `id` IN ($ids) AND checkinfo=true ORDER BY orderid ASC");
		while($row = $dosql->GetArray())
		{
			$k = $row['fieldname'];
			if(isset($_POST[$row['fieldname']]))
			{
				if(is_array($_POST[$row['fieldname']]))
				{
					foreach($_POST[$row['fieldname']] as $post_value)
					{
						$v[] = addslashes($post_value);
					}
				}
				else
				{
					$v = addslashes($_POST[$row['fieldname']]);
				}
			}
			else
			{
				$v = '';
			}

			if(!empty($row['fieldcheck']))
			{
				if(!preg_match($row['fieldcheck'], $v))
				{
					ShowMsg($row['fieldcback']);
					exit();
				}
			}

			if($row['fieldtype'] == 'datetime')
			{
				$v = GetMkTime($v);
			}

			if($row['fieldtype'] == 'fileall')
			{
				$vTxt = isset($_POST[$row['fieldname'].'_txt']) ? $_POST[$row['fieldname'].'_txt'] : '';

				if(is_array($v) &&
				   is_array($vTxt))
				{
					$vNum = count($v);
					$vTmp = '';

					for($i=0;$i<$vNum;$i++)
					{
						$vTmp[] = $v[$i].','.addslashes($vTxt[$i]);
					}

					$v = serialize($vTmp);
				}
			}

			if($row['fieldtype'] == 'checkbox')
			{
				@$v = implode(',',$v);
			}

			$fieldname  .= ", $k";
			$fieldvalue .= ", '$v'";
			$fieldstr   .= ", $k='$v'";
		}
	}


	$sql = "INSERT INTO `$tbname` (siteid, classid, parentid, parentstr, title, flag, picurl, orderid, posttime, checkinfo {$fieldname}) VALUES ('$cfg_siteid', '$classid', '$parentid', '$parentstr', '$title', '$flag', '$picurl', '$orderid', '$posttime', '$checkinfo' {$fieldvalue})";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改模型信息
else if($action == 'update')
{
	//初始化信息
	if(!isset($title))     $title     = '';
	if(!isset($flag))      $flag      = '';
	if(!isset($picurl))    $picurl    = '';
	if(!isset($rempic))    $rempic    = '';
	if(!isset($orderid))   $orderid   = '';
	if(!isset($posttime))  $posttime  = '';
	if(!isset($checkinfo)) $checkinfo = 'true';
	if(is_array($flag)) $flag = implode(',',$flag);


	//栏目权限验证
	IsCategoryPriv($cid,'update');


	//获取parentstr
	$row = $dosql->GetOne("SELECT `parentid` FROM `#@__infoclass` WHERE `id`=$classid");
	$parentid = $row['parentid'];

	if($parentid == 0)
	{
		$parentstr = '0,';
	}
	else
	{
		$r = $dosql->GetOne("SELECT `parentstr` FROM `#@__infoclass` WHERE `id`=$parentid");
		$parentstr = $r['parentstr'].$parentid.',';
	}


	//保存远程缩略图
	if($rempic=='true' &&
	   preg_match("#^http:\/\/#i", $picurl))
	{
		$picurl = GetRemPic($picurl);
	}


	//自动缩略图处理
	$r = $dosql->GetOne("SELECT `picwidth`,`picheight` FROM `#@__infoclass` WHERE `id`=$classid");
	if(!empty($r['picwidth']) &&
	   !empty($r['picheight']))
	{
		ImageResize(PHPMYWIND_ROOT.'/'.$picurl, $r['picwidth'], $r['picheight']);
	}


	$posttime = GetMkTime($posttime);


	//自定义字段处理
	$fieldname  = '';
	$fieldvalue = '';
	$fieldstr   = '';

	$ids = GetDiyFieldCatePriv($modelid,$classid);
	if(!empty($ids))
	{
		$dosql->Execute("SELECT * FROM `#@__diyfield` WHERE infotype=$modelid AND `id` IN ($ids) AND checkinfo=true ORDER BY orderid ASC");
		while($row = $dosql->GetArray())
		{
			$k = $row['fieldname'];
			if(isset($_POST[$row['fieldname']]))
			{
				if(is_array($_POST[$row['fieldname']]))
				{
					foreach($_POST[$row['fieldname']] as $post_value)
					{
						$v[] = addslashes($post_value);
					}
				}
				else
				{
					$v = addslashes($_POST[$row['fieldname']]);
				}
			}
			else
			{
				$v = '';
			}

			if(!empty($row['fieldcheck']))
			{
				if(!preg_match($row['fieldcheck'], $v))
				{
					ShowMsg($row['fieldcback']);
					exit();
				}
			}

			if($row['fieldtype'] == 'datetime')
			{
				$v = GetMkTime($v);
			}

			if($row['fieldtype'] == 'fileall')
			{
				$vTxt = isset($_POST[$row['fieldname'].'_txt']) ? $_POST[$row['fieldname'].'_txt'] : '';

				if(is_array($v) &&
				   is_array($vTxt))
				{
					$vNum = count($v);
					$vTmp = '';

					for($i=0;$i<$vNum;$i++)
					{
						$vTmp[] = $v[$i].','.addslashes($vTxt[$i]);
					}

					$v = serialize($vTmp);
				}
			}

			if($row['fieldtype'] == 'checkbox')
			{
				@$v = implode(',',$v);
			}

			$fieldname  .= ", $k";
			$fieldvalue .= ", '$v'";
			$fieldstr   .= ", $k='$v'";
		}
	}


	$sql = "UPDATE `$tbname` SET siteid='$cfg_siteid', classid='$classid', parentid='$parentid', parentstr='$parentstr', title='$title', flag='$flag', picurl='$picurl', orderid='$orderid', posttime='$posttime', checkinfo='$checkinfo' {$fieldstr} WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//无状态返回
else
{
	header("location:$gourl");
	exit();
}
?>
