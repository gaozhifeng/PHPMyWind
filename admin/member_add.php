<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('member'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员注册</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="templates/js/getarea.js"></script>
<script type="text/javascript">
var xmlHttp;

function xmlhttprequest(){
	if(window.ActiveXObject){
		xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
	}
	else if(window.XMLHttpRequest){
		xmlHttp = new XMLHttpRequest();
	}
	else{
		alert('您的浏览器不支持Ajax技术！');
	}
}


//用户名检测
function CheckUser(){
	if(document.form.username.value == ''){
		document.getElementById('usernote').innerHTML = '　';
	}
	else{
		if(document.form.username.value.length < 4){
			document.getElementById('usernote').innerHTML = '<span class="regnotenok">用户名小于4位</span>';
			return;
		}
		xmlhttprequest();
		var username = document.getElementById('username').value;
		var url = "ajax_do.php?"+parseInt(Math.random()*(15271)+1)+'&action=checkuser&username='+username;
		xmlHttp.open("GET", url, true);
		xmlHttp.onreadystatechange = check_done;
		xmlHttp.send(null);
	}
}


function check_done(){
	if(xmlHttp.readyState == 4 && xmlHttp.status == 200){
		document.getElementById('usernote').innerHTML = xmlHttp.responseText;
		if('<span class="regnotenok">用户名已存在</span>' == xmlHttp.responseText){
			document.getElementById('isuser').value = '0';
		}
		else{
			document.getElementById('isuser').value = '';
		}
	}
}
</script>
</head>
<body>
<div class="formHeader"> <span class="title">注册会员</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="member_save.php" onsubmit="return cfm_member();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">用户名：</td>
			<td width="75%"><input type="text" name="username" id="username" class="input" onblur="CheckUser();" />
				<span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span> <span id="usernote"></span>
				<input type="hidden" id="isuser" name="isuser" value="" /></td>
		</tr>
		<tr>
			<td height="40" align="right">密　码：</td>
			<td><input name="password" type="password" id="password" class="input" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">确　认： </td>
			<td><input name="repassword" type="password" id="repassword" class="input" />
				<span class="maroon">*</span></td>
		</tr>
		<tr>
			<td height="40" align="right">提　问： </td>
			<td><select name="question" id="question">
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='question' ORDER BY orderid ASC, datavalue ASC");
					while($row = $dosql->GetArray())
					{
						echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">回　答： </td>
			<td><input type="text" name="answer" id="answer" class="input" /></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">姓　名：</td>
			<td><input type="text" name="cnname" id="cnname" class="input" />
				<span class="maroon">&nbsp;</span><span class="cnote">用户头像在前台编辑</span></td>
		</tr>
		<tr>
			<td height="40" align="right">英文名：</td>
			<td><input type="text" name="enname" id="enname" class="input" /></td>
		</tr>
		<tr>
			<td height="40" align="right">性　别：</td>
			<td><input name="sex" type="radio" value="0" checked="checked"  />
				男&nbsp;
				<input name="sex" type="radio" value="1"  />
				女</td>
		</tr>
		<tr>
			<td height="40" align="right">生　日：</td>
			<td><label>
					<input type="radio" name="birthtype" value="0" checked="checked" />
					公历生日 </label>
				<label>
					<input type="radio" name="birthtype" value="1" />
					农历生日</label></td>
		</tr>
		<tr>
			<td height="40" align="right">&nbsp;</td>
			<td><select name="birth_year" id="birth_year">
					<option value="-1">请选择</option>
					<?php
					$nowyear = MyDate('Y',time());
					for($nowyear; $nowyear>=1900; $nowyear--)
					{
					?>
					<option value="<?php echo $nowyear; ?>"><?php echo $nowyear; ?></option>
					<?php
					}
					?>
				</select>
				年
				<select name="birth_month" id="birth_month">
					<option value="-1">--</option>
					<?php
					for($nowmonth=1; $nowmonth<=12; $nowmonth++)
					{
						if($nowmonth <= 9) $nowmonth = '0'.$nowmonth;
					?>
					<option value="<?php echo $nowmonth; ?>"><?php echo $nowmonth; ?></option>
					<?php
					}
					?>
				</select>
				月
				<select name="birth_day" id="birth_day">
					<option value="-1">--</option>
					<?php
					for($nowday=1; $nowday<=31; $nowday++)
					{
						if($nowday < 10) $nowday = '0'.$nowday;
					?>
					<option value="<?php echo $nowday; ?>"><?php echo $nowday; ?></option>
					<?php
					}
					?>
				</select>
				日</td>
		</tr>
		<tr>
			<td height="40" align="right">星　座：</td>
			<td><select name="astro" id="astro">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='astro'");
					while($row = $dosql->GetArray())
					{
						echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">血　型：</td>
			<td><select name="bloodtype" id="bloodtype">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='bloodtype' ORDER BY orderid ASC, datavalue ASC");
					while($row = $dosql->GetArray())
					{
						echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">行　业：</td>
			<td><select name="trade" id="trade">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='trade'");
					while($row = $dosql->GetArray())
					{
						echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right"> 现居地：</td>
			<td id="live"><select name="live_prov" id="live_prov" onchange="SelProv(this.value,'live');">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row = $dosql->GetArray())
					{
						echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
					}
					?>
				</select>
				<select name="live_city" id="live_city" onchange="SelCity(this.value,'live');">
					<option value="-1">--</option>
				</select>
				<select name="live_country" id="live_country">
					<option value="-1">--</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">家　乡：</td>
			<td id="home"><select name="home_prov" id="home_prov" onchange="SelProv(this.value,'home');">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row = $dosql->GetArray())
					{
						echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
					}
					?>
				</select>
				<select name="home_city" id="home_city" onchange="SelCity(this.value,'home');">
					<option value="-1">--</option>
				</select>
				<select name="home_country" id="home_country">
					<option value="-1">--</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">证件号码：</td>
			<td><select name="cardtype" id="cardtype">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='cardtype' ORDER BY orderid ASC, datavalue ASC");
					while($row = $dosql->GetArray())
					{
						echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
					}
					?>
				</select>
				<input type="text" name="cardnum" id="cardnum" class="input" style="width:209px" /></td>
		</tr>
		<tr class="nb">
			<td height="116" align="right">个人说明：</td>
			<td><textarea name="intro" id="intro" class="textarea"></textarea></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">E-mail：</td>
			<td><input type="text" name="email" id="email" class="input" /></td>
		</tr>
		<tr>
			<td height="40" align="right">QQ号码：</td>
			<td><input type="text" name="qqnum" id="qqnum" class="input" /></td>
		</tr>
		<tr>
			<td height="40" align="right">手　机：</td>
			<td><input type="text" name="mobile" id="mobile" class="input" /></td>
		</tr>
		<tr>
			<td height="40" align="right">固定电话：</td>
			<td><input type="text" name="telephone" id="telephone" class="input" /></td>
		</tr>
		<tr>
			<td height="40" align="right">通信地址：</td>
			<td><select name="address_prov" id="address_prov" onchange="SelProv(this.value,'address');">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row = $dosql->GetArray())
					{
						echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
					}
					?>
				</select>
				<select name="address_city" id="address_city" onchange="SelCity(this.value,'address');">
					<option value="-1">--</option>
				</select>
				<select name="address_country" id="address_country">
					<option value="-1">--</option>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">&nbsp;</td>
			<td><input type="text" name="address" id="address" class="input" /></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">邮　编：</td>
			<td><input type="text" name="zipcode" id="zipcode" class="input" /></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">认证用户：</td>
			<td><input type="checkbox" name="enteruser" id="enteruser" value="1" />
				是</td>
		</tr>
		<tr>
			<td height="40" align="right">经验值：</td>
			<td><input type="text" name="expval" id="expval" class="input" value="10" />
				<span class="maroon">&nbsp;</span><span class="cnote">通过调整经验值改变用户组</span></td>
		</tr>
		<tr>
			<td height="40" align="right">积　分：</td>
			<td><input type="text" name="integral" id="integral" class="input" value="0" /></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">注册时间：</td>
			<td><input type="text" name="regtime" id="regtime" class="inputms" value="<?php echo GetDateTime(time()); ?>" readonly="readonly" />
				<script type="text/javascript" src="plugin/calendar/calendar.js"></script> 
				<script type="text/javascript">
				Calendar.setup({
					inputField     :    "regtime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
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