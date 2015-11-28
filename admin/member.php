<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('member'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">会员管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="member_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="5%">ID</td>
			<td width="10%">头像</td>
			<td width="15%">用户名</td>
			<td width="15%">用户组</td>
			<td width="15%">登录时间</td>
			<td width="15%">经验值</td>
			<td width="10%">积分</td>
			<td width="10%" class="endCol">操作</td>
		</tr>
		<?php
		$dopage->GetPage("SELECT * FROM `#@__member`");

		while($row = $dosql->GetArray())
		{
			$usergroup = '';
			$dosql->Execute("SELECT * FROM `#@__usergroup`",$row['id']);
			while($row2 = $dosql->GetArray($row['id']))
			{
				if($row['expval'] >= $row2['expvala'] and
				   $row['expval'] <= $row2['expvalb'])
				{
					$usergroup = '<span style="color:'.$row2['color'].'">'.$row2['groupname'].'</span>';
				}
			}

			if($usergroup == '')
			{
				//系统不允许使用子查询
				$r = $dosql->GetOne("SELECT MAX(expvalb) as expvalb FROM `#@__usergroup`");
				
				if(isset($r['expvalb']) && ($row['expval'] > $r['expvalb']))
				{
					$r = $dosql->GetOne("SELECT `groupname` FROM `#@__usergroup` WHERE expvalb=".$r['expvalb']);
					$usergroup = $r['groupname'];
				}
				else
				{
					$usergroup = '参数获取失败';
				}
			}
			
			$oauthico = '';

			if($row['qqid'] != '')
				$oauthico .= '<span class="qqico" title="QQ账号绑定"></span>';

			if($row['weiboid'] != '')
				$oauthico .= '<span class="weiboico" title="微博账号绑定"></span>';
		?>
		<tr align="left" class="dataTr">
			<td height="60" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php echo $row['id']; ?></td>
			<td><span class="thumbs" style="width:48px;"><img src="../data/avatar/index.php?uid=<?php echo $row['id']; ?>&size=small&rnd=<?php echo GetRandStr(); ?>" width="48" height="48" /></span></td>
			<td><?php echo $oauthico.$row['username']; ?></td>
			<td><?php
			echo $usergroup;
			if($row['enteruser'] == 1) echo '<br /><span class="red">认证用户</span>';
			?></td>
			<td class="number"><?php echo GetDateMk($row['logintime']); ?><br />
				<?php echo MyDate('H:i:s',$row['logintime']); ?></td>
			<td><?php echo $row['expval']; ?></td>
			<td><?php echo $row['integral']; ?></td>
			<td class="action endCol"><span><a href="member_update.php?id=<?php echo $row['id']; ?>">修改</a></span> | <span class="nb"><a href="member_save.php?action=del2&id=<?php echo $row['id']; ?>" onclick="return ConfDel(0)">删除</a></span></td>
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
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('member_save.php');" onclick="return ConfDelAll(0);">删除</a></span> <a href="member_add.php" class="dataBtn">注册新会员</a> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('member_save.php');" onclick="return ConfDelAll(0);">删除</a></span> <a href="member_add.php" class="dataBtn">注册新会员</a> <span class="pageSmall"> <?php echo $dopage->GetList(); ?> </span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>