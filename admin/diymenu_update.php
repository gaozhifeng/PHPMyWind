<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('diymenu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改菜单项</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript">
$(function(){
	$(".addNewTr").click(function(){

		$("#orderidnum").attr("value", parseInt($("#orderidnum").val())+1);

		var str = '<tr align="left" class="dataTr"><td height="40" class="firstCol action"><a href="javascript:;" onclick="delThisTr($(this))">删</a></td><td align="left"><input type="text" name="classnameadd[]" id="classnameadd[]" class="input" /></td><td align="left"><input type="text" name="linkurladd[]" id="linkurladd[]" class="input" /></td><td align="center"><input type="text" name="orderidadd[]" id="orderidadd[]" class="inputls" value="'+$("#orderidnum").val()+'" /></td></tr>';
	
		$("#newline").append(str);

	});

})


function delThisTr(trobj)
{
	trobj.parent().parent().remove();
	$("#orderidnum").val(parseInt($("#orderidnum").val())-1);
}
</script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__diymenu` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改菜单项</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="diymenu_save.php" onsubmit="return cfm_diymenu();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="80" height="40" class="firstCol">菜单名称：</td>
			<td><input type="text" name="classname" id="classname" class="input" value="<?php echo $row['classname']; ?>" />
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="40" class="firstCol">排列顺序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputs" value="<?php echo $row['orderid']; ?>" /></td>
		</tr>
		<tr class="nb">
			<td width="80" height="40" class="firstCol">隐藏类别：</td>
			<td><input type="radio" name="checkinfo" id="checkinfo" value="true" <?php if($row['checkinfo'] == 'true') echo 'checked="checked"'; ?> />
				显示&nbsp;
				<input type="radio" name="checkinfo" id="checkinfo" value="false" <?php if($row['checkinfo'] == 'false') echo 'checked="checked"'; ?> />
				隐藏</td>
		</tr>
		<tr class="nb">
			<td height="40" colspan="2">&nbsp;</td>
		</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="newline" class="formTable">
		<tr align="left" class="head">
			<td width="80" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);" /></td>
			<td align="left">菜单项名称</td>
			<td align="left">跳转链接</td>
			<td align="center">排序</td>
			<td width="10%" class="endCol">操作</td>
		</tr>
		<?php
		$dosql->Execute("SELECT * FROM `#@__diymenu` WHERE parentid=$id ORDER BY orderid ASC");
		while($row = $dosql->GetArray())
		{					
		?>
		<tr align="left">
			<td width="80" height="40" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td align="left"><input type="text" name="classnameupdate[]" id="classnameupdate[]" class="input" value="<?php echo $row['classname']; ?>" />
				<input type="hidden" name="upid[]" id="upid[]" value="<?php echo $row['id']; ?>" /></td>
			<td align="left"><input type="text" name="linkurlupdate[]" id="linkurlupdate[]" class="input" value="<?php echo $row['linkurl']; ?>" /></td>
			<td align="center"><a href="diymenu_save.php?id=<?php echo $row['id']; ?>&orderid=<?php echo $row['orderid']; ?>&action=up&tid=<?php echo $id; ?>" class="leftArrow" title="提升排序"></a>
				<input type="text" name="orderidupdate[]" id="orderidupdate[]" class="inputls" value="<?php echo $row['orderid']; ?>" />
				<a href="diymenu_save.php?id=<?php echo $row['id']; ?>&orderid=<?php echo $row['orderid']; ?>&action=down&tid=<?php echo $id; ?>" class="rightArrow" title="下降排序"></a></td>
			<td class="action endCol"><span><a href="diymenu_save.php?action=deldiymenu&tid=<?php echo $id; ?>&id=<?php echo $row['id'] ?>" onclick="return ConfDel(0);">删除</a></span></td>
		</tr>
		<?php
		}
		?>
		<tr align="left" class="dataTr">
			<td height="40" class="firstCol"><a href="javascript:AddNewTr();" class="addNewTr" title="新增一行"></a></td>
			<td><input type="text" name="classnameadd[]" id="classnameadd[]" class="input" /></td>
			<td><input type="text" name="linkurladd[]" id="linkurladd[]" class="input" /></td>
			<td align="center"><input type="text" name="orderidadd[]" id="orderidadd[]" class="inputls" value="<?php echo GetOrderID('#@__diymenu'); ?>" /></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>" />
	<input type="hidden" name="orderidnum" id="orderidnum" value="<?php echo GetOrderID('#@__diymenu'); ?>" />
</form>
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('diymenu_save.php?action=delalldiymenu');" onclick="return ConfDelAll(0);">删除</a></span> <a href="#" onclick="UpdateForm('diymenu_save.php');" class="dataBtn">更新全部</a> <a href="diymenu.php" class="dataBack">返回列表</a> </div>
</body>
</html>