<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('admin'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加管理员</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
</head>
<body>
<div class="formHeader"> <span class="title">添加管理员</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="admin_save.php" onsubmit="return cfm_admin();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">用户名：</td>
			<td width="75%"><input type="text" name="username" class="input" id="username" />
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="40" align="right">密　码：</td>
			<td><input type="password" name="password" class="input" id="password" />
				<span class="maroon">*</span><span class="cnote">6-16个字符组成，区分大小写</span></td>
		</tr>
		<tr>
			<td height="40" align="right">确　认：</td>
			<td><input type="password" name="repassword" class="input" id="repassword" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">提　问：</td>
			<td><select name="question" id="question">
				<?php
				$question = array('无安全提问',
								  '母亲的名字',
								  '爷爷的名字',
								  '父亲出生的城市',
								  '你其中一位老师的名字',
								  '你个人计算机的型号',
								  '你最喜欢的餐馆名称',
								  '驾驶执照最后四位数字');

				foreach($question as $k=>$v)
				{
					echo "<option value=\"$k\">$v</option>";									
				}
				?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">回　答：</td>
			<td><input type="text" name="answer" class="input" id="answer" /></td>
		</tr>
		<tr>
			<td height="40" align="right">昵　称：</td>
			<td><input type="text" name="nickname" class="input" id="nickname" />
				<span class="maroon">&nbsp;</span><span class="cnote">用于文章作者的显示</span></td>
		</tr>
		<tr>
			<td height="40" align="right">管理组：</td>
			<td><select name="levelname" id="levelname">
				<?php
				$dosql->Execute("SELECT * FROM `#@__admingroup` WHERE `checkinfo`='true' ORDER BY `id` ASC");
				while($row = $dosql->GetArray())
				{
					if($cfg_adminlevel == 1)
					{
						echo '<option value="'.$row['id'].'">'.$row['groupname'].'</option>';
					}
					else
					{
						if($row['id'] != 1)
							echo '<option value="'.$row['id'].'">'.$row['groupname'].'</option>';
					}
				}
				?>
				</select></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">审　核：</td>
			<td><input type="radio" name="checkadmin" value="true" checked="checked" />
				已审核&nbsp;
				<input type="radio" name="checkadmin" value="false" />
				未审核</td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="add" />
	</div>
</form>
</body>
</html>