<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('infoimg');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:01:13
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__infoimg';
$gourl  = 'infoimg.php';
$action = isset($action) ? $action : '';


//添加图片信息
if($action == 'add')
{
	//栏目权限验证
	IsCategoryPriv($classid,'add');


	//初始化参数
	if(!isset($mainid))        $mainid = '-1';
	if(!isset($flag))          $flag   = '';
	if(!isset($picarr))        $picarr = '';
	if(!isset($rempic))        $rempic = '';
	if(!isset($remote))        $remote = '';
	if(!isset($autothumb))     $autothumb = '';
	if(!isset($autodesc))      $autodesc = '';
	if(!isset($autodescsize))  $autodescsize = '';
	if(!isset($autopage))      $autopage = '';
	if(!isset($autopagesize))  $autopagesize = '';


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


	//获取mainid
	if($mainid != '-1')
	{
		$row = $dosql->GetOne("SELECT `parentid` FROM `#@__maintype` WHERE `id`=$mainid");
		$mainpid = $row['parentid'];

		if($mainpid == 0)
		{
			$mainpstr = '0,';
		}
		else
		{
			$r = $dosql->GetOne("SELECT parentstr FROM `#@__maintype` WHERE id=$mainpid");
			$mainpstr = $r['parentstr'].$mainpid.',';
		}
	}
	else
	{
		$mainpid  = '-1';
		$mainpstr = '';
	}


	//文章属性
	if(is_array($flag))
	{
		$flag = implode(',',$flag);
	}


	//文章组图
	if(is_array($picarr) &&
	   is_array($picarr_txt))
	{
		$picarrNum = count($picarr);
		$picarrTmp = '';

		for($i=0;$i<$picarrNum;$i++)
		{
			$picarrTmp[] = $picarr[$i].','.$picarr_txt[$i];
		}

		$picarr = serialize($picarrTmp);
	}


	//保存远程缩略图
	if($rempic=='true' &&
	   preg_match("#^http:\/\/#i", $picurl))
	{
		$picurl = GetRemPic($picurl);
	}


	//保存远程资源
	if($remote == 'true')
	{
		$content = GetContFile($content);
	}


	//第一个图片作为缩略图
	if($autothumb == 'true')
	{
		$cont_str = stripslashes($content);
		preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/', $cont_str, $imgurl);

		//如果存在图片
		if(isset($imgurl[1][0]))
		{
			$picurl = $imgurl[1][0];
			$picurl = substr($picurl, strpos($picurl, 'uploads/'));
		}
	}


	//自动提取内容到摘要
	if($autodesc == 'true')
	{
		if(empty($autodescsize) or
		   !intval($autodescsize))
		{
			$autodescsize = 200;
		}

		$descstr     = ClearHtml($content);
		$description = ReStrLen($descstr, $autodescsize);

	}


	//自动分页
    if($autopage == 'true')
    {
        $content = ContAutoPage($content, $autopagesize*1024);
    }


	$posttime = GetMkTime($posttime);


	//自定义字段处理
	$fieldname  = '';
	$fieldvalue = '';
	$fieldstr   = '';

	$ids = GetDiyFieldCatePriv('2',$classid);
	if(!empty($ids))
	{
		$dosql->Execute("SELECT * FROM `#@__diyfield` WHERE infotype=2 AND `id` IN ($ids) AND checkinfo=true ORDER BY orderid ASC");
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


	$sql = "INSERT INTO `$tbname` (siteid, classid, parentid, parentstr, mainid, mainpid, mainpstr, title, colorval, boldval, flag, source, author, linkurl, keywords, description, content, picurl, picarr, orderid, hits, posttime, checkinfo {$fieldname}) VALUES ('$cfg_siteid', '$classid', '$parentid', '$parentstr', '$mainid', '$mainpid', '$mainpstr', '$title', '$colorval', '$boldval', '$flag', '$source', '$author', '$linkurl', '$keywords', '$description', '$content', '$picurl', '$picarr', '$orderid', '$hits', '$posttime', '$checkinfo' {$fieldvalue})";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改图片信息
else if($action == 'update')
{
	//栏目权限验证
	IsCategoryPriv($cid,'update');


	//初始化参数
	if(!isset($mainid))        $mainid = '-1';
	if(!isset($flag))          $flag   = '';
	if(!isset($picarr))        $picarr = '';
	if(!isset($rempic))        $rempic = '';
	if(!isset($remote))        $remote = '';
	if(!isset($autothumb))     $autothumb = '';
	if(!isset($autodesc))      $autodesc = '';
	if(!isset($autodescsize))  $autodescsize = '';
	if(!isset($autopage))      $autopage = '';
	if(!isset($autopagesize))  $autopagesize = '';


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


	//获取mainid
	if($mainid != '-1')
	{
		$row = $dosql->GetOne("SELECT `parentid` FROM `#@__maintype` WHERE `id`=$mainid");
		$mainpid = $row['parentid'];

		if($mainpid == 0)
		{
			$mainpstr = '0,';
		}
		else
		{
			$r = $dosql->GetOne("SELECT parentstr FROM `#@__maintype` WHERE id=$mainpid");
			$mainpstr = $r['parentstr'].$mainpid.',';
		}
	}
	else
	{
		$mainpid  = '-1';
		$mainpstr = '';
	}


	//文章属性
	if(is_array($flag))
	{
		$flag = implode(',',$flag);
	}


	//文章组图
	if(is_array($picarr) &&
	   is_array($picarr_txt))
	{
		$picarrNum = count($picarr);
		$picarrTmp = '';

		for($i=0;$i<$picarrNum;$i++)
		{
			$picarrTmp[] = $picarr[$i].','.$picarr_txt[$i];
		}

		$picarr = serialize($picarrTmp);
	}


	//保存远程缩略图
	if($rempic=='true' and
	   preg_match("#^http:\/\/#i", $picurl))
	{
		$picurl = GetRemPic($picurl);
	}


	//保存远程资源
	if($remote == 'true')
	{
		$content = GetContFile($content);
	}


	//第一个图片作为缩略图
	if($autothumb == 'true')
	{
		$cont_str = stripslashes($content);
		preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/', $cont_str, $imgurl);

		//如果存在图片
		if(isset($imgurl[1][0]))
		{
			$picurl = $imgurl[1][0];
			$picurl = substr($picurl, strpos($picurl, 'uploads/'));
		}
	}


	//自动提取内容到摘要
	if($autodesc == 'true')
	{
		if(empty($autodescsize) or !intval($autodescsize))
		{
			$autodescsize = 200;
		}

		$descstr     = ClearHtml($content);
		$description = ReStrLen($descstr, $autodescsize);

	}


	//自动分页
    if($autopage == 'true')
    {
        $content = ContAutoPage($content, $autopagesize*1024);
    }


	$posttime = GetMkTime($posttime);


	//自定义字段处理
	$fieldname  = '';
	$fieldvalue = '';
	$fieldstr   = '';

	$ids = GetDiyFieldCatePriv('2',$classid);
	if(!empty($ids))
	{
		$dosql->Execute("SELECT * FROM `#@__diyfield` WHERE infotype=2 AND `id` IN ($ids) AND checkinfo=true ORDER BY orderid ASC");
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


	$sql = "UPDATE `$tbname` SET siteid='$cfg_siteid', classid='$classid', parentid='$parentid', parentstr='$parentstr', mainid='$mainid', mainpid='$mainpid', mainpstr='$mainpstr', title='$title', colorval='$colorval', boldval='$boldval', flag='$flag', source='$source', author='$author', linkurl='$linkurl', keywords='$keywords', description='$description', content='$content', picurl='$picurl', picarr='$picarr', orderid='$orderid', hits='$hits', posttime='$posttime', checkinfo='$checkinfo' {$fieldstr} WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改审核状态
else if($action == 'check')
{
	//审核权限
	$r = $dosql->GetOne("SELECT `classid` FROM `#@__infoimg` WHERE `id`=$id");
	IsCategoryPriv($r['classid'],'update');


	if($checkinfo == '已审')
	{
		$dosql->ExecNoneQuery("UPDATE `$tbname` SET `checkinfo`='false' WHERE `id`=$id");
		echo '<a href="javascript:;" onclick="CheckInfo('.$id.',\'未审\')" title="点击进行审核与未审操作">未审</a>';
		exit();
	}

	if($checkinfo == '未审')
	{
		$dosql->ExecNoneQuery("UPDATE `$tbname` SET `checkinfo`='true' WHERE `id`=$id");
		echo '<a href="javascript:;" onclick="CheckInfo('.$id.',\'已审\')" title="点击进行审核与未审操作">已审</a>';
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
