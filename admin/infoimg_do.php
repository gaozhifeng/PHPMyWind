<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('infoimg');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-28 15:30:12
person: Feng
**************************
*/


//初始化参数
$action  = isset($action)  ? $action  : '';
$keyword = isset($keyword) ? $keyword : '';


//删除单条记录
if($action == 'del')
{
	//栏目权限验证
	$r = $dosql->GetOne("SELECT `classid` FROM `#@__$tbname` WHERE `id`=$id");
	IsCategoryPriv($r['classid'],'del',1);

	$deltime = time();
	$dosql->ExecNoneQuery("UPDATE `#@__$tbname` SET delstate='true', deltime='$deltime' WHERE id=$id");
}


//删除选中记录
if($action == 'delall')
{
	if($ids != '')
	{
		//解析id,验证是否有删除权限
		$ids = explode(',',$ids);
		$idstr = '';
		foreach($ids as $id)
		{
			$r = $dosql->GetOne("SELECT `classid` FROM `#@__$tbname` WHERE `id`=$id");
			if(IsCategoryPriv($r['classid'],'del',1))
			{
				$idstr .= $id.',';
			}
		}
		$idstr .= trim($idstr,',');

		if($idstr != '')
		{
			$deltime = time();
			$dosql->ExecNoneQuery("UPDATE `#@__$tbname` SET delstate='true', deltime='$deltime' WHERE `id` IN ($idstr)");
		}
	}
}
?>

<div class="toolbarTab">
	<ul>
		<?php

		$flagArr = array('all'=>'全部', 'notcheck'=>'未审', 'ischeck'=>'已审');


		$dosql->Execute("SELECT * FROM `#@__infoflag` ORDER BY `orderid` ASC");
		while($row = $dosql->GetArray())
		{
			$flagArr[$row['flag']] = $row['flagname'];
		}


		$flagArrNum = count($flagArr);

		foreach($flagArr as $k => $v)
		{
			if($flag == $k)
				$flagOn = 'on';
			else
				$flagOn = '';

			echo '<li class="'.$flagOn.'"><a href="javascript:;" onclick="GetFlag(\''.$k.'\')">'.$v.'</a></li><li class="line">-</li>';
		}

		if($flag == 'author')
			$flagOn = 'on';
		else
			$flagOn = '';

		echo '<li class="'.$flagOn.'"><a href="javascript:;" onclick="GetFlag(\'author\')">我发布的文档</a></li><li class="line">-</li><li><a href="javascript:;" onclick="ShowRecycle();">内容回收站</a></li>';
		?>
	</ul>
	<div id="search" class="search"> <span class="s">
		<input name="keyword" id="keyword" type="text" title="输入标题名进行搜索" value="<?php echo $keyword; ?>" />
		</span> <span class="b"><a href="javascript:;" onclick="GetSearch();"></a></span></div>
	<div class="cl"></div>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="ajaxlist" class="dataTable">
	<tr align="left" class="head">
		<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
		<td width="10%">缩略图</td>
		<td width="5%">ID</td>
		<td width="20%">标题</td>
		<td width="15%">栏目</td>
		<td width="15%">更新时间</td>
		<td width="10%">发布人</td>
		<td width="5%">点击</td>
		<td width="15%" class="endCol">操作</td>
	</tr>
	<?php

	//检查全局分页数
	if(empty($cfg_pagenum))  $cfg_pagenum = 20;


	//权限验证
	if($cfg_adminlevel != 1)
	{
		//初始化参数
		$catgoryListPriv   = '';
		$catgoryUpdatePriv = array();
		$catgoryDelPriv    = array();

		$dosql->Execute("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`='category' AND `action`<>'add'");
		while($row = $dosql->GetArray())
		{
			//查看权限
			if($row['action'] == 'list')
				$catgoryListPriv .= $row['classid'].',';

			//修改权限
			if($row['action'] == 'update')
				$catgoryUpdatePriv[] = $row['classid'];

			//删除权限
			if($row['action'] == 'del')
				$catgoryDelPriv[]    = $row['classid'];

		}

		$catgoryListPriv = trim($catgoryListPriv,',');
	}


	//设置sql
	$sql = "SELECT * FROM `#@__$tbname` WHERE siteid='$cfg_siteid' AND delstate=''";

	if(!empty($catgoryListPriv)) $sql .= " AND classid IN ($catgoryListPriv)";

	if(!empty($cid))     $sql .= " AND (classid=$cid OR parentstr Like '%,$cid,%')";

	if(!empty($keyword)) $sql .= " AND title LIKE '%$keyword%'";

	if(!empty($flag))
	{
		if($flag == 'all')
			$sql .= 'AND id<>0';
		else if($flag == 'notcheck')
			$sql .= "AND checkinfo='false'";
		else if($flag == 'ischeck')
			$sql .= "AND checkinfo='true'";
		else if($flag == 'author')
			$sql .= "AND author='".$_SESSION['admin']."'";
		else
		{
			$dosql->Execute("SELECT `flag` FROM `#@__infoflag`");
			while($row = $dosql->GetArray())
			{
				if($row['flag'] == $flag)
				{
					$sql .= "AND `flag` LIKE '%$flag%'";
				}
			}
		}
	}


	$dopage->GetPage($sql);
	while($row = $dosql->GetArray())
	{

		//标题名称
		$title  = '<span class="title" style="color:'.$row['colorval'].';font-weight:'.$row['boldval'].'">'.$row['title'];
		$title .= '<span class="titflag">';


		//二级分类
		if($cfg_maintype == 'Y')
		{
			$r = $dosql->GetOne('SELECT `classname` FROM `#@__maintype` WHERE `id`='.$row['mainid']);

			if(isset($r['classname']))
			{
				$title .= '['.$r['classname'].'] ';
			}
		}


		//信息属性
		$flagarr = explode(',',$row['flag']);
		$flagnum = count($flagarr);
		for($i=0; $i<$flagnum; $i++)
		{
			$r = $dosql->GetOne("SELECT `flagname` FROM `#@__infoflag` WHERE `flag`='".$flagarr[$i]."'");

			if(isset($r['flagname']))
			{
				$title .= $r['flagname'].'&nbsp;';
			}
		}

		$title .= '</span>';
		$title .= '</span>';


		//获取类型名称
		$r = $dosql->GetOne("SELECT classname FROM `#@__infoclass` WHERE id=".$row['classid']);

		if(isset($r['classname']))
			$classname = $r['classname'].' ['.$row['classid'].']';
		else
			$classname = '<span class="red">分类已删 ['.$row['classid'].']</span>';


		//获取审核状态
		switch($row['checkinfo'])
		{
			case 'true':
				$checkinfo = '已审';
				break;
			case 'false':
				$checkinfo = '未审';
				break;
			default:
				$checkinfo = '没有获取到参数';
		}


		//修改权限
		if($cfg_adminlevel != 1)
		{
			if(in_array($row['classid'], $catgoryUpdatePriv))
				$updateStr = '<a href="infoimg_update.php?cid='.$cid.'&id='.$row['id'].'">修改</a>';
			else
				$updateStr = '修改';
		}
		else
		{
			$updateStr = '<a href="infoimg_update.php?cid='.$cid.'&id='.$row['id'].'">修改</a>';
		}


		//删除权限
		if($cfg_adminlevel != 1)
		{
			if(in_array($row['classid'], $catgoryDelPriv))
				$delStr = '<a href="javascript:;" onclick="ClearInfo('.$row['id'].')">删除</a>';
			else
				$delStr = '删除';
		}
		else
		{
			$delStr = '<a href="javascript:;" onclick="ClearInfo('.$row['id'].')">删除</a>';
		}


		//审核权限
		if($cfg_adminlevel != 1)
		{
			if(in_array($row['classid'], $catgoryUpdatePriv))
				$checkStr = '<a href="javascript:;" title="点击进行审核与未审操作" onclick="CheckInfo('.$row['id'].',\''.$checkinfo.'\')">'.$checkinfo.'</a>';
			else
				$checkStr = $checkinfo;
		}
		else
		{
			$checkStr = '<a href="javascript:;" title="点击进行审核与未审操作" onclick="CheckInfo('.$row['id'].',\''.$checkinfo.'\')">'.$checkinfo.'</a>';
		}
	?>
	<tr align="left" class="dataTr" onmouseover="this.className='dataTrOn'" onmouseout="this.className='dataTr'">
		<td height="70" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
		<td><span class="thumbs"><?php echo GetMgrThumbs($row['picurl']); ?></span></td>
		<td><?php echo $row['id']; ?></td>
		<td><span class="title"><?php echo $title; ?></span></td>
		<td><?php echo $classname; ?></td>
		<td class="number"><?php echo GetDateTime($row['posttime']); ?></td>
		<td><?php echo $row['author']; ?></td>
		<td><?php echo $row['hits']; ?></td>
		<td class="action endCol"><span id="check<?php echo $row['id']; ?>"><?php echo $checkStr; ?></span> | <span><?php echo $updateStr; ?></span> | <span class="nb"><?php echo $delStr; ?></span></td>
	</tr>
	<?php
	}
	?>
</table>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:;" onclick="AjaxClearAll();">删除</a></span> <a href="infoimg_add.php" class="dataBtn">添加图片信息</a></span> </div>
<div class="page"> <?php echo $dopage->AjaxPage(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:;" onclick="AjaxClearAll();">删除</a></span> <a href="infoimg_add.php" class="dataBtn">添加图片信息</a></span> <span class="pageSmall"><?php echo $dopage->AjaxPageSmall(); ?></span> </div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
<script>
$(function(){
    $(".thumbs img").LoadImage();
});
</script>
