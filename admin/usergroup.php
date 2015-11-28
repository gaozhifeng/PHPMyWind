<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('usergroup'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户组管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">用户组管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="usergroup_save.php?action=save">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);" /></td>
			<td width="3%">ID</td>
			<td width="30%">用户组名称</td>
			<td width="20%">用户组经验值</td>
			<td width="15%">星星数</td>
			<td width="10%">头衔颜色</td>
			<td width="12%" class="endCol">操作</td>
		</tr>
		<?php
		$dosql->Execute("SELECT * FROM `#@__usergroup` ORDER BY id ASC");
		
		if($dosql->GetTotalRow() > 0)
		{
			while($row = $dosql->GetArray())
			{			
		?>
		<tr align="left" class="dataTr">
			<td height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php echo $row['id']; ?></td>
			<td><input type="text" name="groupname[]" id="groupname[]" class="inputd" value="<?php echo $row['groupname']; ?>" />
				<input type="hidden" name="id[]" id="id[]" value="<?php echo $row['id']; ?>" /></td>
			<td><input type="text" name="expvala[]" id="expvala[]" class="inputls" value="<?php echo $row['expvala']; ?>" />
				~
				<input type="text" name="expvalb[]" id="expvalb[]" class="inputls" value="<?php echo $row['expvalb']; ?>" /></td>
			<td><input type="text" name="stars[]" id="stars[]" class="inputls" value="<?php echo $row['stars']; ?>" /></td>
			<td><input type="text" name="color[]" id="color[]" class="inputs" value="<?php echo $row['color']; ?>" /></td>
			<td class="action endCol"><a href="usergroup_save.php?action=del2&id=<?php echo $row['id'] ?>" onclick="return ConfDel(0);">删除</a></td>
		</tr>
		<?php
			}
		}
		else
		{
		?>
		<tr align="center">
			<td colspan="7" class="dataEmpty">暂时没有相关的记录</td>
		</tr>
		<?Php
		}
		?>
		<tr align="center">
			<td height="36" colspan="7"><strong>新增一个用户组</strong></td>
		</tr>
		<tr align="left" class="dataTrOn">
			<td height="36">&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="text" name="groupname_add" id="groupname_add" class="input" /></td>
			<td><input type="text" name="expvala_add" id="expvala_add" class="inputls" value="" />
				~
				<input type="text" name="expvalb_add" id="expvalb_add" class="inputls" value="" /></td>
			<td><input type="text" name="stars_add" id="stars_add" class="inputls" /></td>
			<td><input type="text" name="color_add" id="color_add" class="inputs" /></td>
			<td>&nbsp;</td>
		</tr>
	</table>
</form>
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('usergroup_save.php');" onclick="return ConfDelAll(0);">删除</a></span> <a href="javascript:void(0);" onclick="form.submit();return false;" class="dataBtn">更新全部</a></div>
<div class="page">
	<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__usergroup'); ?></span>条记录</div>
</div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('usergroup_save.php');" onclick="return ConfDelAll(0);">删除</a></span> <a href="javascript:void(0);" onclick="form.submit();return false;" class="dataBtn">更新全部</a> <span class="pageSmall">
			<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__usergroup'); ?></span>条记录</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>