<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('info');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 16:49:43
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__info';
$gourl  = 'info.php';
$action = isset($action) ? $action : '';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//修改单页信息
if($action == 'update')
{
	//栏目权限验证
	IsCategoryPriv($classid,'update');


	//初始化参数
	if(!isset($mainid)) $mainid = '-1';

	$row = $dosql->GetOne("SELECT `parentid` FROM `#@__infoclass` WHERE `id`=$classid");
	$parentid = $row['parentid'];

	$parentstr = $doaction->GetParentStr();
	$posttime = GetMkTime($posttime);


	//自定义字段处理
	$fieldname  = '';
	$fieldvalue = '';
	$fieldstr   = '';

    $ids = GetDiyFieldCatePriv('0',$classid);
	if(!empty($ids))
	{
		$dosql->Execute("SELECT * FROM `#@__diyfield` WHERE infotype=0 AND `id` IN ($ids) AND checkinfo=true ORDER BY orderid ASC");
		while($row = $dosql->GetArray())
		{
			$k = $row['fieldname'];
			$v = '';
			if(isset($_POST[$row['fieldname']]))
			{
				if(is_array($_POST[$row['fieldname']]))
				{
					foreach($_POST[$row['fieldname']] as $post_value)
					{
						if(@!get_magic_quotes_gpc())
    					{
							$v[] = addslashes($post_value);
						}
						else
						{
							$v[] = $post_value;
						}
					}
				}
				else
				{
					if(@!get_magic_quotes_gpc())
					{
						$v = addslashes($_POST[$row['fieldname']]);
					}
					else
					{
						$v = $_POST[$row['fieldname']];
					}
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
						if(@!get_magic_quotes_gpc())
						{
							$vTmp[] = $v[$i].','.addslashes($vTxt[$i]);
						}
						else
						{
							$vTmp[] = $v[$i].','.$vTxt[$i];
						}
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


	//自动缩略图处理
	$r = $dosql->GetOne("SELECT `picwidth`,`picheight` FROM `#@__infoclass` WHERE `id`=$classid");
	if(!empty($r['picwidth']) &&
	   !empty($r['picheight']))
	{
		ImageResize(PHPMYWIND_ROOT.'/'.$picurl, $r['picwidth'], $r['picheight']);
	}


	//判断是否已存在
	$row2 = $dosql->GetOne("SELECT `id` FROM `#@__info` WHERE `classid`=$classid AND `mainid`=$mainid");
	if(empty($row2))
		$sql = "INSERT INTO `$tbname` (classid, mainid, content, picurl, posttime {$fieldname}) VALUES ('$classid', '$mainid', '$content', '$picurl', '$posttime' {$fieldvalue})";
	else
		$sql = "UPDATE `$tbname` SET classid='$classid', mainid='$mainid', content='$content', picurl='$picurl', posttime='$posttime' {$fieldstr} WHERE classid=$classid AND mainid=$mainid";

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
