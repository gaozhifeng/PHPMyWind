<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('member'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改会员</title>
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
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">修改会员</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="member_save.php" onsubmit="return cfm_upmember();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">用户名：</td>
			<td width="75%"><strong><?php echo $row['username']; ?></strong></td>
		</tr>
		<tr>
			<td height="40" align="right">密　码：</td>
			<td><input name="password" type="password" id="password" class="input" />
				<span class="maroon">*</span><span class="cnote">若不修改密码请留空</span></td>
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
				$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='question' AND level=0 ORDER BY orderid ASC, datavalue ASC");
				while($row2 = $dosql->GetArray())
				{
					if($row['question'] == $row2['datavalue'])
						$selected = 'selected="selected"';
					else
						$selected = '';

					echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
				}
				?>
				</select></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">回　答： </td>
			<td><input type="text" name="answer" id="answer" class="input" value="<?php echo $row['answer']; ?>" /></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">姓　名：</td>
			<td><input type="text" name="cnname" id="cnname" class="input" value="<?php echo $row['cnname']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">英文名：</td>
			<td><input type="text" name="enname" id="enname" class="input" value="<?php echo $row['enname']; ?>" /></td>
		</tr>
		<tr>
			<td height="165" align="right">头　像：</td>
			<td><img src="../data/avatar/index.php?uid=<?php echo $row['id']; ?>&size=middle&rnd=<?php echo GetRandStr(); ?>" />
				<div class="hr_10"></div><input name="delavatar" type="checkbox" value="1" />
				删除头像 (会员头像在前台编辑)</td>
		</tr>
		<tr>
			<td height="40" align="right">性　别：</td>
			<td><input name="sex" type="radio" value="0" <?php if($row['sex'] == '0') echo 'checked="checked"'; ?> />
				男&nbsp;
				<input name="sex" type="radio" value="1" <?php if($row['sex'] == '1') echo 'checked="checked"'; ?> />
				女</td>
		</tr>
		<tr>
			<td height="40" align="right">生　日：</td>
			<td><label>
					<input type="radio" name="birthtype" value="0" <?php if($row['birthtype'] == '0') echo 'checked="checked"'; ?> />
					公历生日 </label>
				<label>
					<input type="radio" name="birthtype" value="1" <?php if($row['birthtype'] == '1') echo 'checked="checked"'; ?> />
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
						if($row['birth_year'] == $nowyear)
							$selected = 'selected="selected"';
						else
							$selected = '';
					?>
					<option value="<?php echo $nowyear; ?>" <?php echo $selected; ?>><?php echo $nowyear; ?></option>
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

						if($row['birth_month'] == $nowmonth)
							$selected = 'selected="selected"';
						else
							$selected = '';
					?>
					<option value="<?php echo $nowmonth; ?>" <?php echo $selected; ?>><?php echo $nowmonth; ?></option>
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

						if($row['birth_day'] == $nowday)
							$selected = 'selected="selected"';
						else
							$selected = '';
					?>
					<option value="<?php echo $nowday; ?>" <?php echo $selected; ?>><?php echo $nowday; ?></option>
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
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='astro' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['astro'] == $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">血　型：</td>
			<td><select name="bloodtype" id="bloodtype">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='bloodtype' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['bloodtype'] == $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">行　业：</td>
			<td><select name="trade" id="trade">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='trade' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['trade'] == $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right"> 现居地：</td>
			<td><select name="live_prov" id="live_prov" onchange="SelProv(this.value,'live');">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['live_prov'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select>
				<select name="live_city" id="live_city" onchange="SelCity(this.value,'live');">
					<option value="-1">--</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue>".$row['live_prov']." AND datavalue<".($row['live_prov'] + 500)." ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['live_city'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select>
				<select name="live_country" id="live_country">
					<option value="-1">--</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=2 AND datavalue LIKE '".$row['live_city'].".%%%' ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['live_country'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">家　乡：</td>
			<td><select name="home_prov" id="home_prov" onchange="SelProv(this.value,'home');">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['live_prov'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select>
				<select name="home_city" id="home_city" onchange="SelCity(this.value,'home');">
					<option value="-1">--</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue>".$row['home_prov']." AND datavalue<".($row['home_prov'] + 500)." ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['home_city'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select>
				<select name="home_country" id="home_country">
					<option value="-1">--</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=2 AND datavalue LIKE '".$row['home_city'].".%%%' ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['home_country'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">证件号码：</td>
			<td><select name="cardtype" id="cardtype">
					<option value="-1">请选择</option>
					<option value="0" <?php if($row['cardtype'] == '0') echo 'selected="selected"'; ?>>身份证</option>
					<option value="1" <?php if($row['cardtype'] == '1') echo 'selected="selected"'; ?>>护照号</option>
					<option value="2" <?php if($row['cardtype'] == '2') echo 'selected="selected"'; ?>>驾驶证</option>
				</select>
				<input type="text" name="cardnum" id="cardnum" class="input" style="width:209px" value="<?php echo $row['cardnum']; ?>" /></td>
		</tr>
		<tr class="nb">
			<td height="116" align="right">个人说明：</td>
			<td><textarea name="intro" id="intro" class="textarea"><?php echo $row['intro']; ?></textarea></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">E-mail：</td>
			<td><input type="text" name="email" id="email" class="input" value="<?php echo $row['email']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">QQ号码：</td>
			<td><input type="text" name="qqnum" id="qqnum" class="input" value="<?php echo $row['qqnum']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">手　机：</td>
			<td><input type="text" name="mobile" id="mobile" class="input" value="<?php echo $row['mobile']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">固定电话：</td>
			<td><input type="text" name="telephone" id="telephone" class="input" value="<?php echo $row['telephone']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">通信地址：</td>
			<td><select name="address_prov" id="address_prov" onchange="SelProv(this.value,'address');">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['address_prov'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select>
				<select name="address_city" id="address_city" onchange="SelCity(this.value,'address');">
					<option value="-1">--</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue>".$row['address_prov']." AND datavalue<".($row['address_prov'] + 500)." ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['address_city'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select>
				<select name="address_country" id="address_country">
					<option value="-1">--</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=2 AND datavalue LIKE '".$row['address_city'].".%%%' ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['address_country'] === $row2['datavalue'])
							$selected = 'selected="selected"';
						else
							$selected = '';

						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
					}
					?>
				</select></td>
		</tr>
		<tr>
			<td height="40" align="right">&nbsp;</td>
			<td><input type="text" name="address" id="address" class="input" value="<?php echo $row['address']; ?>" /></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">邮　编：</td>
			<td><input type="text" name="zipcode" id="zipcode" class="input" value="<?php echo $row['zipcode']; ?>" /></td>
		</tr>
		<tr class="nb">
			<td colspan="2" height="26"><div class="line"></div></td>
		</tr>
		<tr>
			<td height="40" align="right">用户组：</td>
			<td>
			<strong>
			<?php
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
				
				if($row['expval'] > $r['expvalb'])
				{
					$r = $dosql->GetOne("SELECT `groupname` FROM `#@__usergroup` WHERE expvalb=".$r['expvalb']);
					$usergroup = $r['groupname'];
				}
				else
				{
					$usergroup = '参数获取失败';
				}
			}
			echo $usergroup;
			?>
			</strong></td>
		</tr>
		<tr>
			<td height="40" align="right">认证用户：</td>
			<td><input type="checkbox" name="enteruser" id="enteruser" value="1" <?php if($row['enteruser'] == '1') echo 'checked="checked"'; ?> />
			是</td>
		</tr>
		<tr>
			<td height="40" align="right">经验值：</td>
			<td><input type="text" name="expval" id="expval" class="input" value="<?php echo $row['expval']; ?>" />
			<span class="maroon">&nbsp;</span><span class="cnote">通过调整经验值改变用户组</span></td>
		</tr>
		<tr>
			<td height="40" align="right">积　分：</td>
			<td><input name="integral" type="text" id="integral" class="input" value="<?php echo $row['integral']; ?>" /></td>
		</tr>
		<tr>
			<td height="40" align="right">绑定QQ：</td>
			<td><?php if($row['qqid'] != ''){echo $row['qqid'].'&nbsp;&nbsp;<span class="cutimg"><a href="member_save.php?action=removeoqq&id='.$row['id'].'">解除绑定</a></span>';}else{echo '暂无绑定';} ?></td>
		</tr>
		<tr>
			<td height="40" align="right">绑定微博：</td>
			<td><?php if($row['weiboid'] != ''){echo $row['weiboid'].'&nbsp;&nbsp;<span class="cutimg"><a href="member_save.php?action=removeoweibo&id='.$row['id'].'">解除绑定</a></span>';}else{echo '暂无绑定';} ?></td>
		</tr>
		<tr>
			<td height="40" align="right">登录信息：</td>
			<td>TIME <?php echo GetDateTime($row['logintime']); ?>&nbsp;-&nbsp;IP <?php echo $row['loginip']; ?></td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">注册信息：</td>
			<td>TIME <?php echo GetDateTime($row['regtime']); ?>&nbsp;-&nbsp;IP <?php echo $row['regip']; ?></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
		<input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>" />
	</div>
</form>
</body>
</html>