<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('diymenu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加菜单项</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript">
$(function(){
	$(".addNewTr").click(function(){

		$("#orderidnum").attr("value", parseInt($("#orderidnum").val())+1);

		var str = '<tr align="left" class="dataTr"><td height="40" class="firstCol action"><a href="javascript:;" onclick="delThisTr($(this))">删</a></td><td align="left"><input type="text" name="classnameadd[]" id="classnameadd[]" class="input" /></td><td align="left"><input type="text" name="linkurladd[]" id="linkurladd[]" class="input" /></td><td align="center"><input type="text" name="orderidadd[]" id="orderidadd[]" class="inputls" value="'+$("#orderidnum").val()+'" /></td></tr>';
	
		$("#newline").append(str);

	});
	
	
	$("input[name^=orderidadd]").val(parseInt($("#orderid").val())+1);
	$("#orderidnum").val(parseInt($("#orderidnum").val())+1);
})


function delThisTr(trobj)
{
	trobj.parent().parent().remove();
	$("#orderidnum").val(parseInt($("#orderidnum").val())-1);
}
</script>
</head>
<body>
<div class="formHeader"> <span class="title">添加菜单项</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="diymenu_save.php" onsubmit="return cfm_diymenu();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="80" height="40" class="firstCol">菜单名称：</td>
			<td><input type="text" name="classname" id="classname" class="input" />
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="40" class="firstCol">排列顺序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputs" value="<?php echo GetOrderID('#@__diymenu'); ?>" /></td>
		</tr>
		<tr class="nb">
			<td width="80" height="40" class="firstCol">隐藏类别：</td>
			<td><input type="radio" name="checkinfo" value="true" checked="checked"  />
				显示&nbsp;
				<input type="radio" name="checkinfo" value="false" />
				隐藏</td>
		</tr>
		<tr class="nb">
			<td height="40" colspan="2">&nbsp;</td>
		</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="newline" class="formTable">
		<tr align="left" class="head">
			<td width="80" height="36" class="firstCol">操作</td>
			<td>菜单项名称</td>
			<td>跳转链接</td>
			<td align="center">排序</td>
		</tr>
		<tr align="left" class="dataTr">
			<td height="40" class="firstCol"><a href="javascript:;" class="addNewTr" title="新增一行"></a></td>
			<td><input type="text" name="classnameadd[]" id="classnameadd[]" class="input" /></td>
			<td><input type="text" name="linkurladd[]" id="linkurladd[]" class="input" /></td>
			<td align="center"><input type="text" name="orderidadd[]" id="orderidadd[]" class="inputls" value="" /></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="add" />
		<input type="hidden" name="orderidnum" id="orderidnum" value="<?php echo GetOrderID('#@__diymenu'); ?>" />
	</div>
</form>
</body>
</html>