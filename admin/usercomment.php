<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('usercomment'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户评论管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">用户评论管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);" /></td>
			<td width="4%">ID</td>
			<td width="5%">UID</td>
			<td width="10%">用户名</td>
			<td width="30%">评论内容</td>
			<td width="8%">模型ID</td>
			<td width="8%">信息ID</td>
			<td width="10%">时间</td>
			<td width="10%">IP</td>
			<td width="10%" class="endCol">操作</td>
		</tr>
		<?php

		$dopage->GetPage("SELECT * FROM `#@__usercomment`");
		while($row = $dosql->GetArray())
		{			
			switch($row['isshow'])
			{
				case '1':
					$checkinfo = '显示';
					break;  
				case '0':
					$checkinfo = '隐藏';
					break;
				default:
					$checkinfo = '没有获取到参数';
			}
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['uid']; ?></td>
			<td><?php echo $row['uname']; ?>
				<input type="hidden" name="id[]" id="id[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php
			if($row['molds'] == 1)
				$tbname = '#@__infolist';

			else if($row['molds'] == 2)
				$tbname = '#@__infoimg';

			else if($row['molds'] == 3)
				$tbname = '#@__soft';

			else if($row['molds'] == 4)
				$tbname = '#@__goods';
			
			else
			{
				$r = $dosql->GetOne("SELECT * FROM `#@__diymodel` WHERE `id`=".$row['molds']);
				if(isset($r) && is_array($r))
					$tbname = $r['modeltbname'];
				else
					$tbname = '';
			}

			$r = $dosql->GetOne("SELECT * FROM `$tbname` WHERE id=".$row['aid']."");
			?>
				<a href="<?php echo $row['link']; ?>" target="_blank" title="点击访问"><?php echo ClearHtml($row['body']); ?></a></td>
			<td><?php echo $row['molds']; ?></td>
			<td><?php echo $row['aid']; ?></td>
			<td class="number"><?php echo GetDateTime($row['time']); ?></td>
			<td><?php echo $row['ip']; ?></td>
			<td class="action endCol"><span><a href="usercomment_save.php?id=<?php echo $row['id']; ?>&action=check&checkinfo=<?php echo $row['isshow']; ?>" title="点击进行审核与未审操作"><?php echo $checkinfo; ?></a></span> | <span><a href="usercomment_save.php?action=del2&id=<?php echo $row['id'] ?>" onclick="return ConfDel(0);">删除</a></span></td>
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
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('usercomment_save.php');" onclick="return ConfDelAll(0);">删除</a></span> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('usercomment_save.php');" onclick="return ConfDelAll(0);">删除</a></span><span class="pageSmall">
			<?php echo $dopage->GetList(); ?>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>