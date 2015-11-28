<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('vote'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>投票信息管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">投票信息管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="job_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="5%">ID</td>
			<td width="20%">投票标题</td>
			<td width="10%">开始时间</td>
			<td width="10%">结束时间</td>
			<td width="10%">游客投票</td>
			<td width="10%">查看投票</td>
			<td width="15%">发布时间</td>
			<td width="15%" class="endCol">操作</td>
		</tr>
		<?php
		$dopage->GetPage("SELECT * FROM `#@__vote` WHERE `siteid`='$cfg_siteid'");
		while($row = $dosql->GetArray())
		{			
			$r = $dosql->GetOne("SELECT COUNT(id) as total FROM `#@__votedata` WHERE voteid=".$row['id']);
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['title']; ?></td>
			<td class="number"><?php if(empty($row['starttime'])){echo '不限制';}else{echo GetDateTime($row['starttime']);} ?></td>
			<td class="number"><?php if(empty($row['endtime'])){echo '不限制';}else{echo GetDateTime($row['endtime']);} ?></td>
			<td><?php if($row['isguest']=='true'){echo '允许';} else{echo '不允许';} ?></td>
			<td><?php if($row['isview']=='true'){echo '允许';} else{echo '不允许';} ?></td>
			<td class="number"><?php echo GetDateTime($row['posttime']); ?></td>
			<td class="action endCol"><span><a href="../vote.php?id=<?php echo $row['id']; ?>" target="_blank" title="查看投票结果">预览</a></span> | <span><a href="vote_update.php?id=<?php echo $row['id']; ?>">修改</a></span> | <span class="nb"><a href="vote_save.php?action=delvote&id=<?php echo $row['id'] ?>" onclick="return ConfDel(0);">删除</a></span></td>
		</tr>
		<?php
		}	
		?>
	</table>
</form>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('vote_save.php');" onclick="return ConfDelAll(0);">删除</a></span> <a href="vote_add.php" class="dataBtn">添加投票信息</a> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('vote_save.php');" onclick="return ConfDelAll(0);">删除</a></span> <a href="vote_add.php" class="dataBtn">添加投票信息</a><span class="pageSmall"> <?php echo $dopage->GetList(); ?> </span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>