<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('upload_filemgr_sql'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传文件管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">上传文件管理</span> <span class="text">[<a href="upload_filemgr_sql.php" class="topFolder">返回数据模式</a>]</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="">
	<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);" /></td>
			<td width="20%">文件名称</td>
			<td width="15%">文件类型</td>
			<td width="15%">上传日期</td>
			<td width="15%">文件大小</td>
			<td width="15%">使用状态</td>
			<td width="15%" class="endCol">操作</td>
		</tr>
		<?php

		//初始化参数
		$mid_array = array(0,1,2,3,4);
		$tb_array  = array(
			'admanage'   => array('picurl'),
			'goods'      => array('picurl', 'content', 'picarr'),
			'goodsbrand' => array('picurl'),
			'goodstype'  => array('picurl'),
			'info'       => array('picurl', 'content'),
			'infoclass'  => array('picurl'),
			'infoimg'    => array('picurl', 'content', 'picarr'),
			'infolist'   => array('picurl', 'content', 'picarr'),
			'job'        => array('workdesc', 'content'),
			'soft'       => array('picurl', 'content', 'picarr'),
			'message'    => array('content'),
			'nav'        => array('picurl'),
			'weblink'    => array('picurl')
		);


		//自定义模型中存在附件的字段
		$dosql->Execute("SELECT * FROM `#@__diymodel` ORDER BY `id` ASC");
		while($row = $dosql->GetArray())
		{
			$mtbname = str_replace($db_tablepre, '', $row['modeltbname']);
			
			//自定义模型加入数组
			$mid_array[] = $row['id'];
			
			//自定义模型表名和字段加入数组
			$tb_array[$mtbname] = array('picurl');
		}


		//自定义字段中存在附件的字段
		foreach($mid_array as $infotype)
		{
			$dosql->Execute("SELECT * FROM `#@__diyfield` WHERE infotype='$infotype' AND (`fieldtype`='mediumtext' or `fieldtype`='file' or `fieldtype`='fileall') AND checkinfo=true ORDER BY orderid ASC");
			while($row = $dosql->GetArray())
			{
				if($infotype == 0)
					array_push($tb_array['info'], $row['fieldname']);
				
				if($infotype == 1)
					array_push($tb_array['infolist'], $row['fieldname']);
				
				if($infotype == 2)
					array_push($tb_array['infoimg'], $row['fieldname']);
				
				if($infotype == 3)
					array_push($tb_array['soft'], $row['fieldname']);
				
				if($infotype == 4)
					array_push($tb_array['goods'], $row['fieldname']);
				
				//自定义模型
				if($infotype == $row['infotype'])
				{
					$r = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `id`=$infotype");
					
					if(!empty($r['modeltbname']))
						array_push($tb_array[str_replace($db_tablepre, '', $r['modeltbname'])], $row['fieldname']);
				}
			}
		}


		//初始化参数		
		$fl_str = '';
		$img_ext   = $cfg_upload_img_type;
		$a_ext     = $cfg_upload_soft_type;
		$embed_ext = $cfg_upload_media_type;


		//取出所有存储图片路径
		//循环所有表
		foreach($tb_array as $k=>$tbname)
		{

			//循环表包含图片的字段
			foreach($tbname as $field)
			{

				//取出字段内容
				$dosql->Execute("SELECT `$field` FROM `#@__$k`");
				while($row = $dosql->GetArray())
				{
					
					//如果是内容字段，匹配字符串
					if($field == 'content')
					{
						preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?(?:['.$img_ext.']))[\'|\"].*?[\/]?>/', $row[$field], $match);
						if(!empty($match[1]) && is_array($match[1]))
						{
							foreach($match[1] as $path)
							{
								$fl_str .= $path.',';
							}
						}

						preg_match_all('/<[a|A].*?href=[\'|\"](.*?(?:['.$a_ext.']))[\'|\"].*?[\/]?>/', $row[$field], $match);
						if(!empty($match[1]) && is_array($match[1]))
						{
							foreach($match[1] as $path)
							{
								$fl_str .= $path.',';
							}
						}
						
						preg_match_all('/<[embed|EMBED].*?src=[\'|\"](.*?(?:['.$embed_ext.']))[\'|\"].*?[\/]?>/', $row[$field], $match);
						if(!empty($match[1]) && is_array($match[1]))
						{
							foreach($match[1] as $path)
							{
								$fl_str .= $path.',';
							}
						}
					}
					
					//组图、缩略图直接连接
					else
					{
						$fl_str .= $row[$field].',';
					}

				}
			}
		}


		//查询上传文件记录
		$dosql->Execute("SELECT * FROM `#@__uploads` ORDER BY `id` DESC");
		$i = 0;
		while($row = $dosql->GetArray())
		{

			//对比是否在已用字符串中出现
			if(!strpos($fl_str,$row['path']))
			{
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['path']; ?>" /></td>
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['type']; ?></td>
			<td class="number"><span><?php echo GetDateTime($row['posttime']); ?></span></td>
			<td><?php echo GetRealSize($row['size']); ?></td>
			<td>未使用</td>
			<td class="action endCol"><span><a href="../<?php echo $row['path']; ?>" target="_blank">预览</a></span> | <span class="nb"><a href="upload_filemgr_save.php?mode=sql&action=del&id=<?php echo $row['id']; ?>&path=<?php echo $row['path']; ?>" onclick="return ConfDel(0);">删除</a></span></td>
		</tr>
		<?php
				$i++;
			}
		}

		$fl_str = '';
		?>
	</table>
</form>
<?php
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有上传的文件</div>';
}
else if($i == 0)
{
	echo '<div class="dataEmpty">暂时没有可清理的文件</div>';
}
?>
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAll('upload_filemgr_save.php','&mode=sql');" onclick="return ConfDelAll(0);">删除</a></span></div>
<div class="page">
	<div class="pageText">共有<span><?php echo $i; ?></span>条记录</div>
</div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAll('upload_filemgr_save.php','&mode=sql');" onclick="return ConfDelAll(0);">删除</a></span> <span class="pageSmall">
			<div class="pageText">共有<span><?php echo $i; ?></span>条记录</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>